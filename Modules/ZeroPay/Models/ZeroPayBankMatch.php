<?php

namespace Modules\ZeroPay\Models;

use App\Traits\HasCompany;

class ZeroPayBankMatch extends \App\Models\BaseModel
{
    use HasCompany;

    protected $table = 'zeropay_bank_matches';

    protected $guarded = ['id'];

    protected $casts = [
        'meta' => 'array',
        'matched_at' => 'datetime',
        'suggested_amount' => 'float',
        'confidence_score' => 'float',
    ];

    public function session()
    {
        return $this->belongsTo(ZeroPaySession::class, 'session_id');
    }
}
