<?php

namespace Modules\Aitools\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;

class AiRequestLog extends BaseModel
{
    use HasCompany;

    protected $table = 'ai_request_logs';

    protected $guarded = ['id'];

    protected $casts = [
        'request_meta' => 'array',
        'response_meta' => 'array',
    ];
}
