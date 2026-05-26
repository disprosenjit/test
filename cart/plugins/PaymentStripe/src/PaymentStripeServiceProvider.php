<?php

namespace Plugins\PaymentStripe;

use Illuminate\Support\ServiceProvider;
use App\PaymentMethods\PaymentMethodsManager;
use Plugins\PaymentStripe\PaymentMethods\StripePaymentMethod;

class PaymentStripeServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Boot the service provider
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'payment-stripe');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        // Register Stripe payment method
        PaymentMethodsManager::getInstance()->register('stripe', StripePaymentMethod::class);
    }

    /**
     * Register config
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/stripe.php',
            'payment.stripe'
        );
    }

    /**
     * Get the services provided by the provider
     */
    public function provides(): array
    {
        return [
            \Plugins\PaymentStripe\Services\StripeService::class,
        ];
    }
}
