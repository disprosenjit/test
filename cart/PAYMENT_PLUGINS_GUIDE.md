# Payment Method Plugins Architecture

This document describes the modularized payment method plugin system that keeps the main application code clean and organized.

## Directory Structure

```
plugins/
├── PaymentStripe/              # Stripe Credit/Debit Card Payment Plugin
│   ├── config/
│   │   └── stripe.php
│   ├── database/
│   │   └── migrations/
│   │       ├── 2026_05_01_000001_create_stripe_settings_table.php
│   │       └── 2026_05_01_000002_create_stripe_refunds_table.php
│   ├── resources/
│   │   └── views/
│   │       └── checkout/
│   │           └── card-payment.blade.php
│   ├── src/
│   │   ├── Controllers/
│   │   │   └── StripePaymentController.php
│   │   ├── Models/
│   │   │   ├── StripeSetting.php
│   │   │   └── StripeRefund.php
│   │   ├── PaymentMethods/
│   │   │   └── StripePaymentMethod.php
│   │   ├── Services/
│   │   │   └── StripeService.php
│   │   ├── routes.php
│   │   └── PaymentStripeServiceProvider.php
│
├── PaymentPayPal/              # PayPal Payment Plugin
│   ├── config/
│   │   └── paypal.php
│   ├── database/
│   │   └── migrations/
│   │       ├── 2026_05_01_000001_create_paypal_settings_table.php
│   │       └── 2026_05_01_000002_create_paypal_ipn_logs_table.php
│   ├── resources/
│   │   └── views/
│   │       └── checkout/
│   │           └── paypal-checkout.blade.php
│   ├── src/
│   │   ├── Controllers/
│   │   │   └── PayPalPaymentController.php
│   │   ├── Models/
│   │   │   ├── PaypalSetting.php
│   │   │   └── PaypalIpnLog.php
│   │   ├── PaymentMethods/
│   │   │   └── PayPalPaymentMethod.php
│   │   ├── Services/
│   │   │   └── PayPalService.php
│   │   ├── Webhooks/
│   │   │   └── PayPalIpnWebhookHandler.php
│   │   ├── routes.php
│   │   └── PaymentPayPalServiceProvider.php
│
├── PaymentBank/                # Bank Transfer Payment Plugin
│   ├── config/
│   │   └── bank.php
│   ├── resources/
│   │   └── views/
│   │       └── checkout/
│   │           └── bank-transfer.blade.php
│   ├── src/
│   │   ├── Controllers/
│   │   │   └── BankTransferPaymentController.php
│   │   ├── PaymentMethods/
│   │   │   └── BankTransferPaymentMethod.php
│   │   ├── routes.php
│   │   └── PaymentBankServiceProvider.php
│
└── PluginLoader.php            # Central plugin loading mechanism
```

## Plugin Architecture

### Core Components

Each payment plugin contains:

1. **PaymentMethod Class** - Implements `PaymentMethodInterface`
   - Located in: `src/PaymentMethods/{PaymentMethodName}.php`
   - Implements standard payment method interface
   - Registers with `PaymentMethodsManager`

2. **Service Class** - Business logic for payment processing
   - Located in: `src/Services/{ServiceName}.php`
   - Handles API communication, payment processing, refunds
   - Independent and testable

3. **Controller** - Route handlers
   - Located in: `src/Controllers/{ControllerName}.php`
   - Handles HTTP requests specific to the payment method
   - Middleware and authorization checks

4. **Models** - Database models
   - Located in: `src/Models/{ModelName}.php`
   - Settings, transaction records, webhooks
   - Relationships to core `Payment` model

5. **Service Provider** - Plugin bootstrap
   - Located in: `src/{PluginName}ServiceProvider.php`
   - Loads migrations, views, routes
   - Registers payment method with manager

6. **Routes** - Plugin-specific routes
   - Located in: `src/routes.php`
   - Payment checkout, callback, webhook routes
   - Loaded automatically by service provider

7. **Migrations** - Database structure
   - Located in: `database/migrations/`
   - Plugin-specific tables
   - Loaded by Laravel's migration system

8. **Views** - Blade templates
   - Located in: `resources/views/`
   - Payment checkout pages
   - Loaded with plugin namespace

9. **Config** - Plugin configuration
   - Located in: `config/{plugin}.php`
   - Environment variables
   - Plugin settings

## How Plugins Work

### 1. Plugin Loading

**File:** `bootstrap/providers.php`

```php
use Plugins\PluginLoader;

return array_merge([
    AppServiceProvider::class,
], PluginLoader::loadPaymentPlugins());
```

All plugins are registered in the service container during bootstrap.

### 2. Namespace Configuration

**File:** `composer.json`

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Plugins\\": "plugins/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        }
    }
}
```

Run `composer dump-autoload` after adding new plugins.

### 3. Service Provider Registration

Each plugin has a service provider that:
- Loads migrations
- Loads and publishes views
- Loads routes with namespace
- Registers payment method with `PaymentMethodsManager`

**Example:** `PaymentStripeServiceProvider`

```php
public function boot(): void
{
    $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    $this->loadViewsFrom(__DIR__ . '/../resources/views', 'payment-stripe');
    $this->loadRoutesFrom(__DIR__ . '/routes.php');

    // Register payment method
    PaymentMethodsManager::getInstance()->register('stripe', StripePaymentMethod::class);
}
```

### 4. Payment Method Manager

**File:** `app/PaymentMethods/PaymentMethodsManager.php`

Central registry for all payment methods:
```php
// Get specific payment method
$stripeMethod = PaymentMethodsManager::getInstance()->get('stripe');

// Get all registered methods
$allMethods = PaymentMethodsManager::getInstance()->all();

// Get enabled methods
$enabledMethods = PaymentMethodsManager::getInstance()->getEnabled();
```

## Creating a New Payment Plugin

To add a new payment method:

### 1. Create Plugin Directory

```bash
mkdir -p plugins/PaymentNewMethod/{src,config,database/migrations,resources/views}
```

### 2. Create Payment Method Class

```php
// plugins/PaymentNewMethod/src/PaymentMethods/NewMethodPaymentMethod.php
namespace Plugins\PaymentNewMethod\PaymentMethods;

use App\Models\Order;
use App\PaymentMethods\PaymentMethodInterface;

class NewMethodPaymentMethod implements PaymentMethodInterface
{
    public function getKey(): string { return 'new_method'; }
    public function getName(): string { return 'New Payment Method'; }
    public function getIcon(): string { return 'fas fa-icon'; }
    public function isEnabled(): bool { /* ... */ }
    public function processPayment(Order $order): array { /* ... */ }
    public function getRoutePrefix(): string { return 'new-method'; }
    public function getViewPath(): string { return 'payment-newmethod::checkout.form'; }
    public function supportsRefunds(): bool { return false; }
}
```

### 3. Create Service Provider

```php
// plugins/PaymentNewMethod/src/PaymentNewMethodServiceProvider.php
namespace Plugins\PaymentNewMethod;

use Illuminate\Support\ServiceProvider;
use App\PaymentMethods\PaymentMethodsManager;

class PaymentNewMethodServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'payment-newmethod');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        PaymentMethodsManager::getInstance()->register(
            'new_method', 
            PaymentNewMethodPaymentMethod::class
        );
    }
}
```

### 4. Update Plugin Loader

```php
// plugins/PluginLoader.php
class PluginLoader
{
    public static function loadPaymentPlugins(): array
    {
        return [
            // ... existing plugins ...
            \Plugins\PaymentNewMethod\PaymentNewMethodServiceProvider::class,
        ];
    }
}
```

### 5. Run Migrations

```bash
php artisan migrate
```

## Benefits of Plugin Architecture

✅ **Modular** - Each payment method is isolated and independent
✅ **Scalable** - Easy to add new payment methods
✅ **Maintainable** - Changes to one plugin don't affect others
✅ **Testable** - Plugins can be tested independently
✅ **Deployable** - Enable/disable plugins per environment
✅ **Clean** - Main app code remains focused
✅ **Reusable** - Plugins can be shared across projects

## Plugin Management

### Enable/Disable Plugins

```php
// In bootstrap/providers.php
return array_merge([
    AppServiceProvider::class,
], array_filter([
    \Plugins\PaymentStripe\PaymentStripeServiceProvider::class,      // Always enabled
    env('ENABLE_PAYPAL') ? \Plugins\PaymentPayPal\PaymentPayPalServiceProvider::class : null,
    env('ENABLE_BANK_TRANSFER') ? \Plugins\PaymentBank\PaymentBankServiceProvider::class : null,
]));
```

### Plugin Configuration

Each plugin can be configured via environment variables:

```env
# Stripe
STRIPE_PUBLIC_KEY=pk_test_...
STRIPE_SECRET_KEY=sk_test_...
STRIPE_ENVIRONMENT=test

# PayPal
PAYPAL_CLIENT_ID=...
PAYPAL_CLIENT_SECRET=...
PAYPAL_ENVIRONMENT=sandbox

# Bank Transfer
BANK_ACCOUNT_HOLDER=Company Name
BANK_NAME=Bank Name
BANK_ACCOUNT_NUMBER=...
BANK_IFSC_CODE=...
BANK_BRANCH=...
```

## Migration and Integration

Old files that can be removed once plugins are in place:

- `app/PaymentMethods/StripePaymentMethod.php` ✓ Moved to plugin
- `app/PaymentMethods/PayPalPaymentMethod.php` ✓ Moved to plugin
- `app/PaymentMethods/BankTransferPaymentMethod.php` ✓ Moved to plugin
- `app/Services/StripeService.php` ✓ Moved to plugin
- `app/Services/PayPalService.php` ✓ Moved to plugin
- `app/Http/Controllers/Frontend/CardPaymentController.php` ✓ Moved to plugin
- `app/Http/Controllers/Frontend/PayPalController.php` ✓ Moved to plugin

Keep these core files:

- `app/PaymentMethods/PaymentMethodInterface.php` - Core interface
- `app/PaymentMethods/PaymentMethodsManager.php` - Central registry
- `app/Models/Payment.php` - Core payment model
- `app/Models/PaymentMethod.php` - Payment method configuration model

## Testing Plugins

Each plugin should have its own test suite:

```
plugins/PaymentStripe/tests/
├── Unit/
│   ├── Services/StripeServiceTest.php
│   └── PaymentMethods/StripePaymentMethodTest.php
└── Feature/
    └── Controllers/StripePaymentControllerTest.php
```

## Summary

The modularized payment plugin architecture provides:

1. **Clean separation of concerns** - Each payment method is self-contained
2. **Easy scaling** - Add new payment methods without touching core code
3. **Better organization** - Related code is grouped together
4. **Independent testing** - Test each payment method in isolation
5. **Flexible deployment** - Enable/disable plugins per environment
6. **Simplified maintenance** - Changes are localized to specific plugins
