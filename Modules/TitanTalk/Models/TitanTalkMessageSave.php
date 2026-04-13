<?php

namespace Modules\TitanTalk\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitanTalkMessageSave extends Model
{
    protected $table = 'titan_talk_message_saves';

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(TitanTalkMessage::class, 'message_id');
    }
}
