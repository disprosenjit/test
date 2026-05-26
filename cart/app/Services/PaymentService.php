<?php

namespace App\Services;

use App\Models\Commerce\Order;
use App\Models\Payment\Payment;
use Plugins\PaymentStripe\Services\StripeService;

class PaymentService
{
    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Handle Bank Transfer
     */
    public function processBankTransfer(): array
    {
        // Create payment record
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'method' => 'bank_transfer',
            'status' => 'pending',
            'amount' => $this->order->total,
            'metadata' => [
                'bank_details' => $this->getBankDetails(),
            ],
        ]);

        return [
            'payment_id' => $payment->id,
            'status' => 'pending',
            'bank_details' => $this->getBankDetails(),
            'amount' => $this->order->total,
            'order_number' => $this->order->order_number,
        ];
    }

    /**
     * Get bank details for transfer
     */
    protected function getBankDetails(): array
    {
        return [
            'account_holder' => 'Ship Spare Parts Store',
            'bank_name' => 'HDFC Bank',
            'account_number' => '1234567890123456',
            'ifsc_code' => 'HDFC0001234',
            'upi_id' => 'shipparts@hdfc',
        ];
    }

    /**
     * Handle UPI Payment
     */
    public function processUPIPayment(): array
    {
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'method' => 'upi',
            'status' => 'pending',
            'amount' => $this->order->total,
        ]);

        // Generate UPI payment link
        $upiLink = $this->generateUPIPaymentLink();

        return [
            'payment_id' => $payment->id,
            'status' => 'pending',
            'upi_link' => $upiLink,
            'qr_code_url' => $this->generateQRCode(),
            'amount' => $this->order->total,
            'order_number' => $this->order->order_number,
        ];
    }

    /**
     * Generate UPI payment link
     */
    protected function generateUPIPaymentLink(): string
    {
        $upiId = 'shipparts@hdfc';
        $payee = 'ShipSpareParts';
        $amount = intval($this->order->total);
        $transactionRef = $this->order->order_number;
        $description = "Payment for order {$this->order->order_number}";

        return "upi://pay?pa={$upiId}&pn=" . urlencode($payee) . "&am={$amount}&tn=" . urlencode($description) . "&tr={$transactionRef}";
    }

    /**
     * Generate QR code for UPI
     * In production, use a library like chillerlan/php-qrcode
     */
    protected function generateQRCode(): string
    {
        // Placeholder - implement with actual QR code library
        return asset('placeholder-qr.png');
    }

    /**
     * Handle Credit/Debit Card Payment (Gateway Integration)
     */
    public function processCardPayment(): array
    {
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'method' => 'credit_card',
            'status' => 'pending',
            'amount' => $this->order->total,
        ]);

        // In production, integrate with payment gateway (Razorpay, PayU, etc.)
        // For now, return gateway token
        return [
            'payment_id' => $payment->id,
            'status' => 'pending',
            'gateway_token' => $this->generateGatewayToken(),
            'amount' => $this->order->total,
            'order_number' => $this->order->order_number,
        ];
    }

    /**
     * Handle Stripe Payment - Initialize payment processing
     */
    public function processStripePayment(): array
    {
        // Check if Stripe is configured
        if (!StripeService::isConfigured()) {
            return [
                'success' => false,
                'message' => 'Stripe is not configured',
                'error' => 'Payment gateway not available',
            ];
        }

        // Get Stripe public key for frontend token generation
        $publicKey = StripeService::getPublicKey();

        // Create payment record in pending state
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'method' => 'stripe',
            'status' => 'pending',
            'amount' => $this->order->total,
            'metadata' => [
                'provider' => 'stripe',
            ],
        ]);

        return [
            'success' => true,
            'payment_id' => $payment->id,
            'status' => 'pending',
            'provider' => 'stripe',
            'public_key' => $publicKey,
            'amount' => $this->order->total,
            'currency' => StripeService::getSettings()?->currency ?? 'USD',
            'order_number' => $this->order->order_number,
            'message' => 'Ready to process Stripe payment',
        ];
    }

    /**
     * Handle PayPal Payment - delegates to PayPalService
     */
    public function processPaypalPayment(): array
    {
        $paypalService = new PayPalService($this->order);
        $result = $paypalService->processPayment();

        if (!($result['success'] ?? false)) {
            return [
                'success' => false,
                'message' => 'PayPal payment initiation failed',
                'checkout_url' => url('/paypal/checkout/' . $this->order->id),
            ];
        }

        return [
            'success' => true,
            'payment_id' => $result['payment_id'],
            'status' => 'pending',
            'provider' => 'paypal',
            'approval_url' => $result['approval_url'],
            'checkout_url' => $result['approval_url'],
            'simulated' => $result['simulated'] ?? false,
            'amount' => $this->order->total,
            'order_number' => $this->order->order_number,
        ];
    }

    /**
     * Generate gateway token
     */
    protected function generateGatewayToken(): string
    {
        // Placeholder for actual gateway integration
        return bin2hex(random_bytes(16));
    }

    /**
     * Verify bank transfer payment
     */
    public function verifyBankTransfer(string $proofFilePath, ?string $transactionRef = null): bool
    {
        $payment = $this->order->payments()
            ->where('method', 'bank_transfer')
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            throw new \Exception('No pending bank transfer found');
        }

        // Update payment with proof
        $payment->update([
            'proof_file_path' => $proofFilePath,
            'transaction_reference' => $transactionRef,
            'metadata' => array_merge($payment->metadata ?? [], [
                'verification_pending' => true,
            ]),
        ]);

        // In production, notify admin for verification
        // Then manually approve

        return true;
    }

    /**
     * Approve payment (admin)
     */
    public function approvePayment(int $paymentId): Payment
    {
        $payment = Payment::findOrFail($paymentId);

        if ($payment->order_id !== $this->order->id) {
            throw new \Exception('Payment does not belong to this order');
        }

        $payment->markAsApproved();

        // Update order status
        $this->order->update(['payment_status' => 'approved']);

        return $payment;
    }

    /**
     * Reject payment (admin)
     */
    public function rejectPayment(int $paymentId, string $reason): Payment
    {
        $payment = Payment::findOrFail($paymentId);

        if ($payment->order_id !== $this->order->id) {
            throw new \Exception('Payment does not belong to this order');
        }

        $payment->markAsFailed($reason);

        return $payment;
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(): array
    {
        $payments = $this->order->payments;

        return [
            'order_id' => $this->order->id,
            'total_amount' => $this->order->total,
            'payments' => $payments->map(fn($p) => [
                'id' => $p->id,
                'method' => $p->method,
                'status' => $p->status,
                'amount' => $p->amount,
                'created_at' => $p->created_at,
            ])->toArray(),
        ];
    }
}
