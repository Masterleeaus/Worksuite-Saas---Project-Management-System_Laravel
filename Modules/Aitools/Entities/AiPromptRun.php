<?php

namespace Modules\Aitools\Entities;

use Illuminate\Database\Eloquent\Model;

class AiPromptRun extends Model
{
    protected $table = 'ai_prompt_runs';

    protected $guarded = ['id'];

    protected $casts = [
        'meta' => 'array',
    ];
}
