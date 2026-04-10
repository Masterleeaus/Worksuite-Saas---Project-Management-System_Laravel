<?php

namespace Modules\TitanPWA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PushSubscription
 *
 * Stores Web Push API subscriptions (VAPID) per user/device.
 *
 * @property int    $id
 * @property int    $user_id
 * @property string $endpoint
 * @property string $p256dh
 * @property string $auth_token
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class PushSubscription extends Model
{
    protected $table = 'pwa_push_subscriptions';

    protected $fillable = [
        'user_id',
        'endpoint',
        'p256dh',
        'auth_token',
        'user_agent',
    ];

    protected $hidden = [
        'p256dh',
        'auth_token',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
