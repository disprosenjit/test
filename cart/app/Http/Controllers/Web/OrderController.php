<?php

namespace App\Http\Controllers\Web;

use App\Models\Commerce\Order;
use App\Models\Commerce\OrderItem;
use App\Helpers\PaymentMethodHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use PDF;

class OrderController extends Controller
{
    /**
     * Display user's orders
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items', 'payment', 'shipment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $enabledMethods = PaymentMethodHelper::getEnabledForView();

        return view('frontend.orders.index', compact('orders', 'enabledMethods'));
    }

    /**
     * Display order details
     */
    public function show(Order $order)
    {
        // Authorize
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product', 'payment', 'shipment']);
        $enabledMethods = PaymentMethodHelper::getEnabledForView();

        return view('frontend.orders.show', compact('order', 'enabledMethods'));
    }

    /**
     * Download invoice
     */
    public function invoice(Order $order)
    {
        // Authorize
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product', 'user', 'shippingAddress', 'billingAddress', 'shipment']);

        $pdf = PDF::loadView('frontend.orders.invoice', compact('order'));
        return $pdf->download('invoice_' . $order->order_number . '.pdf');
    }

    /**
     * Download purchased product file.
     */
    public function downloadItem(Order $order, OrderItem $item)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        abort_unless($item->order_id === $order->id, 404);

        $item->loadMissing('product');

        if ($order->payment_status !== 'approved') {
            return back()->with('error', 'Downloads are available after payment approval.');
        }

        if (!$item->product || !$item->product->hasDownloadFile()) {
            abort(404);
        }

        $disk = Storage::disk('local');

        abort_unless($disk->exists($item->product->download_file_path), 404);

        return $disk->download(
            $item->product->download_file_path,
            $item->product->getDownloadName()
        );
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, Order $order)
    {
        // Authorize
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This order cannot be cancelled');
        }

        $validated = $request->validate([
            'reason' => 'required|string'
        ]);

        $order->update([
            'status' => 'cancelled',
            'notes' => $order->notes . "\n[" . now() . "] Cancelled by user: " . $validated['reason']
        ]);

        // Release inventory
        foreach ($order->items as $item) {
            if ($item->product && $item->product->isPhysical() && $item->product->inventory) {
                $item->product->inventory->release($item->quantity);
            }
        }

        return redirect("/orders/$order->id")
            ->with('success', 'Order cancelled successfully');
    }
}
