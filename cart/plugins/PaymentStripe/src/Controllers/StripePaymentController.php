<?php

namespace Plugins\PaymentStripe\Controllers;

use App\Models\Commerce\Order;
use App\Models\Payment\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Plugins\PaymentStripe\Services\StripeService;

class StripePaymentController extends Controller
{
    /**
     * Display card payment page for an order
     */
    public function show(Order $order)
    {
        // Authorize - user must own the order
        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if Stripe payment method is enabled
        if (!PaymentMethod::isEnabled('stripe')) {
            return redirect("/orders/{$order->id}")
                ->with('error', 'Card payment method is not available');
        }

        // Check if order is pending payment
        if ($order->payment_status !== 'pending') {
            return redirect("/orders/{$order->id}")
                ->with('error', 'This order does not require payment');
        }

        // Get Stripe settings if available
        $stripeConfig = null;
        if (StripeService::isConfigured()) {
            $stripeConfig = StripeService::getSettings();
        }

        return view('payment-stripe::checkout.card-payment', compact('order', 'stripeConfig'));
    }

    /**
     * Process card payment
     */
    public function process(Request $request, Order $order)
    {
        // Authorize
        if (auth()->check() && $order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if Stripe payment method is enabled
        if (!PaymentMethod::isEnabled('stripe')) {
            return response()->json(['error' => 'Card payment method is not available'], 403);
        }

        if ($order->payment_status !== 'pending') {
            return response()->json(['error' => 'Payment already processed'], 422);
        }

        $validated = $request->validate([
            'token' => 'required|string',
        ]);

        try {
            $stripeService = new StripeService($order);
            $result = $stripeService->processPayment($validated['token']);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
