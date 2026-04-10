<?php

namespace Modules\FSMStock\Models;

use Illuminate\Database\Eloquent\Model;

class FSMStockItem extends Model
{
    protected $table = 'fsm_stock_items';

    protected $fillable = [
        'company_id',
        'category_id',
        'name',
        'description',
        'unit',
        'current_qty',
        'min_qty',
        'cost_price',
        'supplier',
        'active',
    ];

    protected $casts = [
        'company_id'  => 'integer',
        'category_id' => 'integer',
        'current_qty' => 'decimal:4',
        'min_qty'     => 'decimal:4',
        'cost_price'  => 'decimal:4',
        'active'      => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(FSMStockCategory::class, 'category_id');
    }

    public function orderLines()
    {
        return $this->hasMany(FSMOrderStockLine::class, 'product_id');
    }

    public function stockMoves()
    {
        return $this->hasMany(FSMStockMove::class, 'product_id');
    }

    public function isBelowMinQty(): bool
    {
        return $this->current_qty < $this->min_qty;
    }
}
