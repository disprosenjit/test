<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'method',
        'status',
        'amount',
        'transaction_reference',
        'gateway_response',
        'metadata',
        'proof_file_path',
        'verified_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'verified_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function stripeRefunds(): HasMany
    {
        return $this->hasMany(StripeRefund::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }

    public function markAsApproved($transactionRef = null)
    {
        $this->update([
            'status' => 'approved',
            'transaction_reference' => $transactionRef ?? $this->transaction_reference,
            'verified_at' => now(),
        ]);

        // Update order payment status
        $this->order->update(['payment_status' => 'approved']);

        return $this;
    }

    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'gateway_response' => $reason,
        ]);

        return $this;
    }
}
