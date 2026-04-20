<?php

namespace Modules\ZeroPay\Models;

use App\Traits\HasCompany;

class ZeroPayAttempt extends \App\Models\BaseModel
{
    use HasCompany;

    protected $table = 'zeropay_attempts';

    protected $guarded = ['id'];

    protected $casts = [
        'payload' => 'array',
        'confirmed_at' => 'datetime',
        'amount' => 'float',
        'fee_amount' => 'float',
    ];

    public function session()
    {
        return $this->belongsTo(ZeroPaySession::class, 'session_id');
    }
}
