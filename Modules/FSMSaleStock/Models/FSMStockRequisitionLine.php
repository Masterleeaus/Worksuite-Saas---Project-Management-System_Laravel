<?php

namespace Modules\FSMSaleStock\Models;

use Illuminate\Database\Eloquent\Model;

class FSMStockRequisitionLine extends Model
{
    protected $table = 'fsm_stock_requisition_lines';

    protected $fillable = [
        'requisition_id',
        'product_name',
        'part_number',
        'quantity',
        'unit_cost',
        'notes',
    ];

    protected $casts = [
        'requisition_id' => 'integer',
        'quantity'       => 'decimal:3',
        'unit_cost'      => 'decimal:2',
    ];

    public function requisition()
    {
        return $this->belongsTo(FSMStockRequisition::class, 'requisition_id');
    }

    public function lineTotal(): float
    {
        return (float) $this->quantity * (float) $this->unit_cost;
    }
}
