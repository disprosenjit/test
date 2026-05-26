<?php

namespace Plugins\PaymentPayPal\Controllers;

use App\Models\Commerce\Order;
use App\Models\Payment\PaymentMethod;
use App\Http\Controllers\Controller;
use Plugins\PaymentPayPal\Services\PayPalService;
use Illuminate\Http\Request;

class PayPalPaymentController extends Controller
{
    /**
     * Display PayPal checkout page
     */
    public function checkout(Order $order, ?string $token = null)
    {
        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!PaymentMethod::isEnabled('paypal')) {
            return redirect("/orders/{$order->id}")
                ->with('error', 'PayPal payment method is not available');
        }

        if ($order->payment_status !== 'pending') {
            return redirect("/orders/{$order->id}")
                ->with('error', 'This order does not require payment');
        }

        $paypalService = new PayPalService($order);
        $configStatus = PayPalService::getConfigStatus();

        return view('payment-paypal::checkout.paypal-checkout', compact('order', 'configStatus', 'token'));
    }

    /**
     * Initiate PayPal payment
     */
    public function initiate(Request $request, Order $order)
    {
        if (auth()->check() && $order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!PaymentMethod::isEnabled('paypal')) {
            return response()->json(['error' => 'PayPal payment method is not available'], 403);
        }

        if ($order->payment_status !== 'pending') {
            return response()->json(['error' => 'Payment already processed'], 422);
        }

        try {
            $paypalService = new PayPalService($order);
            $result = $paypalService->processPayment();

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Capture PayPal payment (AJAX endpoint after approval)
     */
    public function capture(Request $request, Order $order)
    {
        if (auth()->check() && $order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!PaymentMethod::isEnabled('paypal')) {
            return response()->json(['error' => 'PayPal payment method is not available'], 403);
        }

        $validated = $request->validate([
            'token' => 'required|string',
        ]);

        try {
            $paypalService = new PayPalService($order);
            $result        = $paypalService->captureOrder($validated['token']);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Handle PayPal success callback
     */
    public function success(Order $order)
    {
        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403);
        }

        return redirect("/orders/{$order->id}")
            ->with('success', 'Payment completed successfully!');
    }

    /**
     * Handle PayPal cancellation
     */
    public function cancel(Order $order)
    {
        return redirect("/orders/{$order->id}")
            ->with('info', 'Payment was cancelled. You can try again.');
    }
}
