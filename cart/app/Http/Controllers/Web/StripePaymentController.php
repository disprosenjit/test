<?php

namespace App\Http\Controllers\Web;

use App\Models\Commerce\Order;
use App\Models\Payment\Payment;
use App\Models\Payment\StripeCustomer;
use App\Models\Payment\StripeCard;
use App\Services\StripePaymentService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class StripePaymentController extends Controller
{
    protected StripePaymentService $stripeService;

    public function __construct(StripePaymentService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Show card entry form during checkout
     */
    public function showCardForm(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = Order::findOrFail($orderId);
        $payment = $order->payments()->where('method', 'stripe')->first();

        if (!$payment) {
            return redirect('/checkout')->with('error', 'Payment record not found');
        }

        // Get or create Stripe customer
        $stripeCustomer = $this->stripeService->getOrCreateCustomer(auth()->user());

        // Get saved cards
        $savedCards = $this->stripeService->getSavedCards($stripeCustomer);

        // Get Stripe publishable key
        $publishableKey = config('services.stripe.key');

        return view('frontend.checkout.stripe-card-form', compact(
            'order',
            'payment',
            'stripeCustomer',
            'savedCards',
            'publishableKey'
        ));
    }

    /**
     * Process card payment
     */
    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'card_choice' => 'required|in:new,saved',
            'saved_card_id' => 'nullable|integer|exists:stripe_cards,id',
            'card_number' => 'required_if:card_choice,new|regex:/^\d{13,19}$/',
            'exp_month' => 'required_if:card_choice,new|digits:2',
            'exp_year' => 'required_if:card_choice,new|digits:4',
            'cvc' => 'required_if:card_choice,new|regex:/^\d{3,4}$/',
            'cardholder_name' => 'required_if:card_choice,new|string|max:255',
            'save_card' => 'boolean',
        ]);

        try {
            $order = Order::findOrFail($validated['order_id']);
            $payment = $order->payments()->where('method', 'stripe')->firstOrFail();
            $user = auth()->user();

            // Get or create Stripe customer
            $stripeCustomer = $this->stripeService->getOrCreateCustomer($user);

            // Get or create card
            if ($validated['card_choice'] === 'saved') {
                $card = StripeCard::findOrFail($validated['saved_card_id']);

                // Verify card belongs to customer
                if ($card->stripe_customer_id !== $stripeCustomer->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid card selected',
                    ], 403);
                }
            } else {
                // Create new card
                $card = $this->stripeService->createPaymentMethod($stripeCustomer, [
                    'number' => str_replace(' ', '', $validated['card_number']),
                    'exp_month' => (int)$validated['exp_month'],
                    'exp_year' => (int)$validated['exp_year'],
                    'cvc' => $validated['cvc'],
                    'cardholder_name' => $validated['cardholder_name'],
                ]);
            }

            // Process the charge
            $charge = $this->stripeService->charge($payment, $card);

            // If charge succeeded
            if ($charge->status === 'succeeded') {
                $order->update([
                    'payment_status' => 'approved',
                    'status' => 'confirmed',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment processed successfully',
                    'order_id' => $order->id,
                    'redirect' => route('orders.show', $order->id),
                ]);
            }

            // If charge is pending (requires confirmation)
            return response()->json([
                'success' => true,
                'pending' => true,
                'message' => 'Payment requires confirmation',
                'client_secret' => $charge->stripe_payment_intent_id,
                'redirect' => route('checkout.stripe-confirmation', [
                    'payment_intent' => $charge->stripe_payment_intent_id,
                ]),
            ]);
        } catch (Exception $e) {
            \Log::error('Stripe payment error', [
                'error' => $e->getMessage(),
                'order_id' => $validated['order_id'] ?? null,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Handle payment confirmation (3D Secure, etc.)
     */
    public function confirmPayment(Request $request)
    {
        $paymentIntentId = $request->query('payment_intent');

        try {
            $paymentIntent = $this->stripeService->getPaymentIntentStatus($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                $charge = \App\Models\Payment\StripeCharge::where(
                    'stripe_payment_intent_id',
                    $paymentIntentId
                )->firstOrFail();

                $payment = $charge->payment;
                $order = $payment->order;

                // Update records
                $charge->update([
                    'status' => 'succeeded',
                    'charged_at' => now(),
                ]);

                $payment->markAsApproved($paymentIntentId);
                $order->update([
                    'payment_status' => 'approved',
                    'status' => 'confirmed',
                ]);

                return view('frontend.checkout.stripe-success', compact('order', 'charge'));
            }

            return view('frontend.checkout.stripe-failed', [
                'message' => 'Payment was not completed. Status: ' . $paymentIntent->status,
            ]);
        } catch (Exception $e) {
            return view('frontend.checkout.stripe-failed', [
                'message' => 'Error retrieving payment status: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete saved card
     */
    public function deleteCard(Request $request)
    {
        $cardId = $request->route('card');
        $card = StripeCard::findOrFail($cardId);

        // Verify card belongs to authenticated user
        if ($card->stripeCustomer->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $this->stripeService->deleteCard($card);

            return response()->json([
                'success' => true,
                'message' => 'Card deleted successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete card: ' . $e->getMessage(),
            ], 400);
        }
    }
}
