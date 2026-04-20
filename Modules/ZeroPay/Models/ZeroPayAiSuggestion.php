<?php

namespace Modules\ZeroPay\Models;

use App\Traits\HasCompany;

class ZeroPayAiSuggestion extends \App\Models\BaseModel
{
    use HasCompany;

    protected $table = 'zeropay_ai_suggestions';

    protected $guarded = ['id'];

    protected $casts = [
        'accepted_at' => 'datetime',
        'dismissed_at' => 'datetime',
        'meta' => 'array',
        'score' => 'float',
    ];

    public function session()
    {
        return $this->belongsTo(ZeroPaySession::class, 'session_id');
    }
}
