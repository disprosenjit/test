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

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * All booked dates (Y-m-d strings) from pending/confirmed bookings.
     * Used by the availability calendar.
     */
    public function bookedDates(): array
    {
        return $this->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('check_out', '>', now()->toDateString())
            ->get(['check_in', 'check_out'])
            ->flatMap(function ($booking) {
                $days = $booking->check_in->diffInDays($booking->check_out);
                return collect(range(0, $days - 1))
                    ->map(fn ($d) => $booking->check_in->copy()->addDays($d)->format('Y-m-d'));
            })
            ->unique()
            ->values()
            ->all();
    }
}
