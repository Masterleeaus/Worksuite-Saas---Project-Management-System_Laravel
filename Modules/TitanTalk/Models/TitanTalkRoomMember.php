<?php

namespace Modules\TitanTalk\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitanTalkRoomMember extends Model
{
    protected $table = 'titan_talk_room_members';

    protected $guarded = ['id'];

    protected $casts = [
        'is_muted'     => 'boolean',
        'last_read_at' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(TitanTalkRoom::class, 'room_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
