<?php

namespace App\Services;

use App\Models\Payment\Payment;
use App\Models\Payment\StripeCustomer;
use App\Models\Payment\StripeCard;
use App\Models\Payment\StripeCharge;
use App\Models\User\User;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use Exception;

class StripePaymentService
{
    protected StripeClient $stripe;
    protected string $currency;

    public function __construct()
    {
        $apiKey = config('services.stripe.secret');
        if (!$apiKey) {
            throw new Exception('Stripe secret key not configured');
        }

        $this->stripe = new StripeClient(['api_key' => $apiKey]);
        $this->currency = strtolower(config('services.stripe.currency', 'usd'));
    }

    /**
     * Get or create a Stripe customer for the user
     */
    public function getOrCreateCustomer(User $user): StripeCustomer
    {
        $customer = StripeCustomer::where('user_id', $user->id)->first();

        if ($customer) {
            return $customer;
        }

        // Create customer in Stripe
        $stripeCustomer = $this->stripe->customers->create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        // Save to database
        return StripeCustomer::create([
            'user_id' => $user->id,
            'stripe_customer_id' => $stripeCustomer->id,
            'email' => $user->email,
            'name' => $user->name,
            'synced_at' => now(),
        ]);
    }

    /**
     * Create a payment method (card) and save it to Stripe customer
     */
    public function createPaymentMethod(StripeCustomer $customer, array $cardData): StripeCard
    {
        // Create payment method in Stripe
        $paymentMethod = $this->stripe->paymentMethods->create([
            'type' => 'card',
            'card' => [
                'number' => $cardData['number'],
                'exp_month' => $cardData['exp_month'],
                'exp_year' => $cardData['exp_year'],
                'cvc' => $cardData['cvc'],
            ],
            'billing_details' => [
                'name' => $cardData['cardholder_name'],
            ],
        ]);

        // Attach to customer
        $this->stripe->paymentMethods->attach($paymentMethod->id, [
            'customer' => $customer->stripe_customer_id,
        ]);

        // Save card to database
        $card = StripeCard::create([
            'stripe_customer_id' => $customer->id,
            'payment_method_id' => $paymentMethod->id,
            'brand' => ucfirst($paymentMethod->card->brand),
            'last_four' => $paymentMethod->card->last4,
            'exp_month' => $paymentMethod->card->exp_month,
            'exp_year' => $paymentMethod->card->exp_year,
            'cardholder_name' => $cardData['cardholder_name'],
            'is_default' => true,
        ]);

        return $card;
    }

    /**
     * Charge the card for an order
     */
    public function charge(Payment $payment, StripeCard $card, ?string $stripeCustomerId = null): StripeCharge
    {
        $stripeCustomer = StripeCustomer::find($card->stripe_customer_id);

        try {
            // Create payment intent
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => (int)($payment->amount * 100), // Convert to cents
                'currency' => $this->currency,
                'customer' => $stripeCustomer->stripe_customer_id,
                'payment_method' => $card->payment_method_id,
                'off_session' => true,
                'confirm' => true,
                'metadata' => [
                    'order_id' => $payment->order_id,
                    'payment_id' => $payment->id,
                    'user_id' => $stripeCustomer->user_id,
                ],
                'return_url' => route('checkout.stripe-confirmation'),
            ]);

            // Save charge record
            $charge = StripeCharge::create([
                'payment_id' => $payment->id,
                'stripe_customer_id' => $stripeCustomer->id,
                'stripe_card_id' => $card->id,
                'stripe_charge_id' => $paymentIntent->charges->data[0]->id ?? $paymentIntent->id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'amount' => $payment->amount,
                'currency' => $this->currency,
                'status' => $paymentIntent->status === 'succeeded' ? 'succeeded' : 'pending',
                'metadata' => $paymentIntent->metadata->toArray(),
                'receipt_data' => [
                    'payment_intent_status' => $paymentIntent->status,
                    'payment_method_type' => $paymentIntent->payment_method_types[0] ?? null,
                ],
            ]);

            // Update card last used
            $card->update(['last_used_at' => now()]);

            // If payment succeeded, update payment record
            if ($paymentIntent->status === 'succeeded') {
                $payment->markAsApproved($paymentIntent->id);
                $charge->update([
                    'status' => 'succeeded',
                    'charged_at' => now(),
                ]);
            }

            return $charge;
        } catch (ApiErrorException $e) {
            // Save failed charge record
            $charge = StripeCharge::create([
                'payment_id' => $payment->id,
                'stripe_customer_id' => $stripeCustomer->id,
                'stripe_card_id' => $card->id,
                'stripe_charge_id' => $e->getStripeCode() ?? 'unknown',
                'amount' => $payment->amount,
                'currency' => $this->currency,
                'status' => 'failed',
                'failure_message' => $e->getMessage(),
                'failure_code' => $e->getStripeCode(),
            ]);

            // Update payment status
            $payment->markAsFailed($e->getMessage());

            throw $e;
        }
    }

    /**
     * Get saved cards for a customer
     */
    public function getSavedCards(StripeCustomer $customer)
    {
        return $customer->cards()->where('is_default', false)->orWhere('is_default', true)->get();
    }

    /**
     * Set default card
     */
    public function setDefaultCard(StripeCustomer $customer, StripeCard $card): void
    {
        // Unset previous default
        $customer->cards()->update(['is_default' => false]);

        // Set new default
        $card->update(['is_default' => true]);

        // Update in Stripe
        $this->stripe->customers->update($customer->stripe_customer_id, [
            'invoice_settings' => [
                'default_payment_method' => $card->payment_method_id,
            ],
        ]);
    }

    /**
     * Delete a card
     */
    public function deleteCard(StripeCard $card): void
    {
        $stripeCustomer = $card->stripeCustomer;

        // Detach from Stripe
        $this->stripe->paymentMethods->detach($card->payment_method_id);

        // Delete from database
        $card->delete();
    }

    /**
     * Get payment intent status
     */
    public function getPaymentIntentStatus(string $intentId)
    {
        return $this->stripe->paymentIntents->retrieve($intentId);
    }
}
