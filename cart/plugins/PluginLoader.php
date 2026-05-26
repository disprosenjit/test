<?php

namespace Plugins;

/**
 * Plugin Loader - Registers all payment plugins
 */
class PluginLoader
{
    /**
     * Load all payment plugins
     */
    public static function loadPaymentPlugins(): array
    {
        return [
            \Plugins\PaymentStripe\PaymentStripeServiceProvider::class,
            \Plugins\PaymentPayPal\PaymentPayPalServiceProvider::class,
            \Plugins\PaymentBank\PaymentBankServiceProvider::class,
        ];
    }
}
