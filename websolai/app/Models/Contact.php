<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'service',
        'budget',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getServiceLabelAttribute(): string
    {
        return match ($this->service) {
            'web'       => 'Web Development',
            'mobile'    => 'Mobile App Development',
            'design'    => 'UI/UX Design',
            'ecommerce' => 'E-Commerce Solution',
            'api'       => 'API Development & Integration',
            'cloud'     => 'Cloud & DevOps',
            default     => 'Other / Not Sure',
        };
    }

    public function getBudgetLabelAttribute(): string
    {
        return match ($this->budget) {
            'under-5k' => 'Under $5,000',
            '5k-15k'   => '$5,000 – $15,000',
            '15k-30k'  => '$15,000 – $30,000',
            '30k-50k'  => '$30,000 – $50,000',
            'over-50k' => 'Over $50,000',
            default    => '—',
        };
    }
}
