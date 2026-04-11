<?php

namespace Modules\TitanAgents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatbotConversation extends Model
{
    protected $table = 'chatbot_conversations';

    protected $fillable = [
        'chatbot_id',
        'customer_id',
        'channel_type',
        'session_id',
        'status',
        'started_at',
        'ended_at',
        'resolution_notes',
        'metadata',
        'message_count',
    ];

    protected $casts = [
        'metadata'   => 'array',
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(ChatbotCustomer::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(ChatbotHistory::class, 'conversation_id')->orderBy('created_at');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeEscalated($query)
    {
        return $query->where('status', 'escalated');
    }
}
