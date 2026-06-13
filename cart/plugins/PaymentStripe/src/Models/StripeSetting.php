<?php

namespace Plugins\PaymentStripe\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class StripeSetting extends Model
{
    protected $table = 'stripe_settings';
    protected $fillable = [
        'publishable_key',
        'secret_key',
        'webhook_secret',
        'environment',
        'is_active',
        'business_name',
        'business_email',
        'currency',
        'minimum_amount',
        'maximum_amount',
        'webhook_events',
        'notes',
        'configured_at',
        'configured_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'webhook_events' => 'array',
        'minimum_amount' => 'decimal:2',
        'maximum_amount' => 'decimal:2',
        'configured_at' => 'datetime',
    ];

    /**
     * Check if Stripe is configured
     */
    public static function isConfigured(): bool
    {
        $setting = self::first();
        return $setting && !empty($setting->secret_key) && !empty($setting->publishable_key);
    }

    /**
     * Get active settings
     */
    public static function getActive(): ?self
    {
        return self::where('is_active', true)->first() ?? self::first();
    }

    /**
     * Get decrypted publishable key
     */
    public function getDecryptedPublishableKey(): ?string
    {
        try {
            return $this->publishable_key ? Crypt::decryptString($this->publishable_key) : null;
        } catch (\Exception $e) {
            return $this->publishable_key; // Return as-is if not encrypted
        }
    }

    /**
     * Get decrypted secret key
     */
    public function getDecryptedSecretKey(): ?string
    {
        try {
            return $this->secret_key ? Crypt::decryptString($this->secret_key) : null;
        } catch (\Exception $e) {
            return $this->secret_key; // Return as-is if not encrypted
        }
    }

    /**
     * Get webhook secret
     */
    public function getWebhookSecret(): ?string
    {
        try {
            return $this->webhook_secret ? Crypt::decryptString($this->webhook_secret) : null;
        } catch (\Exception $e) {
            return $this->webhook_secret;
        }
    }
}
