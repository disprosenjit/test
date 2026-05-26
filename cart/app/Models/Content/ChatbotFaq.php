<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class ChatbotFaq extends Model
{
    protected $table = 'chatbot_faqs';

    protected $fillable = [
        'question',
        'answer',
        'category',
        'keywords',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
        return $this;
    }
}
