<?php

namespace Modules\Aitools\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;

class AiToolRegistry extends BaseModel
{
    use HasCompany;

    protected $table = 'ai_tools_registry';

    protected $guarded = ['id'];

    protected $casts = [
        'input_schema' => 'array',
        'meta' => 'array',
        'is_enabled' => 'boolean',
    ];
}
