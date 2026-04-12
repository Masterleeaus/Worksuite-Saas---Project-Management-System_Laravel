<?php

namespace Modules\TitanAgents\Models\Voice;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VoiceChatbot extends Model
{
    protected $table = 'voice_chatbots';

    protected $fillable = [
        'uuid',
        'user_id',
        'agent_id',
        'title',
        'bubble_message',
        'welcome_message',
        'instructions',
        'language',
        'ai_model',
        'avatar',
        'voice_id',
        'position',
        'active',
        'is_favorite',
    ];

    protected $casts = [
        'active'      => 'boolean',
        'is_favorite' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trains(): HasMany
    {
        return $this->hasMany(VoiceChatbotTrain::class, 'chatbot_id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(VoiceChatbotConversation::class, 'chatbot_uuid', 'uuid');
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
