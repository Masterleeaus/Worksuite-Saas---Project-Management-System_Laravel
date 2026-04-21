<?php

namespace Modules\Accountings\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;

class FinancialSignal extends BaseModel
{
    use HasCompany;

    protected $table = 'acc_financial_signals';

    protected $guarded = ['id'];

    protected $casts = [
        'occurred_at' => 'datetime',
        'payload' => 'array',
    ];
}
