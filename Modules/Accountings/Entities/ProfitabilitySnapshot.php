<?php

namespace Modules\Accountings\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Modules\Accountings\Traits\HasUserScope;

class ProfitabilitySnapshot extends BaseModel
{
    use HasCompany;
    use HasUserScope;

    protected $table = 'acc_profitability_snapshots';

    protected $guarded = ['id'];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'flags' => 'array',
        'revenue' => 'decimal:2',
        'cost' => 'decimal:2',
        'margin' => 'decimal:2',
        'margin_percent' => 'decimal:4',
    ];
}
