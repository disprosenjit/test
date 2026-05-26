<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'name',
        'phone',
        'company_name',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeShipping($query)
    {
        return $query->whereIn('type', ['shipping', 'both']);
    }

    public function scopeBilling($query)
    {
        return $query->whereIn('type', ['billing', 'both']);
    }

    public function getFullAddress()
    {
        return "{$this->address_line_1}, {$this->address_line_2} {$this->city}, {$this->state} {$this->postal_code}, {$this->country}";
    }
}
