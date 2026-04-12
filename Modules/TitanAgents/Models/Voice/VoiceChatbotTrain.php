<?php

namespace Modules\TitanAgents\Models\Voice;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoiceChatbotTrain extends Model
{
    protected $table = 'voice_chatbot_trains';

    protected $fillable = [
        'chatbot_id',
        'user_id',
        'doc_id',
        'name',
        'type',
        'file',
        'url',
        'text',
        'trained_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(VoiceChatbot::class, 'chatbot_id');
    }
}
