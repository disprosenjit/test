<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{
    protected $table = 'app_settings';

    protected $fillable = [
        'store_name',
        'store_email',
        'store_phone',
        'store_address',
        'store_city',
        'store_state',
        'store_zip',
        'store_country',
        'business_registration',
        'tax_id',
        'currency',
        'timezone',
        'items_per_page',
        'enable_notifications',
        'enable_api',
        'footer_contact_description',
        'logo_path',
        'favicon_path',
        'primary_color',
        'secondary_color',
        'accent_color',
        'dark_mode_bg',
        'dark_mode_text',
        'dark_mode_accent',
        'enable_dark_mode',
    ];

    protected $casts = [
        'enable_notifications' => 'boolean',
        'enable_api' => 'boolean',
        'enable_dark_mode' => 'boolean',
        'items_per_page' => 'integer',
    ];

    /**
     * Get or create the single settings record
     */
    public static function getSettings()
    {
        return self::firstOrCreate(
            [],
            [
                'store_name' => 'Ship Spare Parts',
                'footer_contact_description' => 'Your trusted provider of maritime spare parts and equipment.',
                'primary_color' => '#2563eb',
                'secondary_color' => '#1e40af',
                'accent_color' => '#3b82f6',
                'dark_mode_bg' => '#1f2937',
                'dark_mode_text' => '#f3f4f6',
                'dark_mode_accent' => '#60a5fa',
                'enable_dark_mode' => true,
            ]
        );
    }

    /**
     * Get logo URL
     */
    public function getLogoUrl()
    {
        return $this->logo_path ? asset('storage/' . $this->logo_path) : asset('images/logo.png');
    }

    /**
     * Get favicon URL
     */
    public function getFaviconUrl()
    {
        return $this->favicon_path ? asset('storage/' . $this->favicon_path) : asset('favicon.ico');
    }
}
