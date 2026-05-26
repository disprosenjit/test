<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the route key for implicit model binding.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
