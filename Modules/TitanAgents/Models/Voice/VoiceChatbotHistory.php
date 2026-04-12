<?php

namespace Modules\TitanAgents\Models\Voice;

use Modules\TitanAgents\Enums\Voice\RoleEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoiceChatbotHistory extends Model
{
    protected $table = 'voice_chatbot_histories';

    protected $fillable = [
        'conversation_id',
        'role',
        'message',
    ];

    protected $casts = [
        'role' => RoleEnum::class,
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(VoiceChatbotConversation::class, 'conversation_id');
    }
}
