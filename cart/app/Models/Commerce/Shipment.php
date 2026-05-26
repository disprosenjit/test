<?php

namespace App\Models\Commerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = [
        'order_id',
        'tracking_number',
        'carrier',
        'carrier_name',
        'shipping_cost',
        'tracking_details',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'tracking_details' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function scopePending($query)
    {
        return $query->where('carrier', 'pending');
    }

    public function scopeInTransit($query)
    {
        return $query->where('carrier', 'in_transit');
    }

    public function scopeDelivered($query)
    {
        return $query->where('carrier', 'delivered');
    }
}
