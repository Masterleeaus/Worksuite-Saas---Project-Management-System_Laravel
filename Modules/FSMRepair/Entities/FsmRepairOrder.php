<?php

namespace Modules\FSMRepair\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FSMCore\Entities\FsmOrder;

class FsmRepairOrder extends Model
{
    use SoftDeletes;

    protected $table = 'fsm_repair_orders';

    protected $fillable = [
        'company_id', 'name', 'fsm_order_id', 'fsm_order_type_id',
        'product_id', 'lot_id', 'problem_description', 'repair_notes',
        'technician_id', 'partner_id', 'state',
        'scheduled_date', 'date_completed',
        'parts_cost', 'labour_cost', 'created_by',
    ];

    protected $casts = [
        'scheduled_date'  => 'datetime',
        'date_completed'  => 'datetime',
        'parts_cost'      => 'decimal:2',
        'labour_cost'     => 'decimal:2',
    ];

    public function fsmOrder(): BelongsTo
    {
        return $this->belongsTo(FsmOrder::class, 'fsm_order_id');
    }

    public function getTotalCostAttribute(): float
    {
        return (float)($this->parts_cost ?? 0) + (float)($this->labour_cost ?? 0);
    }
}
