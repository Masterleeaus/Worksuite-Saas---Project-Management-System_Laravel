<?php

namespace Modules\Accountings\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Modules\Accountings\Traits\HasUserScope;

class FinancialTransaction extends BaseModel
{
    use HasCompany;
    use HasUserScope;

    protected $table = 'acc_financial_transactions';

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'occurred_at' => 'datetime',
        'meta' => 'array',
    ];
}
