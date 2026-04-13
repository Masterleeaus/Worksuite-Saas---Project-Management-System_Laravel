<?php

namespace Modules\TitanTalk\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitanTalkRoomPin extends Model
{
    protected $table = 'titan_talk_room_pins';

    protected $guarded = ['id'];

    public function room(): BelongsTo
    {
        return $this->belongsTo(TitanTalkRoom::class, 'room_id');
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(TitanTalkMessage::class, 'message_id');
    }

    public function pinnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pinned_by');
    }
}
