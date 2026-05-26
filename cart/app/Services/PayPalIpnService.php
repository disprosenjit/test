<?php

namespace App\Services;

use App\Models\Commerce\Order;
use App\Models\Payment\Payment;
use Plugins\PaymentPayPal\Models\PaypalIpnLog;
use Plugins\PaymentPayPal\Models\PaypalSetting;
use Illuminate\Support\Facades\Log;
use Exception;

class PayPalIpnService
{
    protected PaypalSetting $settings;
    protected array $postData = [];
    protected PaypalIpnLog $ipnLog;

    const SANDBOX_URL = 'https://api-m.sandbox.paypal.com';
    const LIVE_URL    = 'https://api-m.paypal.com';

    public function __construct()
    {
        $settings = PaypalSetting::getActive();
        if (!$settings) {
            throw new Exception('PayPal not configured');
        }
        $this->settings = $settings;
    }

    /**
     * Process incoming IPN message
     */
    public function processIpn(array $postData): bool
    {
        try {
            $this->postData = $postData;

            // Create IPN log entry
            $this->ipnLog = PaypalIpnLog::create([
                'txn_id'           => $postData['txn_id'] ?? null,
                'txn_type'         => $postData['txn_type'] ?? null,
                'payer_email'      => $postData['payer_email'] ?? null,
                'receiver_email'   => $postData['receiver_email'] ?? null,
                'mc_gross'         => $postData['mc_gross'] ?? null,
                'mc_currency'      => $postData['mc_currency'] ?? 'USD',
                'payment_status'   => $postData['payment_status'] ?? null,
                'raw_post_data'    => http_build_query($postData),
                'parsed_data'      => $postData,
                'verification_status' => 'pending',
            ]);

            // Check for duplicate
            if ($this->isDuplicate($postData)) {
                $this->ipnLog->markAsDuplicate();
                Log::warning('PayPal IPN duplicate detected', ['txn_id' => $postData['txn_id']]);
                return true;
            }

            // Verify IPN signature
            if (!$this->verifyIpnSignature()) {
                $this->ipnLog->markAsInvalid();
                Log::error('PayPal IPN verification failed', ['txn_id' => $postData['txn_id']]);
                return false;
            }

            $this->ipnLog->markAsVerified();

            // Link to payment/order if exists
            $this->linkToPaymentAndOrder();

            // Process the IPN
            $this->handleIpnEvent($postData);

            $this->ipnLog->markAsProcessed();

            return true;
        } catch (Exception $e) {
            Log::error('PayPal IPN processing error', [
                'error' => $e->getMessage(),
                'txn_id' => $postData['txn_id'] ?? null,
            ]);

            if ($this->ipnLog) {
                $this->ipnLog->markAsProcessed($e->getMessage());
            }

            return false;
        }
    }

    /**
     * Verify IPN authenticity with PayPal
     */
    protected function verifyIpnSignature(): bool
    {
        try {
            // Build verification message
            $verifyMsg = 'cmd=_notify-validate';

            foreach ($this->postData as $key => $value) {
                $verifyMsg .= '&' . urlencode($key) . '=' . urlencode($value);
            }

            // Send verification request to PayPal
            $baseUrl = $this->settings->environment === 'live' ? self::LIVE_URL : self::SANDBOX_URL;
            $verifyUrl = $baseUrl . '/cgi-bin/webscr';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $verifyUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $verifyMsg);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'User-Agent: PHP-IPN-Verification/1.0'
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                Log::error('PayPal IPN curl error', ['error' => $error]);
                return false;
            }

            if ($response === 'VERIFIED') {
                return true;
            }

            if ($response === 'INVALID') {
                Log::warning('PayPal IPN invalid signature');
                return false;
            }

            Log::warning('PayPal IPN unexpected response', ['response' => $response]);
            return false;
        } catch (Exception $e) {
            Log::error('PayPal IPN verification exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Check if IPN is a duplicate
     */
    protected function isDuplicate(array $postData): bool
    {
        if (!isset($postData['txn_id'])) {
            return false;
        }

        $existing = PaypalIpnLog::where('txn_id', $postData['txn_id'])
            ->where('verification_status', 'verified')
            ->first();

        return (bool)$existing;
    }

    /**
     * Link IPN log to payment and order
     */
    protected function linkToPaymentAndOrder(): void
    {
        // Try to find payment by PayPal transaction reference
        if ($this->postData['txn_id'] ?? null) {
            $payment = Payment::where('transaction_reference', $this->postData['txn_id'])
                ->where('method', 'paypal')
                ->first();

            if ($payment) {
                $this->ipnLog->update([
                    'payment_id' => $payment->id,
                    'order_id'   => $payment->order_id,
                ]);
            }
        }

        // Also try by custom field (if set during order)
        if ($this->postData['custom'] ?? null) {
            $order = Order::where('id', $this->postData['custom'])->first();
            if ($order) {
                $this->ipnLog->update(['order_id' => $order->id]);

                // Try to find payment for this order
                $payment = $order->payments()->where('method', 'paypal')->first();
                if ($payment) {
                    $this->ipnLog->update(['payment_id' => $payment->id]);
                }
            }
        }
    }

    /**
     * Handle specific IPN event type
     */
    protected function handleIpnEvent(array $postData): void
    {
        $txnType = $postData['txn_type'] ?? null;

        match ($txnType) {
            'web_accept' => $this->handlePaymentCompleted($postData),
            'pending' => $this->handlePaymentPending($postData),
            'failed' => $this->handlePaymentFailed($postData),
            'denied' => $this->handlePaymentDenied($postData),
            'refund' => $this->handlePaymentRefunded($postData),
            'reversed' => $this->handlePaymentReversed($postData),
            default => Log::info('Unhandled PayPal IPN type', ['txn_type' => $txnType]),
        };
    }

    /**
     * Handle payment completed event
     */
    protected function handlePaymentCompleted(array $postData): void
    {
        if ($postData['payment_status'] !== 'Completed') {
            return;
        }

        $payment = $this->ipnLog->payment;
        if (!$payment) {
            Log::warning('PayPal IPN: Payment not found for completed event', ['txn_id' => $postData['txn_id']]);
            return;
        }

        // Update payment to approved
        $payment->update([
            'status'                  => 'approved',
            'transaction_reference'   => $postData['txn_id'],
            'verified_at'             => now(),
        ]);

        // Update order status
        if ($payment->order && $payment->order->status === 'pending') {
            $payment->order->update(['status' => 'confirmed']);
        }

        Log::info('PayPal payment completed', [
            'payment_id' => $payment->id,
            'txn_id'     => $postData['txn_id'],
            'amount'     => $postData['mc_gross'],
        ]);
    }

    /**
     * Handle payment pending event
     */
    protected function handlePaymentPending(array $postData): void
    {
        $payment = $this->ipnLog->payment;
        if (!$payment) {
            return;
        }

        $payment->update([
            'status' => 'pending',
            'metadata' => array_merge(
                $payment->metadata ?? [],
                ['pending_reason' => $postData['pending_reason'] ?? null]
            ),
        ]);

        Log::info('PayPal payment pending', [
            'payment_id' => $payment->id,
            'reason' => $postData['pending_reason'] ?? null,
        ]);
    }

    /**
     * Handle payment failed event
     */
    protected function handlePaymentFailed(array $postData): void
    {
        $payment = $this->ipnLog->payment;
        if (!$payment) {
            return;
        }

        $payment->update([
            'status' => 'failed',
            'metadata' => array_merge(
                $payment->metadata ?? [],
                ['failure_reason' => $postData['reason_code'] ?? null]
            ),
        ]);

        Log::warning('PayPal payment failed', [
            'payment_id' => $payment->id,
            'reason' => $postData['reason_code'] ?? null,
        ]);
    }

    /**
     * Handle payment denied event
     */
    protected function handlePaymentDenied(array $postData): void
    {
        $payment = $this->ipnLog->payment;
        if (!$payment) {
            return;
        }

        $payment->update(['status' => 'rejected']);

        if ($payment->order) {
            $payment->order->update(['status' => 'cancelled']);
        }

        Log::warning('PayPal payment denied', [
            'payment_id' => $payment->id,
            'reason' => $postData['reason_code'] ?? null,
        ]);
    }

    /**
     * Handle payment refunded event
     */
    protected function handlePaymentRefunded(array $postData): void
    {
        $payment = $this->ipnLog->payment;
        if (!$payment) {
            return;
        }

        $refundAmount = abs($postData['mc_gross'] ?? 0);

        // Check if full or partial refund
        $isFullRefund = $refundAmount >= $payment->amount;

        $payment->update([
            'status' => $isFullRefund ? 'refunded' : 'partially_refunded',
            'metadata' => array_merge(
                $payment->metadata ?? [],
                [
                    'refund_txn_id' => $postData['txn_id'],
                    'refund_amount' => $refundAmount,
                    'parent_txn_id' => $postData['parent_txn_id'] ?? null,
                ]
            ),
        ]);

        Log::info('PayPal payment refunded', [
            'payment_id' => $payment->id,
            'amount' => $refundAmount,
            'full' => $isFullRefund,
        ]);
    }

    /**
     * Handle payment reversal event
     */
    protected function handlePaymentReversed(array $postData): void
    {
        $payment = $this->ipnLog->payment;
        if (!$payment) {
            return;
        }

        $payment->update([
            'status' => 'reversed',
            'metadata' => array_merge(
                $payment->metadata ?? [],
                ['reversal_reason' => $postData['reason_code'] ?? null]
            ),
        ]);

        Log::warning('PayPal payment reversed', [
            'payment_id' => $payment->id,
            'reason' => $postData['reason_code'] ?? null,
        ]);
    }

    /**
     * Get IPN log by transaction ID
     */
    public static function getIpnByTxnId(string $txnId): ?PaypalIpnLog
    {
        return PaypalIpnLog::where('txn_id', $txnId)->first();
    }

    /**
     * Get unprocessed IPN logs
     */
    public static function getUnprocessed(int $limit = 20)
    {
        return PaypalIpnLog::unprocessed()
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Retry processing failed IPNs
     */
    public static function retryFailedIpn(PaypalIpnLog $ipnLog): bool
    {
        try {
            $service = new self();
            $postData = $ipnLog->parsed_data ?? json_decode(http_build_query([]), true);
            
            // Re-verify if needed
            if ($ipnLog->verification_status === 'pending') {
                // Mark as verified if was already processed before
                if ($ipnLog->processed) {
                    $ipnLog->markAsVerified();
                }
            }

            // Reprocess the event
            $service->handleIpnEvent($postData);
            
            $ipnLog->markAsProcessed();

            return true;
        } catch (Exception $e) {
            Log::error('PayPal IPN retry failed', [
                'ipn_log_id' => $ipnLog->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
