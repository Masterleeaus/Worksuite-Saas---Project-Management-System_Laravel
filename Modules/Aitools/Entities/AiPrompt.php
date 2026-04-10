<?php

namespace Modules\Aitools\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;

class AiPrompt extends BaseModel
{
    use HasCompany;

    protected $table = 'ai_prompts';

    protected $guarded = ['id'];

    protected $casts = [
        'meta' => 'array',
    ];
}
