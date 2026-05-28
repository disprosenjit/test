<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'type',
        'location',
        'country',
        'state',
        'city',
        'address',
        'latitude',
        'longitude',
        'area',
        'bedrooms',
        'bathrooms',
        'parking_spaces',
        'is_featured',
        'is_available',
        'agent_name',
        'agent_contact',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'area' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'is_featured' => 'boolean',
        'is_available' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }
}
