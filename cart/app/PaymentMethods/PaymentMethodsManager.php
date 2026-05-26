<?php

namespace App\PaymentMethods;

use App\Models\Payment\PaymentMethod as PaymentMethodModel;
use Illuminate\Support\Collection;

class PaymentMethodsManager
{
    protected array $methods = [];
    protected static self $instance;

    public function __construct()
    {
        $this->registerDefaultMethods();
    }

    /**
     * Get singleton instance
     */
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Register default payment methods from plugins
     */
    protected function registerDefaultMethods(): void
    {
        // Methods are registered by their plugin service providers
        // This is called after plugins are loaded
    }

    /**
     * Register a payment method
     */
    public function register(string $key, string $classPath): void
    {
        $this->methods[$key] = $classPath;
    }

    /**
     * Get a specific payment method instance
     */
    public function get(string $key): ?PaymentMethodInterface
    {
        if (!isset($this->methods[$key])) {
            return null;
        }

        return app($this->methods[$key]);
    }

    /**
     * Get all registered payment methods
     */
    public function all(): array
    {
        return $this->methods;
    }

    /**
     * Get enabled payment methods
     */
    public function getEnabled(): Collection
    {
        $enabled = PaymentMethodModel::enabled()
            ->orderBy('sort_order')
            ->get();

        return $enabled->map(function ($method) {
            return $this->get($method->key);
        })->filter();
    }

    /**
     * Check if a payment method is enabled
     */
    public function isEnabled(string $key): bool
    {
        $method = PaymentMethodModel::where('key', $key)->first();
        return $method && $method->is_enabled;
    }

    /**
     * Get default enabled method
     */
    public function getDefault(): ?PaymentMethodInterface
    {
        $default = PaymentMethodModel::where('is_default', true)
            ->where('is_enabled', true)
            ->first();

        if (!$default) {
            return null;
        }

        return $this->get($default->key);
    }

    /**
     * Get method config from database
     */
    public function getConfig(string $key): ?PaymentMethodModel
    {
        return PaymentMethodModel::where('key', $key)->first();
    }

    /**
     * Get all enabled method configs
     */
    public function getEnabledConfigs(): Collection
    {
        return PaymentMethodModel::enabled()->orderBy('sort_order')->get();
    }
}
