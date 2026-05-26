<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotConversation extends Model
{
    protected $table = 'chatbot_conversations';

    protected $fillable = [
        'user_id',
        'session_id',
        'messages',
        'status',
        'escalated_to_support',
        'support_email',
        'escalation_reason',
        'escalated_at',
        'closed_at',
    ];

    protected $casts = [
        'messages' => 'array',
        'escalated_to_support' => 'boolean',
        'escalated_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeEscalated($query)
    {
        return $query->where('escalated_to_support', true);
    }

    public function addMessage($role, $content)
    {
        $messages = $this->messages ?? [];
        $messages[] = [
            'role' => $role,
            'content' => $content,
            'timestamp' => now()->toIso8601String(),
        ];
        $this->update(['messages' => $messages]);
        return $this;
    }

    public function escalate($supportEmail, $reason)
    {
        $this->update([
            'escalated_to_support' => true,
            'support_email' => $supportEmail,
            'escalation_reason' => $reason,
            'escalated_at' => now(),
            'status' => 'escalated',
        ]);
        return $this;
    }

    public function close()
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
        return $this;
    }
}
