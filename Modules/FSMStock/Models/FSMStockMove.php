<?php

namespace Modules\FSMStock\Models;

use Illuminate\Database\Eloquent\Model;

class FSMStockMove extends Model
{
    protected $table = 'fsm_stock_moves';

    protected $fillable = [
        'company_id',
        'fsm_order_id',
        'fsm_order_stock_line_id',
        'product_id',
        'qty',
        'direction',
        'reason',
        'moved_by',
        'moved_at',
    ];

    protected $casts = [
        'company_id'   => 'integer',
        'fsm_order_id' => 'integer',
        'product_id'   => 'integer',
        'qty'          => 'decimal:4',
        'moved_at'     => 'datetime',
        'moved_by'     => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(FSMStockItem::class, 'product_id');
    }

    public function order()
    {
        return $this->belongsTo(\Modules\FSMCore\Models\FSMOrder::class, 'fsm_order_id');
    }

    public function mover()
    {
        return $this->belongsTo(\App\Models\User::class, 'moved_by');
    }
}
