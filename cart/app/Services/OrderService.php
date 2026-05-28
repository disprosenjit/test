<?php

namespace App\Services;

use App\Models\Commerce\Cart;
use App\Models\Commerce\Order;
use App\Models\Commerce\OrderItem;
use App\Models\User\User;
use App\Models\User\Address;
use App\Models\Commerce\Inventory;
use Illuminate\Database\Eloquent\Collection;

class OrderService
{
    protected User $user;
    protected Cart $cart;

    public function __construct(User $user, Cart $cart)
    {
        $this->user = $user;
        $this->cart = $cart;
    }

    /**
     * Create order from cart
     */
    public function createOrder(
        int $shippingAddressId,
        int $billingAddressId,
        string $paymentMethod,
        string $notes = null
    ): Order {
        // Validate cart
        $cartService = new CartService($this->user);
        $errors = $cartService->validate();

        if (!empty($errors)) {
            throw new \Exception(implode(', ', $errors));
        }

        // Create order
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $this->user->id,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $paymentMethod,
            'shipping_address_id' => $shippingAddressId,
            'billing_address_id' => $billingAddressId,
            'subtotal' => $this->cart->subtotal,
            'tax' => $this->cart->tax,
            'shipping' => 0, // Calculate based on address
            'discount' => 0,
            'total' => $this->cart->subtotal + $this->cart->tax,
            'notes' => $notes,
        ]);

        // Auto-update user's billing address if not already set
        $billingAddress = Address::find($billingAddressId);
        if ($billingAddress && !$this->userHasBillingAddress()) {
            // Mark this address as the billing address if user doesn't have one
            $billingAddress->update(['type' => 'both']);
        }

        // Create order items and reserve inventory for physical products only.
        foreach ($this->cart->items as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'product_sku' => $cartItem->product->sku,
                'product_name' => $cartItem->product->name,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->price,
                'subtotal' => $cartItem->subtotal,
            ]);

            if ($cartItem->product->isPhysical()) {
                $inventory = Inventory::where('product_id', $cartItem->product_id)->first();
                if ($inventory) {
                    $inventory->reserve($cartItem->quantity);
                }

                $cartItem->product->decrement('stock_qty', $cartItem->quantity);
            }
        }

        // Clear cart
        $this->cart->clear();

        return $order->load('items', 'shippingAddress', 'billingAddress');
    }

    /**
     * Get order details
     */
    public function getOrder(int $orderId): ?Order
    {
        return Order::with(['items.product', 'payments', 'shipment'])
            ->where('user_id', $this->user->id)
            ->find($orderId);
    }

    /**
     * Get user orders
     */
    public function getUserOrders($limit = 15)
    {
        return Order::where('user_id', $this->user->id)
            ->orderBy('created_at', 'desc')
            ->with(['items', 'payments'])
            ->paginate($limit);
    }

    /**
     * Update order status (admin)
     */
    public function updateOrderStatus(Order $order, string $status, string $notes = null): Order
    {
        $order->update([
            'status' => $status,
            'admin_notes' => $notes,
        ]);

        if ($status === 'shipped') {
            $order->markAsShipped();
        } elseif ($status === 'delivered') {
            $order->markAsDelivered();
        }

        return $order;
    }

    /**
     * Cancel order (if not paid/shipped)
     */
    public function cancelOrder(Order $order, string $reason = null): bool
    {
        if (in_array($order->status, ['shipped', 'delivered'])) {
            throw new \Exception('Cannot cancel shipped/delivered orders');
        }

        if ($order->payment_status === 'approved') {
            throw new \Exception('Cannot cancel paid orders. Please initiate refund.');
        }

        $order->update([
            'status' => 'cancelled',
            'admin_notes' => $reason,
        ]);

        // Release reserved inventory
        foreach ($order->items as $item) {
            if ($item->product && $item->product->isPhysical()) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                if ($inventory) {
                    $inventory->release($item->quantity);
                }

                $item->product->increment('stock_qty', $item->quantity);
            }
        }

        return true;
    }

    /**
     * Get order summary for display
     */
    public function getOrderSummary(Order $order): array
    {
        return [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'subtotal' => $order->subtotal,
            'tax' => $order->tax,
            'shipping' => $order->shipping,
            'discount' => $order->discount,
            'total' => $order->total,
            'items' => $order->items->map(fn($item) => [
                'product_name' => $item->product_name,
                'sku' => $item->product_sku,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->subtotal,
            ])->toArray(),
            'shipping_address' => $order->shippingAddress?->getFullAddress(),
            'billing_address' => $order->billingAddress?->getFullAddress(),
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
        ];
    }

    /**
     * Check if user has a billing address set
     */
    private function userHasBillingAddress(): bool
    {
        return (bool) $this->user->addresses()
            ->whereIn('type', ['billing', 'both'])
            ->exists();
    }
}
