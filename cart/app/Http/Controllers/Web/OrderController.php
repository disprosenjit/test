<?php

namespace App\Http\Controllers\Web;

use App\Models\Commerce\Order;
use App\Helpers\PaymentMethodHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            $item->product->inventory->release($item->quantity);
        }

        return redirect("/orders/$order->id")
            ->with('success', 'Order cancelled successfully');
    }
}
