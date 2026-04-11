<?php

namespace Modules\TitanAgents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotPageVisit extends Model
{
    protected $table = 'chatbot_page_visits';

    protected $fillable = [
        'chatbot_id',
        'session_id',
        'page_url',
        'referrer',
        'ip_address',
        'user_agent',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }
}
