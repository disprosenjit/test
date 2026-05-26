<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentInquiry extends Model
{
    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'inquiry_details',
        'investment_amount',
        'investment_type',
        'preferred_location',
        'status',
    ];

    protected $casts = [
        'investment_amount' => 'decimal:2',
    ];
}
