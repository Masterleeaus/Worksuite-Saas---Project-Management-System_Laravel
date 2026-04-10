<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\CustomerConnect\Traits\CompanyScoped;

class Delivery extends Model
{
    use CompanyScoped;
    protected $table = 'customerconnect_deliveries';
    protected $guarded = ['id'];

    protected $casts = [
        'provider_response' => 'array',
        'scheduled_for'     => 'datetime',
        'last_attempt_at'   => 'datetime',
        'sent_at'           => 'datetime',
    ];

    public function run(): BelongsTo
    {
        return $this->belongsTo(CampaignRun::class, 'run_id');
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    /**
     * UPGRADE 4: contact_id FK relationship (used by SendDelivery to link deliveries to inbox threads).
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }
}