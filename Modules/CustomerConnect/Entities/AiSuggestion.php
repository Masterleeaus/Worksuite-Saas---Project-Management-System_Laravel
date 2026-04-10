<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;

class AiSuggestion extends Model
{
    protected $table = 'customerconnect_ai_suggestions';

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
    ];
}
