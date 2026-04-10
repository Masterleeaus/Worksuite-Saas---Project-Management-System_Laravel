<?php

namespace Modules\Aitools\Entities;

use Illuminate\Database\Eloquent\Model;

class AiToolsSignal extends Model
{
    protected $table = 'ai_tools_signals';

    protected $fillable = [
        'company_id',
        'user_id',
        'type',
        'severity',
        'payload',
        'occurred_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'occurred_at' => 'datetime',
    ];
}
