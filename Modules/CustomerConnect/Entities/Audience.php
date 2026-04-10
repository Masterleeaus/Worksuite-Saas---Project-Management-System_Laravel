<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\CustomerConnect\Traits\CompanyScoped;

class Audience extends Model
{
    use CompanyScoped;
    protected $table = 'customerconnect_audiences';
    protected $guarded = ['id'];

    protected $casts = [
        'filters' => 'array',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(AudienceMember::class, 'audience_id');
    }
}