<?php

namespace Modules\TitanAgents\Models\Voice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VoiceChatbotConversation extends Model
{
    protected $table = 'voice_chatbot_conversations';

    protected $fillable = ['chatbot_uuid', 'conversation_id', 'status'];

    public function chat_histories(): HasMany
    {
        return $this->hasMany(VoiceChatbotHistory::class, 'conversation_id');
    }

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(VoiceChatbot::class, 'chatbot_uuid', 'uuid');
    }
}
