<?php

namespace App\Models\Commerce;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'subtotal',
        'tax',
        'total',
        'expires_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now())->orWhereNull('expires_at');
    }

    public function addItem($productId, $quantity, $price)
    {
        $cartItem = $this->items()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
            $cartItem->update(['subtotal' => $cartItem->quantity * $price]);
        } else {
            $this->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $quantity * $price,
            ]);
        }

        $this->recalculateTotals();
        return $this;
    }

    public function removeItem($cartItemId)
    {
        $this->items()->where('id', $cartItemId)->delete();
        $this->recalculateTotals();
        $this->refresh();
        return $this;
    }

    public function updateItem($cartItemId, $quantity)
    {
        $item = $this->items()->find($cartItemId);
        if ($item) {
            $item->update([
                'quantity' => $quantity,
                'subtotal' => $quantity * $item->price,
            ]);
            $this->recalculateTotals();
            $this->refresh();
        }
        return $this;
    }

    public function recalculateTotals()
    {
        $subtotal = $this->items()->sum('subtotal');
        $tax = $subtotal * 0.18; // 18% GST
        $total = $subtotal + $tax;

        $this->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ]);

        return $this;
    }

    public function clear()
    {
        $this->items()->delete();
        $this->update([
            'subtotal' => 0,
            'tax' => 0,
            'total' => 0,
        ]);
        $this->refresh();
        return $this;
    }
}
