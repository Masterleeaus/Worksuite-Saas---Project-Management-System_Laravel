<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\CustomerConnect\Traits\CompanyScoped;

class CampaignRun extends Model
{
    use CompanyScoped;
    protected $table = 'customerconnect_campaign_runs';
    protected $guarded = ['id'];

    protected $casts = [
        'meta' => 'array',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class, 'run_id');
    }
}