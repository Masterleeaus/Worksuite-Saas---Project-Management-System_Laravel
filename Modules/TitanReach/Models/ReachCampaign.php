<?php

namespace Modules\TitanReach\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReachCampaign extends Model
{
    use SoftDeletes;

    protected $table = 'reach_campaigns';

    protected $fillable = [
        'company_id', 'name', 'channel', 'status', 'audience_type',
        'audience_id', 'content', 'call_script', 'scheduled_at', 'stats', 'meta',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'stats'        => 'array',
        'meta'         => 'array',
    ];
}
