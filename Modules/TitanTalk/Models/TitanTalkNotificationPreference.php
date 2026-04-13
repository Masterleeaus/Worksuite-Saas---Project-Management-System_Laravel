<?php

namespace Modules\TitanTalk\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitanTalkNotificationPreference extends Model
{
    protected $table = 'titan_talk_notification_preferences';

    protected $guarded = ['id'];

    protected $casts = [
        'notify_all_messages' => 'boolean',
        'notify_mentions'     => 'boolean',
        'notify_email'        => 'boolean',
        'notify_sms'          => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(TitanTalkRoom::class, 'room_id');
    }
}
