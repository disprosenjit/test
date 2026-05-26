<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaypalIpn extends Model
{
    protected $table = 'paypal_ipns';
    protected $guarded = [];

    protected $casts = [
        'verified' => 'boolean',
        'processed' => 'boolean',
        'test_ipn' => 'boolean',
        'mc_gross' => 'float',
        'mc_fee' => 'float',
        'paypal_timestamp' => 'datetime',
    ];

    /**
     * Relationship: Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship: Payment
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted(): bool
    {
        return in_array($this->payment_status, ['Completed', 'Processed']);
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'Pending';
    }

    /**
     * Check if payment is refunded
     */
    public function isRefunded(): bool
    {
        return in_array($this->payment_status, ['Refunded', 'Reversed', 'Canceled_Reversal']);
    }

    /**
     * Check if payment failed
     */
    public function isFailed(): bool
    {
        return in_array($this->payment_status, ['Failed', 'Denied', 'Expired', 'Voided']);
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->payment_status) {
            'Completed', 'Processed' => 'green',
            'Pending' => 'yellow',
            'Refunded', 'Reversed', 'Canceled_Reversal' => 'blue',
            'Failed', 'Denied', 'Expired', 'Voided' => 'red',
            default => 'gray',
        };
    }

    /**
     * Scope: verified
     */
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    /**
     * Scope: unverified
     */
    public function scopeUnverified($query)
    {
        return $query->where('verified', false);
    }

    /**
     * Scope: processed
     */
    public function scopeProcessed($query)
    {
        return $query->where('processed', true);
    }

    /**
     * Scope: unprocessed
     */
    public function scopeUnprocessed($query)
    {
        return $query->where('processed', false);
    }

    /**
     * Scope: completed payments
     */
    public function scopeCompleted($query)
    {
        return $query->whereIn('payment_status', ['Completed', 'Processed']);
    }

    /**
     * Scope: failed payments
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('payment_status', ['Failed', 'Denied', 'Expired', 'Voided']);
    }

    /**
     * Scope: refunded payments
     */
    public function scopeRefunded($query)
    {
        return $query->whereIn('payment_status', ['Refunded', 'Reversed', 'Canceled_Reversal']);
    }
}
