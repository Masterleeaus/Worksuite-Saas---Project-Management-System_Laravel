<?php

namespace Modules\TitanZero\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiChatMessage extends Model
{
    protected $table = 'titanzero_ai_chat_messages';

    protected $fillable = [
        'ai_chat_session_id',
        'user_id',
        'input',
        'output',
        'response',
        'hash',
        'credits',
        'words',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(AiChatSession::class, 'ai_chat_session_id');
    }
}
