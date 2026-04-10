<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\CustomerConnect\Traits\CompanyScoped;

class Campaign extends Model
{
    use CompanyScoped;
    protected $table = 'customerconnect_campaigns';

    protected $guarded = ['id'];

    protected $casts = [
        'stop_on_reply' => 'boolean',
        'settings' => 'array',
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(CampaignStep::class, 'campaign_id')->orderBy('position');
    }

    public function runs(): HasMany
    {
        return $this->hasMany(CampaignRun::class, 'campaign_id')->latest();
    }
}