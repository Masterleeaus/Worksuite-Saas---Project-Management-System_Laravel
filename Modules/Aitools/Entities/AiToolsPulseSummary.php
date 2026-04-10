<?php

namespace Modules\Aitools\Entities;

use Illuminate\Database\Eloquent\Model;

class AiToolsPulseSummary extends Model
{
    protected $table = 'ai_tools_pulse_summaries';

    protected $fillable = [
        'company_id',
        'user_id',
        'for_date',
        'window',
        'summary',
        'metrics',
    ];

    protected $casts = [
        'for_date' => 'date',
        'metrics' => 'array',
    ];
}
