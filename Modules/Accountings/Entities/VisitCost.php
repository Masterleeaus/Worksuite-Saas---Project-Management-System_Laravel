<?php

namespace Modules\Accountings\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Modules\Accountings\Traits\HasUserScope;

class VisitCost extends BaseModel
{
    use HasCompany;
    use HasUserScope;

    protected $table = 'acc_visit_costs';

    protected $guarded = ['id'];

    protected $casts = [
        'occurred_at' => 'datetime',
        'finalized_at' => 'datetime',
        'meta' => 'array',
        'labour_cost' => 'decimal:2',
        'travel_cost' => 'decimal:2',
        'equipment_cost' => 'decimal:2',
        'consumables_cost' => 'decimal:2',
        'overhead_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'revenue' => 'decimal:2',
        'margin' => 'decimal:2',
        'margin_percent' => 'decimal:4',
    ];
}
