<?php

namespace Modules\Aitools\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;

class AiProvider extends BaseModel
{
    use HasCompany;

    protected $table = 'ai_providers';

    protected $guarded = ['id'];

    protected $casts = [
        'api_key' => 'encrypted',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'meta' => 'array',
    ];

    public function models()
    {
        return $this->hasMany(AiModel::class, 'provider_id');
    }
}
