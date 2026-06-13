<?php

namespace Plugins\PaymentPayPal\Models;

use Illuminate\Database\Eloquent\Model;

class PaypalSetting extends Model
{
    protected $table = 'paypal_settings';
    protected $fillable = [
        'client_id',
        'client_secret',
        'environment',
        'is_active',
        'currency',
        'business_name',
        'business_email',
        'minimum_amount',
        'maximum_amount',
        'notes',
        'configured_at',
        'configured_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'minimum_amount' => 'decimal:2',
        'maximum_amount' => 'decimal:2',
        'configured_at' => 'datetime',
    ];

    /**
     * Check if PayPal is configured
     */
    public static function isConfigured(): bool
    {
        $setting = self::first();
        return $setting && !empty($setting->client_id) && !empty($setting->client_secret);
    }

    /**
     * Get active settings
     */
    public static function getActive(): ?self
    {
        return self::where('is_active', true)->first() ?? self::first();
    }
}
