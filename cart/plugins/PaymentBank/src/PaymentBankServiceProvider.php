<?php

namespace Plugins\PaymentBank;

use Illuminate\Support\ServiceProvider;
use App\PaymentMethods\PaymentMethodsManager;
use Plugins\PaymentBank\PaymentMethods\BankTransferPaymentMethod;

class PaymentBankServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'payment-bank');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        // Register Bank Transfer payment method
        PaymentMethodsManager::getInstance()->register('bank_transfer', BankTransferPaymentMethod::class);
    }

    /**
     * Register config
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/bank.php',
            'payment.bank'
        );
    }

    /**
     * Get the services provided by the provider
     */
    public function provides(): array
    {
        return [];
    }
}
