<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\CustomerConnect\Traits\CompanyScoped;

class AudienceMember extends Model
{
    use CompanyScoped;
    protected $table = 'customerconnect_audience_members';
    protected $guarded = ['id'];

    public function audience(): BelongsTo
    {
        return $this->belongsTo(Audience::class, 'audience_id');
    }
}