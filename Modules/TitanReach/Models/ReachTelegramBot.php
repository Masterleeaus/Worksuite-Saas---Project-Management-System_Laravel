<?php

namespace Modules\TitanReach\Models;

use Illuminate\Database\Eloquent\Model;

class ReachTelegramBot extends Model
{
    protected $table = 'reach_telegram_bots';

    protected $fillable = [
        'company_id', 'name', 'bot_token', 'webhook_url', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $hidden = ['bot_token'];
}
