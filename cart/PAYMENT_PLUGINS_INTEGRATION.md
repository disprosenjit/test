# Payment Plugins - Integration & Next Steps

## ✅ Completed Refactoring

Your payment methods have been successfully refactored into modular plugins:

```
plugins/
├── PaymentStripe/     ✓ Credit/Debit Card payments
├── PaymentPayPal/     ✓ PayPal payments
├── PaymentBank/       ✓ Bank transfer payments
└── PluginLoader.php   ✓ Plugin management
```

## 🚀 Quick Integration Steps

### Step 1: Update Autoloader

Run this command to rebuild the Composer autoloader:

```bash
composer dump-autoload
```

### Step 2: Run Migrations (if needed)

If plugins haven't been migrated yet:

```bash
php artisan migrate
```

This will automatically load all migrations from `plugins/*/database/migrations/`.

### Step 3: Clear Application Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:cache
php artisan view:cache
```

### Step 4: Verify Plugins Are Loaded

```bash
php artisan tinker
>>> use App\PaymentMethods\PaymentMethodsManager;
>>> $manager = PaymentMethodsManager::getInstance();
>>> dd($manager->all());
```

Should show all three payment methods registered.

## 📋 Files to Review & Update

### Configuration Files

Update your `.env` file if needed:

```env
# Stripe Configuration
STRIPE_PUBLIC_KEY=your_public_key
STRIPE_SECRET_KEY=your_secret_key
STRIPE_WEBHOOK_SECRET=your_webhook_secret
STRIPE_ENVIRONMENT=test
STRIPE_CURRENCY=USD

# PayPal Configuration
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_CLIENT_SECRET=your_client_secret
PAYPAL_ENVIRONMENT=sandbox
PAYPAL_CURRENCY=USD

# Bank Transfer Configuration
BANK_ACCOUNT_HOLDER="Your Company Name"
BANK_NAME="Your Bank"
BANK_ACCOUNT_NUMBER="1234567890"
BANK_IFSC_CODE="BANKCODE"
BANK_BRANCH="Branch Name"
```

### View Files

Each plugin has placeholder views that need to be completed:

1. **Stripe Payment View**
   - Location: `plugins/PaymentStripe/resources/views/checkout/card-payment.blade.php`
   - Need to: Add Stripe.js integration and card form

2. **PayPal Checkout View**
   - Location: `plugins/PaymentPayPal/resources/views/checkout/paypal-checkout.blade.php`
   - Need to: Add PayPal buttons and approval flow

3. **Bank Transfer View**
   - Location: `plugins/PaymentBank/resources/views/checkout/bank-transfer.blade.php`
   - Need to: Display bank details and payment instructions

### Routes

All payment routes are now loaded from plugins:

```php
// Routes automatically available:
GET/POST  /card-payment/{order}           # Stripe payment
GET/POST  /paypal/checkout/{order}        # PayPal checkout
POST      /paypal/initiate/{order}        # PayPal initiate
POST      /paypal/capture/{order}         # PayPal capture
GET       /paypal/success/{order}         # PayPal success
GET       /paypal/cancel/{order}          # PayPal cancel
GET       /bank-transfer/{order}          # Bank transfer details
```

## 🗑️ Old Files to Remove

Once you've verified everything is working, remove these old files:

```bash
# Payment Methods
rm app/PaymentMethods/StripePaymentMethod.php
rm app/PaymentMethods/PayPalPaymentMethod.php
rm app/PaymentMethods/BankTransferPaymentMethod.php

# Services
rm app/Services/StripeService.php
rm app/Services/PayPalService.php

# Controllers
rm app/Http/Controllers/Frontend/CardPaymentController.php
rm app/Http/Controllers/Frontend/PayPalController.php

# Old Views (if duplicated)
# rm resources/views/checkout/card-payment.blade.php
# rm resources/views/checkout/paypal-checkout.blade.php
```

**⚠️ DO NOT REMOVE:**
- `app/PaymentMethods/PaymentMethodInterface.php` - Core interface
- `app/PaymentMethods/PaymentMethodsManager.php` - Central registry
- `app/Models/Payment.php` - Core payment model
- `app/Models/PaymentMethod.php` - Config model

## 🧪 Testing the Setup

### 1. Test Plugin Loading

```php
// In a controller or tinker
use App\PaymentMethods\PaymentMethodsManager;

$manager = PaymentMethodsManager::getInstance();

// Get specific payment method
$stripe = $manager->get('stripe');
echo $stripe->getName(); // Output: Credit/Debit Card

// Get all methods
$all = $manager->all();
dd(array_keys($all)); // Output: ['stripe', 'paypal', 'bank_transfer']

// Get enabled methods
$enabled = $manager->getEnabled();
dd($enabled);
```

### 2. Test Payment Processing

```php
use App\Models\Order;

$order = Order::first();
$stripe = PaymentMethodsManager::getInstance()->get('stripe');

if ($stripe->isEnabled()) {
    echo "Stripe is enabled";
}
```

### 3. Manual Route Testing

```bash
# Test Stripe payment route
GET /card-payment/{order_id}

# Test PayPal route
GET /paypal/checkout/{order_id}

# Test Bank Transfer route
GET /bank-transfer/{order_id}
```

## 📱 Frontend Integration

Update your checkout page to use the new plugin routes:

```blade
<!-- In resources/views/checkout/index.blade.php -->
@forelse($paymentMethods as $method)
    <div class="payment-method">
        <h4>{{ $method->getName() }}</h4>
        <i class="{{ $method->getIcon() }}"></i>
        
        @switch($method->getKey())
            @case('stripe')
                <a href="{{ route('card-payment.show', $order) }}" class="btn btn-primary">
                    Pay with Card
                </a>
                @break
            @case('paypal')
                <a href="{{ route('paypal.checkout', $order) }}" class="btn btn-primary">
                    Pay with PayPal
                </a>
                @break
            @case('bank_transfer')
                <a href="{{ route('bank-transfer.show', $order) }}" class="btn btn-primary">
                    Bank Transfer
                </a>
                @break
        @endswitch
    </div>
@empty
    <p>No payment methods available</p>
@endforelse
```

## 🔧 Enabling/Disabling Plugins

To enable or disable specific payment plugins, modify `bootstrap/providers.php`:

```php
use Plugins\PluginLoader;

return array_merge([
    AppServiceProvider::class,
], array_filter([
    // Core plugins - always enabled
    \Plugins\PaymentStripe\PaymentStripeServiceProvider::class,
    
    // Optional plugins
    env('ENABLE_PAYPAL') ? \Plugins\PaymentPayPal\PaymentPayPalServiceProvider::class : null,
    env('ENABLE_BANK_TRANSFER') ? \Plugins\PaymentBank\PaymentBankServiceProvider::class : null,
]));
```

Then in `.env`:
```env
ENABLE_PAYPAL=true
ENABLE_BANK_TRANSFER=true
```

## 📚 Documentation

See the comprehensive guide:
- **Main Documentation:** `PAYMENT_PLUGINS_GUIDE.md`

## 🆘 Troubleshooting

### Routes Not Found

```bash
php artisan route:clear
php artisan route:cache
php artisan config:clear
```

### Plugin Classes Not Found

```bash
composer dump-autoload
php artisan config:clear
```

### Migration Errors

```bash
# Check which migrations need running
php artisan migrate:status

# Run migrations
php artisan migrate

# Rollback if needed
php artisan migrate:rollback
```

### Views Not Loading

Check that plugin views are being loaded with the correct namespace:

```blade
{{-- Should use plugin namespace --}}
@include('payment-stripe::checkout.card-payment')
@include('payment-paypal::checkout.paypal-checkout')
@include('payment-bank::checkout.bank-transfer')
```

## 💡 Benefits of This Architecture

1. **Modular** - Each payment method is isolated
2. **Scalable** - Add new payment methods without affecting core code
3. **Maintainable** - Changes are localized
4. **Testable** - Each plugin can be tested independently
5. **Deployable** - Enable/disable plugins per environment
6. **Clean** - Main application code stays focused

## 📝 Creating Additional Payment Plugins

To add a new payment method (e.g., Razorpay):

1. Create: `plugins/PaymentRazorpay/`
2. Copy the structure from an existing plugin (e.g., PaymentStripe)
3. Update class names and namespaces
4. Add to `plugins/PluginLoader.php`
5. Run `composer dump-autoload`

See `PAYMENT_PLUGINS_GUIDE.md` for detailed instructions.

## ✨ Next Actions

- [ ] Review and complete payment view files
- [ ] Test each payment method integration
- [ ] Update frontend checkout flow
- [ ] Remove old payment method files
- [ ] Update admin payment management views
- [ ] Test with actual payment processors
- [ ] Deploy to staging/production

---

**Questions?** Refer to `PAYMENT_PLUGINS_GUIDE.md` for comprehensive documentation.
