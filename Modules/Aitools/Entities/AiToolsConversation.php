<?php

namespace Modules\Aitools\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Conversation container for Aitools-style chat.
 */
class AiToolsConversation extends Model
{
    protected $table = 'ai_tools_conversations';

    protected $fillable = [
        'company_id',
        'user_id',
        'title',
        'channel',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(AiToolsMessage::class, 'conversation_id');
    }
}
