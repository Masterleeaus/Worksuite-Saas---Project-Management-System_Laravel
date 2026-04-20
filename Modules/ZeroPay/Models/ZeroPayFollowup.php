<?php

namespace Modules\ZeroPay\Models;

use App\Traits\HasCompany;

class ZeroPayFollowup extends \App\Models\BaseModel
{
    use HasCompany;

    protected $table = 'zeropay_followups';

    protected $guarded = ['id'];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'meta' => 'array',
        'ai_suggested' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(ZeroPaySession::class, 'session_id');
    }
}
