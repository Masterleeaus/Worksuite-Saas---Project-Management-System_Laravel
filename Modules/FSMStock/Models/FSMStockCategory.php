<?php

namespace Modules\FSMStock\Models;

use Illuminate\Database\Eloquent\Model;

class FSMStockCategory extends Model
{
    protected $table = 'fsm_stock_categories';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'active'     => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(FSMStockItem::class, 'category_id');
    }
}
