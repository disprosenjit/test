<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StripeCard extends Model
{
    protected $fillable = [
        'stripe_customer_id',
        'payment_method_id',
        'brand',
        'last_four',
        'exp_month',
        'exp_year',
        'cardholder_name',
        'is_default',
        'metadata',
        'last_used_at',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'metadata' => 'array',
        'last_used_at' => 'datetime',
    ];

    public function stripeCustomer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(StripeCharge::class);
    }

    public function getDisplayName(): string
    {
        return "{$this->brand} ending in {$this->last_four}";
    }

    public function getExpiryDisplay(): string
    {
        return "{$this->exp_month}/{$this->exp_year}";
    }

    public function isExpired(): bool
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        if ($this->exp_year < $currentYear) {
            return true;
        }
        
        return $this->exp_year === $currentYear && $this->exp_month < $currentMonth;
    }
}
