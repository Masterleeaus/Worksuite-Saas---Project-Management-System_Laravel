<?php

namespace Modules\TitanReach\Models;

use Illuminate\Database\Eloquent\Model;

class ReachCallLog extends Model
{
    protected $table = 'reach_call_logs';

    protected $fillable = [
        'company_id', 'call_campaign_id', 'contact_id', 'conversation_id',
        'call_sid', 'direction', 'from_number', 'to_number', 'status',
        'duration', 'recording_url', 'transcript', 'keypress', 'meta', 'called_at',
    ];

    protected $casts = [
        'meta'      => 'array',
        'called_at' => 'datetime',
        'duration'  => 'integer',
    ];
}
