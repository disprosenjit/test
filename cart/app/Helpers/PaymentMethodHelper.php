<?php

namespace App\Helpers;

use App\PaymentMethods\PaymentMethodsManager;
use Illuminate\Support\Collection;

class PaymentMethodHelper
{
    protected static PaymentMethodsManager $manager;

    /**
     * Initialize manager
     */
    public static function manager(): PaymentMethodsManager
    {
        if (!isset(self::$manager)) {
            self::$manager = PaymentMethodsManager::getInstance();
        }
        return self::$manager;
    }

    /**
     * Get all enabled payment methods
     */
    public static function getEnabled(): Collection
    {
        return self::manager()->getEnabled();
    }

    /**
     * Get default payment method
     */
    public static function getDefault()
    {
        return self::manager()->getDefault();
    }

    /**
     * Check if a method is enabled
     */
    public static function isEnabled(string $key): bool
    {
        return self::manager()->isEnabled($key);
    }

    /**
     * Get payment method by key
     */
    public static function get(string $key)
    {
        return self::manager()->get($key);
    }

    /**
     * Get enabled methods as array for views
     */
    public static function getEnabledForView(): array
    {
        return self::getEnabled()->map(function ($method) {
            return [
                'key' => $method->getKey(),
                'name' => $method->getName(),
                'icon' => $method->getIcon(),
            ];
        })->toArray();
    }
}
