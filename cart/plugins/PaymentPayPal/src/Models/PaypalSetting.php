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
        'currency',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
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
        return self::where('enabled', true)->first() ?? self::first();
    }
}
