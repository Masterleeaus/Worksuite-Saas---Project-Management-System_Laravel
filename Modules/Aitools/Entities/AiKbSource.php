<?php

namespace Modules\Aitools\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Aitools\Entities\Concerns\CompanyScoped;

class AiKbSource extends Model
{
    use CompanyScoped;

    protected $table = 'ai_kb_sources';

    protected $guarded = ['id'];

    protected $casts = [
        'meta' => 'array',
        'embedding' => 'array',
        'is_active' => 'boolean',
    ];
}
