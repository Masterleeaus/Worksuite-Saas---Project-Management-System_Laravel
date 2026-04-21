<?php

namespace Modules\Accountings\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;

class AccountingPeriod extends BaseModel
{
    use HasCompany;

    protected $table = 'acc_accounting_periods';

    protected $guarded = ['id'];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'closed_at' => 'datetime',
        'meta' => 'array',
    ];
}
