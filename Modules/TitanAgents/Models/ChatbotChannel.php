<?php

namespace Modules\TitanAgents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotChannel extends Model
{
    protected $table = 'chatbot_channels';

    protected $fillable = [
        'chatbot_id',
        'channel_type',
        'channel_identifier',
        'webhook_url',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings'  => 'array',
    ];

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }
}
