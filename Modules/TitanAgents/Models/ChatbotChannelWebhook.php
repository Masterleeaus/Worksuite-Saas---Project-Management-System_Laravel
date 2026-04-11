<?php

namespace Modules\TitanAgents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotChannelWebhook extends Model
{
    protected $table = 'chatbot_channel_webhooks';

    protected $fillable = [
        'chatbot_id',
        'channel_type',
        'webhook_secret',
        'webhook_url',
        'is_active',
        'last_called_at',
        'call_count',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'last_called_at' => 'datetime',
    ];

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }
}
