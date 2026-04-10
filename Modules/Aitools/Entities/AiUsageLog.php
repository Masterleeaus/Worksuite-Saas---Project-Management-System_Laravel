<?php

namespace Modules\Aitools\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;

class AiUsageLog extends BaseModel
{
    use HasCompany;

    protected $table = 'ai_usage_logs';

    protected $guarded = ['id'];

    protected $casts = [
        'prompt_tokens' => 'integer',
        'completion_tokens' => 'integer',
        'total_tokens' => 'integer',
        'total_requests' => 'integer',
        'estimated_cost' => 'float',
        'meta' => 'array',
    ];
}
