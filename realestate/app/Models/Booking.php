<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'property_id',
        'user_id',
        'check_in',
        'check_out',
        'total_days',
        'price_per_day',
        'total_price',
        'status',
        'notes',
    ];

    protected $casts = [
        'check_in'      => 'date',
        'check_out'     => 'date',
        'price_per_day' => 'float',
        'total_price'   => 'float',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isConfirmed(): bool { return $this->status === 'confirmed'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'confirmed'  => 'bg-green-100 text-green-700',
            'cancelled'  => 'bg-red-100 text-red-700',
            default      => 'bg-yellow-100 text-yellow-700',
        };
    }
}
