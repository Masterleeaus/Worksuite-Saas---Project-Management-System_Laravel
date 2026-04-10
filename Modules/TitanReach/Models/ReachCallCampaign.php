<?php

namespace Modules\TitanReach\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReachCallCampaign extends Model
{
    use SoftDeletes;

    protected $table = 'reach_call_campaigns';

    protected $fillable = [
        'company_id', 'name', 'status', 'call_script', 'twiml_url',
        'audience_type', 'audience_id', 'from_number', 'scheduled_at', 'stats', 'meta',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'stats'        => 'array',
        'meta'         => 'array',
    ];
}
