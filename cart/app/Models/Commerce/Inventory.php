<?php

namespace App\Models\Commerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $fillable = [
        'product_id',
        'warehouse_qty',
        'reserved_qty',
        'available_qty',
        'last_restock_at',
    ];

    protected $casts = [
        'last_restock_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function reserve($quantity)
    {
        if ($this->available_qty >= $quantity) {
            $this->increment('reserved_qty', $quantity);
            $this->decrement('available_qty', $quantity);
            return true;
        }
        return false;
    }

    public function release($quantity)
    {
        $this->decrement('reserved_qty', $quantity);
        $this->increment('available_qty', $quantity);
        return $this;
    }

    public function restock($quantity)
    {
        $this->increment('warehouse_qty', $quantity);
        $this->increment('available_qty', $quantity);
        $this->update(['last_restock_at' => now()]);
        return $this;
    }

    public function isOutOfStock()
    {
        return $this->available_qty <= 0;
    }
}
