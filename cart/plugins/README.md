# Payment Method Plugins

This directory contains all payment method implementations as modular, self-contained plugins.

## Available Plugins

### 1. PaymentStripe
**Credit/Debit Card Payments via Stripe**

- Route Prefix: `/card-payment`
- Payment Key: `stripe`
- Features:
  - Credit/Debit card processing
  - Full and partial refunds
  - Webhook support
  - Test and Live modes
- Configuration: `PaymentStripe/config/stripe.php`
- Service: `PaymentStripe/src/Services/StripeService.php`

### 2. PaymentPayPal
**PayPal Payment Integration**

- Route Prefix: `/paypal`
- Payment Key: `paypal`
- Features:
  - PayPal checkout integration
  - IPN (Instant Payment Notification) support
  - Order capture and completion
  - Sandbox and Live modes
- Configuration: `PaymentPayPal/config/paypal.php`
- Service: `PaymentPayPal/src/Services/PayPalService.php`

### 3. PaymentBank
**Bank Transfer Payments**

- Route Prefix: `/bank-transfer`
- Payment Key: `bank_transfer`
- Features:
  - Display bank account details
  - Manual payment verification
  - Payment confirmation flow
- Configuration: `PaymentBank/config/bank.php`

## Plugin Structure

Each plugin follows this standard structure:

```
PluginName/
├── config/               Configuration files
├── database/
│   └── migrations/       Database migrations
├── resources/
│   └── views/            Blade templates
├── src/
│   ├── Controllers/      Route handlers
│   ├── Models/           Eloquent models
│   ├── PaymentMethods/   PaymentMethodInterface implementation
│   ├── Services/         Business logic
│   ├── Webhooks/         Webhook handlers (if applicable)
│   ├── PluginServiceProvider.php    Bootstrap class
│   └── routes.php        Route definitions
└── tests/ (optional)     Unit/Feature tests
```

## How to Use a Plugin

### 1. Access Payment Method
```php
use App\PaymentMethods\PaymentMethodsManager;

$manager = PaymentMethodsManager::getInstance();
$stripe = $manager->get('stripe');
echo $stripe->getName(); // "Credit/Debit Card"
```

### 2. Get All Payment Methods
```php
$allMethods = $manager->all(); // ['stripe', 'paypal', 'bank_transfer']
$enabledMethods = $manager->getEnabled(); // Only enabled methods
```

### 3. Process Payment
```php
$order = Order::find(1);
$result = $stripe->processPayment($order);

if ($result['success']) {
    // Handle success
}
```

### 4. Use Plugin Routes
```blade
<!-- Stripe payment -->
<a href="{{ route('card-payment.show', $order) }}">Pay with Card</a>

<!-- PayPal checkout -->
<a href="{{ route('paypal.checkout', $order) }}">Pay with PayPal</a>

<!-- Bank transfer -->
<a href="{{ route('bank-transfer.show', $order) }}">Bank Transfer</a>
```

### 5. Load Plugin Views
```blade
<!-- Stripe checkout view -->
@include('payment-stripe::checkout.card-payment')

<!-- PayPal checkout view -->
@include('payment-paypal::checkout.paypal-checkout')

<!-- Bank transfer view -->
@include('payment-bank::checkout.bank-transfer')
```

## Creating a New Plugin

See `PAYMENT_PLUGINS_GUIDE.md` for detailed instructions on creating new payment plugins.

### Quick Steps:
1. Create plugin directory: `mkdir -p plugins/PaymentNewMethod`
2. Create service provider extending `ServiceProvider`
3. Implement `PaymentMethodInterface`
4. Create routes file
5. Add to `PluginLoader::loadPaymentPlugins()`
6. Run `composer dump-autoload`

## Plugin Lifecycle

### 1. Boot Time
- `bootstrap/providers.php` loads `PluginLoader`
- `PluginLoader` returns array of plugin service providers
- Each service provider is registered with Laravel

### 2. Service Provider Boot
- Loads migrations from plugin directory
- Loads views with namespace
- Loads routes
- Registers payment method with `PaymentMethodsManager`

### 3. Runtime
- Payment methods accessible via `PaymentMethodsManager`
- Routes available at specified prefixes
- Views available with plugin namespace
- Models accessible from plugin namespace

### 4. Teardown (if disabled)
- Remove from `PluginLoader::loadPaymentPlugins()`
- Run `composer dump-autoload`
- Payment method no longer available

## Configuration

### Environment Variables

Each plugin uses environment variables for configuration:

```env
# Stripe Plugin
STRIPE_PUBLIC_KEY=your_public_key
STRIPE_SECRET_KEY=your_secret_key
STRIPE_WEBHOOK_SECRET=your_webhook_secret
STRIPE_ENVIRONMENT=test
STRIPE_CURRENCY=USD

# PayPal Plugin
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_CLIENT_SECRET=your_client_secret
PAYPAL_ENVIRONMENT=sandbox
PAYPAL_CURRENCY=USD

# Bank Transfer Plugin
BANK_ACCOUNT_HOLDER=Company Name
BANK_NAME=Bank Name
BANK_ACCOUNT_NUMBER=1234567890
BANK_IFSC_CODE=BANKCODE
BANK_BRANCH=Branch Name
```

### Configuration Files

Each plugin has a config file in `PluginName/config/`:
- `stripe.php` - Stripe-specific config
- `paypal.php` - PayPal-specific config
- `bank.php` - Bank transfer config

Access via `config('payment.stripe')`, etc.

## Database

### Migrations
Migrations are automatically loaded from each plugin's `database/migrations/` directory.

Run migrations:
```bash
php artisan migrate
```

Plugins add these tables:
- Stripe: `stripe_settings`, `stripe_refunds`
- PayPal: `paypal_settings`, `paypal_ipn_logs`
- Bank: (uses config, no tables)

### Models
Plugin models are in plugin namespace:
- `Plugins\PaymentStripe\Models\StripeSetting`
- `Plugins\PaymentStripe\Models\StripeRefund`
- `Plugins\PaymentPayPal\Models\PaypalSetting`
- `Plugins\PaymentPayPal\Models\PaypalIpnLog`

## Troubleshooting

### Plugin Not Loading
```bash
composer dump-autoload
php artisan config:clear
```

### Routes Not Available
```bash
php artisan route:clear
php artisan route:cache
```

### Views Not Found
- Check namespace: `payment-stripe::`, `payment-paypal::`, `payment-bank::`
- Verify plugin is loaded in `bootstrap/providers.php`

### Payment Method Not Registered
- Verify service provider `boot()` method is called
- Check `PaymentMethodsManager::getInstance()->all()`
- Ensure plugin is in `PluginLoader::loadPaymentPlugins()`

## Testing

### Test Plugin Loading
```php
php artisan tinker
use App\PaymentMethods\PaymentMethodsManager;
$manager = PaymentMethodsManager::getInstance();
dd($manager->all());
```

### Test Routes
```bash
php artisan route:list | grep -E "card-payment|paypal|bank-transfer"
```

### Test Payment Method
```php
$stripe = $manager->get('stripe');
echo $stripe->getName();
```

## Best Practices

1. **Keep plugins independent** - Don't depend on other plugins
2. **Use configuration files** - Store settings in `config/` directory
3. **Implement interfaces** - All payment methods must implement `PaymentMethodInterface`
4. **Load resources properly** - Use service provider methods for migrations, views, routes
5. **Document your plugin** - Add README to plugin directory
6. **Test thoroughly** - Create tests for plugin logic
7. **Version your plugin** - Use semantic versioning

## Security Considerations

1. **Encrypt sensitive data** - API keys, secrets stored encrypted
2. **Validate input** - All user input validated before processing
3. **Validate webhooks** - Verify webhook signatures
4. **Use HTTPS** - All payment operations over HTTPS
5. **Follow PCI compliance** - Never store full card numbers
6. **Audit logging** - Log all payment operations

## Performance Tips

1. **Cache payment methods** - Results of `getEnabled()` can be cached
2. **Lazy load services** - Create services only when needed
3. **Optimize queries** - Use eager loading in models
4. **Cache configuration** - Run `php artisan config:cache`

## Extending Plugins

### Adding Functionality to Existing Plugin

1. Create a new method in the plugin's service class
2. Update the payment method class if needed
3. Add routes to `routes.php` if needed
4. Create views in `resources/views/`
5. No need to modify core application code

### Creating a Plugin Variant

Copy an existing plugin and customize:
```bash
cp -r plugins/PaymentStripe plugins/PaymentStripeExpress
```

Then modify namespace and service provider name.

## Support & Documentation

- **Complete Guide**: `PAYMENT_PLUGINS_GUIDE.md`
- **Integration Steps**: `PAYMENT_PLUGINS_INTEGRATION.md`
- **Architecture Summary**: `PAYMENT_PLUGINS_SUMMARY.md`
- **Verification Checklist**: `PAYMENT_PLUGINS_CHECKLIST.md`

---

**For questions about specific plugins, see their individual README files in their directories.**
