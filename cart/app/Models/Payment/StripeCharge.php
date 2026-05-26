<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeCharge extends Model
{
    protected $fillable = [
        'payment_id',
        'stripe_customer_id',
        'stripe_card_id',
        'stripe_charge_id',
        'stripe_payment_intent_id',
        'amount',
        'currency',
        'status',
        'failure_message',
        'failure_code',
        'metadata',
        'receipt_data',
        'charged_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'receipt_data' => 'array',
        'charged_at' => 'datetime',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function stripeCustomer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class);
    }

    public function stripeCard(): BelongsTo
    {
        return $this->belongsTo(StripeCard::class);
    }

    public function scopeSucceeded($query)
    {
        return $query->where('status', 'succeeded');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
