<?php

namespace Modules\TitanAgents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatbotCustomer extends Model
{
    protected $table = 'chatbot_customers';

    protected $fillable = [
        'chatbot_id',
        'channel_type',
        'channel_customer_id',
        'name',
        'email',
        'phone',
        'metadata',
        'conversation_count',
        'last_seen_at',
    ];

    protected $casts = [
        'metadata'     => 'array',
        'last_seen_at' => 'datetime',
    ];

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(ChatbotConversation::class, 'customer_id');
    }
}
