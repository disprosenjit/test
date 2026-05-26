<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StripeCustomer extends Model
{
    protected $fillable = [
        'user_id',
        'stripe_customer_id',
        'email',
        'name',
        'metadata',
        'synced_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'synced_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(StripeCard::class);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(StripeCharge::class);
    }

    public function defaultCard()
    {
        return $this->cards()->where('is_default', true)->first();
    }
}
