<?php

namespace Modules\ZeroPay\Models;

use App\Traits\HasCompany;

class ZeroPayChannelLog extends \App\Models\BaseModel
{
    use HasCompany;

    protected $table = 'zeropay_channel_logs';

    protected $guarded = ['id'];

    protected $casts = [
        'sent_at' => 'datetime',
        'meta' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(ZeroPaySession::class, 'session_id');
    }
}
