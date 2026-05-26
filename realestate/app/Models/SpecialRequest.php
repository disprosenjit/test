<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'request_details',
        'budget_range',
        'location_preference',
        'property_type_preference',
        'status',
    ];
}
