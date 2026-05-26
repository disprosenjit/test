<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'key',
        'name',
        'description',
        'type',
        'is_enabled',
        'is_default',
        'sort_order',
        'minimum_amount',
        'maximum_amount',
        'settings',
        'icon_class',
        'controller_path',
        'service_path',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_default' => 'boolean',
        'settings' => 'array',
        'minimum_amount' => 'float',
        'maximum_amount' => 'float',
    ];

    /**
     * Scope: enabled payment methods
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * Scope: disabled payment methods
     */
    public function scopeDisabled($query)
    {
        return $query->where('is_enabled', false);
    }

    /**
     * Get all enabled methods sorted by order
     */
    public static function getEnabledMethods()
    {
        return static::enabled()->orderBy('sort_order')->get();
    }

    /**
     * Get enabled method by key
     */
    public static function getByKey($key)
    {
        return static::where('key', $key)->first();
    }

    /**
     * Check if method is enabled
     */
    public static function isEnabled($key)
    {
        $method = static::getByKey($key);
        return $method && $method->is_enabled;
    }

    /**
     * Enable a payment method
     */
    public function enable()
    {
        $this->update(['is_enabled' => true]);
        return $this;
    }

    /**
     * Disable a payment method
     */
    public function disable()
    {
        $this->update(['is_enabled' => false]);
        return $this;
    }

    /**
     * Set as default
     */
    public function setAsDefault()
    {
        PaymentMethod::query()->update(['is_default' => false]);
        $this->update(['is_default' => true, 'is_enabled' => true]);
        return $this;
    }

    /**
     * Get service instance
     */
    public function getService()
    {
        if (!$this->service_path) {
            return null;
        }
        return app($this->service_path);
    }

    /**
     * Get controller instance
     */
    public function getController()
    {
        if (!$this->controller_path) {
            return null;
        }
        return app($this->controller_path);
    }

    /**
     * Check if method can handle amount
     */
    public function canHandleAmount($amount)
    {
        if ($this->minimum_amount && $amount < $this->minimum_amount) {
            return false;
        }
        if ($this->maximum_amount && $amount > $this->maximum_amount) {
            return false;
        }
        return true;
    }
}
