<?php

namespace Plugins\PaymentPayPal;

use Illuminate\Support\ServiceProvider;
use App\PaymentMethods\PaymentMethodsManager;
use Plugins\PaymentPayPal\PaymentMethods\PayPalPaymentMethod;

class PaymentPayPalServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'payment-paypal');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        // Register PayPal payment method
        PaymentMethodsManager::getInstance()->register('paypal', PayPalPaymentMethod::class);
    }

    /**
     * Register config
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/paypal.php',
            'payment.paypal'
        );
    }

    /**
     * Get the services provided by the provider
     */
    public function provides(): array
    {
        return [
            \Plugins\PaymentPayPal\Services\PayPalService::class,
        ];
    }
}
