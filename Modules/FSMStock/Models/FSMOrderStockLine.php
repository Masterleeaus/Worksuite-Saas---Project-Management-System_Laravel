<?php

namespace Modules\FSMStock\Models;

use Illuminate\Database\Eloquent\Model;

class FSMOrderStockLine extends Model
{
    public const STATE_PLANNED  = 'planned';
    public const STATE_CONSUMED = 'consumed';
    public const STATE_RETURNED = 'returned';

    protected $table = 'fsm_order_stock_lines';

    protected $fillable = [
        'company_id',
        'fsm_order_id',
        'product_id',
        'qty_planned',
        'qty_used',
        'billable',
        'state',
        'notes',
    ];

    protected $casts = [
        'company_id'   => 'integer',
        'fsm_order_id' => 'integer',
        'product_id'   => 'integer',
        'qty_planned'  => 'decimal:4',
        'qty_used'     => 'decimal:4',
        'billable'     => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(FSMStockItem::class, 'product_id');
    }

    public function order()
    {
        return $this->belongsTo(\Modules\FSMCore\Models\FSMOrder::class, 'fsm_order_id');
    }
}
