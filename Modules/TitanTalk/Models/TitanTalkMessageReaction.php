<?php

namespace Modules\TitanTalk\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitanTalkMessageReaction extends Model
{
    protected $table = 'titan_talk_message_reactions';

    protected $guarded = ['id'];

    public function message(): BelongsTo
    {
        return $this->belongsTo(TitanTalkMessage::class, 'message_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
