<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Commerce\Order;
use App\Models\Commerce\Cart;
use App\Models\User\Address;
use App\Services\OrderService;
use App\Services\CartService;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'shipping_address_id' => 'nullable|integer|exists:addresses,id',
            'billing_address_id' => 'nullable|integer|exists:addresses,id',
            'name' => 'required_without:shipping_address_id,billing_address_id|string|max:255',
            'phone' => 'required_without:shipping_address_id,billing_address_id|string|max:20',
            'address_line_1' => 'required_without:shipping_address_id,billing_address_id|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required_without:shipping_address_id,billing_address_id|string|max:100',
            'state' => 'required_without:shipping_address_id,billing_address_id|string|max:100',
            'postal_code' => 'required_without:shipping_address_id,billing_address_id|string|max:20',
            'country' => 'required_without:shipping_address_id,billing_address_id|string|max:100',
            'payment_method' => 'required|in:bank_transfer,upi,credit_card,debit_card,stripe,paypal',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $user = auth()->check() ? auth()->user() : $request->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            $cart = $user->getActiveCart();

            if (!$cart) {
                return response()->json(['error' => 'Cart is empty'], 422);
            }

            $shippingAddressId = $request->shipping_address_id;
            $billingAddressId = $request->billing_address_id;

            if (!$shippingAddressId || !$billingAddressId) {
                $address = Address::create([
                    'user_id' => $user->id,
                    'type' => 'both',
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address_line_1' => $request->address_line_1,
                    'address_line_2' => $request->address_line_2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'postal_code' => $request->postal_code,
                    'country' => $request->country,
                    'is_default' => false,
                ]);

                $shippingAddressId = $shippingAddressId ?: $address->id;
                $billingAddressId = $billingAddressId ?: $address->id;
            }

            $orderService = new OrderService($user, $cart);
            $order = $orderService->createOrder(
                $shippingAddressId,
                $billingAddressId,
                $request->payment_method,
                $request->notes
            );

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $orderService->getOrderSummary($order),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|min:1|max:50',
        ]);

        $user = auth()->check() ? auth()->user() : $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->with(['items', 'payments', 'shipment'])
            ->paginate($request->get('per_page', 15));

        return response()->json($orders);
    }

    public function show(Request $request, int $orderId)
    {
        $user = auth()->check() ? auth()->user() : $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $order = Order::where('user_id', $user->id)
            ->with(['items.product', 'payments', 'shipment', 'shippingAddress', 'billingAddress'])
            ->findOrFail($orderId);

        return response()->json($order);
    }

    public function trackStatus(Request $request, int $orderId)
    {
        $user = auth()->check() ? auth()->user() : $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $order = Order::where('user_id', $user->id)->findOrFail($orderId);

        return response()->json([
            'order_number' => $order->order_number,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'timeline' => [
                'created_at' => $order->created_at,
                'confirmed_at' => $order->confirmed_at,
                'shipped_at' => $order->shipped_at,
                'delivered_at' => $order->delivered_at,
            ],
            'tracking' => $order->shipment?->tracking_details,
            'tracking_number' => $order->shipment?->tracking_number,
        ]);
    }

    public function cancel(Request $request, int $orderId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $user = auth()->check() ? auth()->user() : $request->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            $order = Order::where('user_id', $user->id)->findOrFail($orderId);
            $orderService = new OrderService($user, new Cart());

            $orderService->cancelOrder($order, $request->reason);

            return response()->json(['message' => 'Order cancelled successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function getInvoice(Request $request, int $orderId)
    {
        $user = auth()->check() ? auth()->user() : $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $order = Order::where('user_id', $user->id)->findOrFail($orderId);

        // In production, generate PDF invoice
        // For now, return JSON data
        return response()->json($order->load('items', 'shippingAddress', 'billingAddress'));
    }
}
