<?php

namespace Modules\TitanAgents\Models\Voice;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoiceChatbotAvatar extends Model
{
    protected $table = 'voice_chatbot_avatars';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'avatar',
    ];

    protected $appends = [
        'avatar_url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => asset($this->avatar),
        );
    }
}
