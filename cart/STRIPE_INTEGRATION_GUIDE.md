# Stripe Card Payment Integration Guide

## Overview
This implementation provides a complete Stripe card payment flow for the checkout page, including:
- Secure card entry form
- Stripe customer creation
- Card storage and tokenization
- Card charging
- Payment confirmation and success/failure handling
- Saved cards for future purchases

## Database Tables

### 1. `stripe_customers`
Stores Stripe customer information linked to users.

**Fields:**
- `id` - Primary key
- `user_id` - Foreign key to users (unique)
- `stripe_customer_id` - Stripe's customer ID (unique)
- `email` - Customer email
- `name` - Customer name
- `metadata` - JSON metadata
- `synced_at` - Last sync timestamp
- `timestamps` - Created/updated at

### 2. `stripe_cards`
Stores saved payment methods/cards for customers.

**Fields:**
- `id` - Primary key
- `stripe_customer_id` - Foreign key to stripe_customers
- `payment_method_id` - Stripe's payment method ID (unique)
- `brand` - Card brand (Visa, Mastercard, etc.)
- `last_four` - Last 4 digits
- `exp_month` - Expiration month
- `exp_year` - Expiration year
- `cardholder_name` - Name on card
- `is_default` - Boolean for default card
- `metadata` - JSON metadata
- `last_used_at` - Last usage timestamp
- `timestamps` - Created/updated at

### 3. `stripe_charges`
Stores payment charge records for audit and tracking.

**Fields:**
- `id` - Primary key
- `payment_id` - Foreign key to payments
- `stripe_customer_id` - Foreign key to stripe_customers
- `stripe_card_id` - Foreign key to stripe_cards (nullable)
- `stripe_charge_id` - Stripe's charge ID (unique)
- `stripe_payment_intent_id` - Stripe's payment intent ID (unique)
- `amount` - Charged amount (decimal)
- `currency` - Currency code
- `status` - Status (pending, succeeded, failed, cancelled)
- `failure_message` - Failure reason if failed
- `failure_code` - Stripe error code if failed
- `metadata` - JSON metadata
- `receipt_data` - JSON receipt data
- `charged_at` - When charge was processed
- `timestamps` - Created/updated at

## Installation Steps

### 1. Run Migrations
```bash
php artisan migrate
```

This will create the three new tables.

### 2. Configure Environment Variables
Add to your `.env` file:

```env
STRIPE_PUBLISHABLE_KEY=pk_test_your_publishable_key
STRIPE_SECRET_KEY=sk_test_your_secret_key
STRIPE_CURRENCY=INR
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

For production, use `pk_live_` and `sk_live_` keys.

### 3. Test Stripe Credentials
In test mode, use this card for testing:
- **Card Number:** 4242 4242 4242 4242
- **Expiry:** Any future date (e.g., 12/25)
- **CVC:** Any 3 digits (e.g., 123)

For other test cards, see [Stripe Testing Documentation](https://stripe.com/docs/testing)

## Models

### `StripeCustomer`
- Relationship: `hasMany('cards')`, `hasMany('charges')`, `belongsTo('user')`
- Methods: `defaultCard()` - Get default card

### `StripeCard`
- Relationship: `belongsTo('stripeCustomer')`, `hasMany('charges')`
- Methods:
  - `getDisplayName()` - Returns "Brand ending in XXXX"
  - `getExpiryDisplay()` - Returns "MM/YY" format
  - `isExpired()` - Check if card is expired

### `StripeCharge`
- Relationship: `belongsTo('payment')`, `belongsTo('stripeCustomer')`, `belongsTo('stripeCard')`
- Scopes: `succeeded()`, `failed()`, `pending()`

## Services

### `StripePaymentService`
Main service for handling Stripe operations:

**Methods:**
- `getOrCreateCustomer(User $user): StripeCustomer`
- `createPaymentMethod(StripeCustomer $customer, array $cardData): StripeCard`
- `charge(Payment $payment, StripeCard $card): StripeCharge`
- `getSavedCards(StripeCustomer $customer): Collection`
- `setDefaultCard(StripeCustomer $customer, StripeCard $card): void`
- `deleteCard(StripeCard $card): void`
- `getPaymentIntentStatus(string $intentId): PaymentIntent`

## Controller

### `StripePaymentController`
Handles the payment flow:

**Routes:**
- `GET /checkout/stripe/card-form` - Show card entry form
- `POST /checkout/stripe/process` - Process card payment
- `GET /checkout/stripe/confirmation` - Confirm payment (for 3D Secure)
- `DELETE /checkout/stripe/cards/{card}` - Delete saved card

## Views

### `frontend.checkout.stripe-card-form`
Main card entry form with features:
- New card entry section
- Saved cards selection (if available)
- Card brand detection
- Card validation
- Secure payment button
- Test card information

### `frontend.checkout.stripe-success`
Success page after payment showing:
- Order confirmation
- Transaction details
- Next steps
- Options to view order, download invoice, or continue shopping

### `frontend.checkout.stripe-failed`
Failure page with:
- Error explanation
- Common failure reasons
- Recovery options
- Support contact information

## Checkout Page Integration

The checkout page (`frontend.checkout.index`) now:
1. Detects when Stripe payment method is selected
2. Creates an order first
3. Redirects user to Stripe card form
4. After successful payment, redirects to order confirmation

**Flow:**
```
User selects Stripe → Order created → Card form → Process payment → Success/Failure
```

## Routes

Routes are defined in `routes/web.php`:

```php
// Stripe Payment Routes
Route::middleware('auth')->prefix('checkout/stripe')->name('checkout.stripe')->group(function () {
    Route::get('/card-form', [StripePaymentController::class, 'showCardForm'])->name('-card-form');
    Route::post('/process', [StripePaymentController::class, 'processPayment'])->name('-process');
    Route::get('/confirmation', [StripePaymentController::class, 'confirmPayment'])->name('-confirmation');
    Route::delete('/cards/{card}', [StripePaymentController::class, 'deleteCard'])->name('-delete-card');
});

// Alternative simpler names
Route::middleware('auth')->group(function () {
    Route::get('/checkout/stripe/card-form', ...)->name('checkout.stripe-card-form');
    Route::post('/checkout/stripe/process', ...)->name('checkout.stripe-process');
    Route::get('/checkout/stripe/confirmation', ...)->name('checkout.stripe-confirmation');
});
```

## Usage Flow

### 1. User Checkout
- User fills checkout form
- User selects Stripe payment method
- User clicks "Place Order"

### 2. Order Creation
- Order is created in database
- Payment record created with stripe method
- User redirected to card form with `order_id` parameter

### 3. Card Entry
- User can enter new card OR select saved card
- User can optionally save card for future use
- User reviews order total
- User clicks "Pay"

### 4. Payment Processing
- Card details sent securely to card form
- `processPayment()` validates input
- Stripe customer created if new user
- Card tokenized via Stripe Payment Methods API
- Payment intent created and confirmed
- Charge record saved to database

### 5. Confirmation
- If 3D Secure required, user redirected to confirmation page
- If immediate success, redirected to success page
- Payment status updated in order

### 6. Success/Failure Handling
- **Success:** User sees order confirmation, can download invoice
- **Failure:** User sees error page with recovery options

## Security Features

✅ **PCI Compliance:**
- Card details never stored in database
- All card data handled via Stripe's APIs
- Card details sent directly from browser to Stripe

✅ **CSRF Protection:**
- All POST requests require CSRF token
- Token validated in controller

✅ **Authentication:**
- All payment routes require user authentication
- Users can only access their own orders and cards

✅ **Payment Method Encryption:**
- Payment method IDs (tokens) stored securely
- Can be deleted from Stripe independently

✅ **Audit Trail:**
- All charges logged with full details
- All card operations tracked
- Complete payment history maintained

## Error Handling

Common errors and solutions:

| Error | Cause | Solution |
|-------|-------|----------|
| "Insufficient funds" | Card doesn't have enough balance | Use different card or add funds |
| "Expired card" | Card expiry date passed | Check expiry date and use valid card |
| "Incorrect CVV" | Wrong security code | Re-enter correct 3-digit code |
| "Card declined" | Bank declined transaction | Contact bank or use different card |
| "Stripe key not configured" | Environment variables missing | Set STRIPE_SECRET_KEY in .env |

## Testing

### Test Cards
Use these test card numbers for testing different scenarios:

**Successful Charge:**
```
4242 4242 4242 4242
```

**Card Declined:**
```
4000 0000 0000 0002
```

**Requires Authentication (3D Secure):**
```
4000 2500 0000 3010
```

**Expired Card:**
```
4000 0000 0000 0069
```

For comprehensive test card list, see [Stripe Test Cards](https://stripe.com/docs/testing#cards)

### Unit Testing
```php
// Example test
public function testStripePaymentFlow()
{
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create();
    $payment = Payment::factory()->for($order)->create(['method' => 'stripe']);
    
    $this->actingAs($user)
        ->post('/checkout/stripe/process', [
            'order_id' => $order->id,
            'card_choice' => 'new',
            'card_number' => '4242424242424242',
            'exp_month' => '12',
            'exp_year' => '2025',
            'cvc' => '123',
            'cardholder_name' => 'John Doe',
            'save_card' => false,
        ])
        ->assertSuccessful();
}
```

## Webhooks (Optional)

For production, implement Stripe webhooks to handle:
- `payment_intent.succeeded`
- `payment_intent.payment_failed`
- `customer.deleted`
- `payment_method.detached`

Reference: [Stripe Webhooks Documentation](https://stripe.com/docs/webhooks)

## Best Practices

1. **Always use HTTPS** - Card data requires encrypted connection
2. **Store publishable key safely** - Can be exposed in frontend
3. **Never store secret key** - Keep in environment only
4. **Use idempotency keys** - Prevent duplicate charges from retries
5. **Implement webhook verification** - Validate webhook signatures
6. **Log all transactions** - Maintain audit trail
7. **Test thoroughly** - Use Stripe's test mode extensively
8. **Handle errors gracefully** - Show user-friendly messages

## Troubleshooting

### Cards not saving
- Check if `stripe_customers` table has user record
- Verify Stripe API key is correct
- Check database permissions

### Payment failing silently
- Check Laravel logs in `storage/logs/`
- Verify order was created
- Check Stripe dashboard for failed charges

### Redirect loop
- Verify order ID passed correctly
- Check database for order record
- Ensure authentication middleware working

## Support

For Stripe-specific issues, see:
- [Stripe API Documentation](https://stripe.com/docs/api)
- [Stripe Testing Guide](https://stripe.com/docs/testing)
- [Stripe Error Codes](https://stripe.com/docs/error-codes)

## Next Steps

1. Deploy migrations to production
2. Set Stripe API keys in environment
3. Test payment flow with test cards
4. Set up Stripe webhooks (optional)
5. Enable in admin panel
6. Monitor transactions in Stripe dashboard
