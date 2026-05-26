<?php

namespace Plugins\PaymentStripe\Services;

use App\Models\Commerce\Order;
use App\Models\Payment\Payment;
use Plugins\PaymentStripe\Models\StripeRefund;
use Plugins\PaymentStripe\Models\StripeSetting;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Refund;
use Exception;
use Illuminate\Support\Facades\Log;

class StripeService
{
    protected Order $order;
    protected ?StripeSetting $settings;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->settings = StripeSetting::getActive();

        if ($this->settings) {
            Stripe::setApiKey($this->settings->getDecryptedSecretKey());
        }
    }

    /**
     * Check if Stripe is configured
     */
    public static function isConfigured(): bool
    {
        return StripeSetting::isConfigured();
    }

    /**
     * Get active Stripe settings
     */
    public static function getSettings(): ?StripeSetting
    {
        return StripeSetting::getActive();
    }

    /**
     * Process Stripe payment
     */
    public function processPayment(string $tokenId, ?string $description = null): array
    {
        try {
            if (!$this->settings) {
                throw new Exception('Stripe is not configured');
            }

            // Create charge
            $charge = Charge::create([
                'amount' => intval($this->order->total * 100), // Amount in cents
                'currency' => strtolower($this->settings->currency ?? 'usd'),
                'source' => $tokenId,
                'description' => $description ?? "Order #{$this->order->order_number}",
                'metadata' => [
                    'order_id' => $this->order->id,
                    'customer_name' => $this->order->user?->name ?? 'Guest',
                    'customer_email' => $this->order->user?->email ?? '',
                ],
                'receipt_email' => $this->order->user?->email,
            ]);

            // Create payment record
            $payment = Payment::create([
                'order_id' => $this->order->id,
                'method' => 'stripe',
                'status' => $charge->status === 'succeeded' ? 'approved' : 'pending',
                'amount' => $this->order->total,
                'transaction_reference' => $charge->id,
                'gateway_response' => json_encode([
                    'charge_id' => $charge->id,
                    'amount' => $charge->amount,
                    'currency' => $charge->currency,
                    'status' => $charge->status,
                    'receipt_url' => $charge->receipt_url,
                ]),
                'metadata' => [
                    'stripe_charge_id' => $charge->id,
                    'receipt_url' => $charge->receipt_url,
                ],
            ]);

            if ($charge->status === 'succeeded') {
                $this->order->update(['payment_status' => 'approved']);
            }

            return [
                'success' => true,
                'payment_id' => $payment->id,
                'charge_id' => $charge->id,
                'status' => $charge->status,
                'receipt_url' => $charge->receipt_url,
                'message' => 'Payment processed successfully',
            ];

        } catch (Exception $e) {
            Log::error('Stripe payment error', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process full refund
     */
    public function refund(Payment $payment, ?string $reason = null): array
    {
        try {
            if (!$this->settings) {
                throw new Exception('Stripe is not configured');
            }

            if ($payment->method !== 'stripe') {
                throw new Exception('This payment is not a Stripe payment');
            }

            $chargeId = $payment->transaction_reference;

            // Create refund in Stripe
            $refund = Refund::create([
                'charge' => $chargeId,
                'metadata' => [
                    'order_id' => $payment->order_id,
                    'reason' => $reason ?? 'Full refund requested',
                ],
            ]);

            // Create refund record
            $stripeRefund = StripeRefund::create([
                'payment_id' => $payment->id,
                'stripe_charge_id' => $chargeId,
                'stripe_refund_id' => $refund->id,
                'amount' => $payment->amount,
                'type' => 'full',
                'status' => $refund->status === 'succeeded' ? 'completed' : 'pending',
                'reason' => $reason,
                'stripe_response' => json_encode([
                    'refund_id' => $refund->id,
                    'amount' => $refund->amount,
                    'status' => $refund->status,
                    'created' => $refund->created,
                ]),
                'processed_by' => auth()->id(),
                'processed_at' => $refund->status === 'succeeded' ? now() : null,
            ]);

            // Update payment status
            if ($refund->status === 'succeeded') {
                $payment->update(['status' => 'refunded']);
                $this->order->update(['payment_status' => 'refunded']);
            }

            return [
                'success' => true,
                'refund_id' => $stripeRefund->id,
                'stripe_refund_id' => $refund->id,
                'status' => $refund->status,
                'amount' => $payment->amount,
                'message' => 'Refund processed successfully',
            ];

        } catch (Exception $e) {
            Log::error('Stripe refund error', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Refund processing failed: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process partial refund
     */
    public function partialRefund(Payment $payment, float $amount, ?string $reason = null): array
    {
        try {
            if (!$this->settings) {
                throw new Exception('Stripe is not configured');
            }

            if ($payment->method !== 'stripe') {
                throw new Exception('This payment is not a Stripe payment');
            }

            if ($amount > $payment->amount) {
                throw new Exception('Refund amount cannot exceed payment amount');
            }

            $chargeId = $payment->transaction_reference;

            // Create partial refund in Stripe
            $refund = Refund::create([
                'charge' => $chargeId,
                'amount' => intval($amount * 100),
                'metadata' => [
                    'order_id' => $payment->order_id,
                    'reason' => $reason ?? 'Partial refund requested',
                ],
            ]);

            // Create refund record
            $stripeRefund = StripeRefund::create([
                'payment_id' => $payment->id,
                'stripe_charge_id' => $chargeId,
                'stripe_refund_id' => $refund->id,
                'amount' => $amount,
                'type' => 'partial',
                'status' => $refund->status === 'succeeded' ? 'completed' : 'pending',
                'reason' => $reason,
                'stripe_response' => json_encode([
                    'refund_id' => $refund->id,
                    'amount' => $refund->amount,
                    'status' => $refund->status,
                    'created' => $refund->created,
                ]),
                'processed_by' => auth()->id(),
                'processed_at' => $refund->status === 'succeeded' ? now() : null,
            ]);

            return [
                'success' => true,
                'refund_id' => $stripeRefund->id,
                'stripe_refund_id' => $refund->id,
                'status' => $refund->status,
                'amount' => $amount,
                'message' => 'Partial refund processed successfully',
            ];

        } catch (Exception $e) {
            Log::error('Stripe partial refund error', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Partial refund processing failed: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create payment intent for Stripe Elements
     */
    public function createPaymentIntent(): array
    {
        try {
            if (!$this->settings) {
                throw new Exception('Stripe is not configured');
            }

            $intent = \Stripe\PaymentIntent::create([
                'amount' => intval($this->order->total * 100),
                'currency' => strtolower($this->settings->currency ?? 'usd'),
                'metadata' => [
                    'order_id' => $this->order->id,
                    'order_number' => $this->order->order_number,
                ],
            ]);

            return [
                'success' => true,
                'client_secret' => $intent->client_secret,
                'intent_id' => $intent->id,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get public key for frontend
     */
    public function getPublicKey(): ?string
    {
        return $this->settings?->getDecryptedPublishableKey();
    }

    /**
     * Get charge status
     */
    public function getChargeStatus(string $chargeId): ?string
    {
        try {
            if (!$this->settings) {
                return null;
            }

            $charge = Charge::retrieve($chargeId);
            return $charge->status;
        } catch (Exception $e) {
            Log::error('Stripe charge status error', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
