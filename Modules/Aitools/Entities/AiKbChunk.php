<?php

namespace Modules\Aitools\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Aitools\Entities\Concerns\CompanyScoped;

class AiKbChunk extends Model
{
    use CompanyScoped;

    protected $table = 'ai_kb_chunks';

    protected $guarded = ['id'];

    protected $casts = [
        'meta' => 'array',
        'embedding' => 'array',
        'is_active' => 'boolean',
    ];
}
