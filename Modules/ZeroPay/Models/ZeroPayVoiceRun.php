<?php

namespace Modules\ZeroPay\Models;

use App\Traits\HasCompany;

class ZeroPayVoiceRun extends \App\Models\BaseModel
{
    use HasCompany;

    protected $table = 'zeropay_voice_runs';

    protected $guarded = ['id'];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'meta' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(ZeroPaySession::class, 'session_id');
    }
}
