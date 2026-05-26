<?php

namespace Plugins\PaymentStripe\Models;

use App\Models\Payment\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeRefund extends Model
{
    protected $table = 'stripe_refunds';
    protected $fillable = [
        'payment_id',
        'stripe_charge_id',
        'stripe_refund_id',
        'amount',
        'type',
        'status',
        'reason',
        'stripe_response',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'stripe_response' => 'array',
        'amount' => 'float',
        'processed_at' => 'datetime',
    ];

    /**
     * Payment relationship
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * User who processed the refund
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Check if refund is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if refund is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if refund is failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Mark as completed
     */
    public function markCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);
    }

    /**
     * Mark as failed
     */
    public function markFailed(): void
    {
        $this->update(['status' => 'failed']);
    }
}
