<?php

namespace Modules\TitanTalk\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitanTalkUserStatus extends Model
{
    protected $table = 'titan_talk_user_statuses';

    protected $guarded = ['id'];

    protected $casts = [
        'expires_at'    => 'datetime',
        'last_seen_at'  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isOnline(): bool
    {
        return $this->presence === 'online';
    }
}
