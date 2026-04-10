<?php

namespace Modules\Aitools\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Message row for a conversation.
 * role: user | assistant | tool
 */
class AiToolsMessage extends Model
{
    protected $table = 'ai_tools_messages';

    protected $fillable = [
        'conversation_id',
        'company_id',
        'user_id',
        'role',
        'content',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AiToolsConversation::class, 'conversation_id');
    }
}
