<?php

namespace Modules\Aitools\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;

class AiModel extends BaseModel
{
    use HasCompany;

    protected $table = 'ai_models';

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'pricing' => 'array',
        'meta' => 'array',
    ];

    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'provider_id');
    }
}
