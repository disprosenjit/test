<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Commerce\Order;
use App\Models\Payment\Payment;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function initiateBankTransfer(Request $request, int $orderId)
    {
        $user = auth()->check() ? auth()->user() : $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $order = Order::where('user_id', $user->id)->findOrFail($orderId);

        if ($order->payment_status !== 'pending') {
            return response()->json(['error' => 'Payment already processed'], 422);
        }

        $paymentService = new PaymentService($order);
        $response = $paymentService->processBankTransfer();

        return response()->json($response);
    }

    public function initiateUPI(Request $request, int $orderId)
    {
        $user = auth()->check() ? auth()->user() : $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $order = Order::where('user_id', $user->id)->findOrFail($orderId);

        if ($order->payment_status !== 'pending') {
            return response()->json(['error' => 'Payment already processed'], 422);
        }

        $paymentService = new PaymentService($order);
        $response = $paymentService->processUPIPayment();

        return response()->json($response);
    }

    public function initiateCard(Request $request, int $orderId)
    {
        $user = auth()->check() ? auth()->user() : $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $order = Order::where('user_id', $user->id)->findOrFail($orderId);

        if ($order->payment_status !== 'pending') {
            return response()->json(['error' => 'Payment already processed'], 422);
        }

        $paymentService = new PaymentService($order);
        $response = $paymentService->processCardPayment();

        return response()->json($response);
    }

    public function initiateStripe(Request $request, int $orderId)
    {
        $user = auth()->check() ? auth()->user() : $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $order = Order::where('user_id', $user->id)->findOrFail($orderId);

        if ($order->payment_status !== 'pending') {
            return response()->json(['error' => 'Payment already processed'], 422);
        }

        $paymentService = new PaymentService($order);
        $response = $paymentService->processStripePayment();

        return response()->json($response);
    }

    public function initiatePaypal(Request $request, int $orderId)
    {
        $user = auth()->check() ? auth()->user() : $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $order = Order::where('user_id', $user->id)->findOrFail($orderId);

        if ($order->payment_status !== 'pending') {
            return response()->json(['error' => 'Payment already processed'], 422);
        }

        $paymentService = new PaymentService($order);
        $response = $paymentService->processPaypalPayment();

        return response()->json($response);
    }

    public function verifyBankTransfer(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'transaction_reference' => 'nullable|string|max:100',
            'proof_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        try {
            $user = auth()->check() ? auth()->user() : $request->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            $order = Order::where('user_id', $user->id)
                ->findOrFail($request->order_id);

            $paymentService = new PaymentService($order);
            $proofPath = null;

            if ($request->hasFile('proof_file')) {
                $proofPath = $request->file('proof_file')->store('payment-proofs', 'public');
            }

            $paymentService->verifyBankTransfer($proofPath, $request->transaction_reference);

            return response()->json([
                'message' => 'Payment proof submitted. Awaiting admin verification.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function getPaymentStatus(Request $request, int $orderId)
    {
        $user = auth()->check() ? auth()->user() : $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $order = Order::where('user_id', $user->id)->findOrFail($orderId);
        $paymentService = new PaymentService($order);

        return response()->json($paymentService->getPaymentStatus());
    }

    // Webhook for payment gateway callbacks
    public function webhookHandler(Request $request)
    {
        // Verify webhook signature from payment gateway
        $signature = $request->header('X-Signature');
        $payload = $request->getContent();

        // Verify signature (implement based on gateway)
        // if (!$this->verifySignature($payload, $signature)) {
        //     return response()->json(['error' => 'Invalid signature'], 403);
        // }

        $data = $request->json()->all();
        $payment = Payment::where('transaction_reference', $data['transaction_id'] ?? null)->first();

        if ($payment && $data['status'] === 'success') {
            $payment->markAsApproved($data['transaction_id']);
        } elseif ($payment) {
            $payment->markAsFailed($data['error'] ?? 'Payment failed');
        }

        return response()->json(['status' => 'ok']);
    }
}
