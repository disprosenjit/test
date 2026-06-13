<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commerce\Order;
use App\Models\Commerce\Shipment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminOrderController extends Controller
{
    /**
     * Display all orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items', 'payment', 'shipment']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search by order ID or customer name
        if ($request->search) {
            $query->where('id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($q) {
                      $q->where('name', 'like', '%' . request('search') . '%');
                  });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        $stats = $this->getOrderStats();

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payment', 'shipment', 'shippingAddress']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string'
        ]);

        $oldStatus = $order->status;
        $order->update([
            'status' => $validated['status'],
            'notes' => $order->notes . "\n[" . now() . "] Status changed from $oldStatus to {$validated['status']}"
        ]);

        // Send notification if status changed to shipped
        if ($validated['status'] === 'shipped') {
            // Queue email notification
        }

        return redirect("/admin/orders/$order->id")
            ->with('success', 'Order status updated successfully');
    }

    /**
     * Create shipment
     */
    public function createShipment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string|unique:shipments,tracking_number',
            'carrier' => 'required|in:dhl,fedex,ups,local',
            'expected_delivery' => 'required|date',
        ]);

        Shipment::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            ...$validated,
            'status' => 'in_transit'
        ]);

        $order->update(['status' => 'shipped']);

        return redirect("/admin/orders/$order->id")
            ->with('success', 'Shipment created and order marked as shipped');
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, Order $order)
    {
        $validated = $request->validate([
            'reason' => 'required|string'
        ]);

        $order->update([
            'status' => 'cancelled',
            'notes' => $order->notes . "\n[" . now() . "] Cancelled: " . $validated['reason']
        ]);

        // Release inventory reservations
        foreach ($order->items as $item) {
            $item->product->inventory->release($item->quantity);
        }

        return redirect("/admin/orders/$order->id")
            ->with('success', 'Order cancelled and inventory released');
    }

    /**
     * Get order statistics
     */
    private function getOrderStats()
    {
        return [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'items']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();
        
        $filename = 'orders_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://memory', 'w');

        fputcsv($handle, ['Order ID', 'Customer', 'Email', 'Items', 'Total', 'Status', 'Date']);

        foreach ($orders as $order) {
            $itemsSummary = $order->items->map(function ($item) {
                return "{$item->product_name} (SKU: {$item->product_sku}) x {$item->quantity}";
            })->implode(', ');

            fputcsv($handle, [
                $order->id,
                $order->user->name,
                $order->user->email,
                $itemsSummary,
                $order->total,
                $order->status,
                $order->created_at->format('Y-m-d')
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=$filename");
    }
}
