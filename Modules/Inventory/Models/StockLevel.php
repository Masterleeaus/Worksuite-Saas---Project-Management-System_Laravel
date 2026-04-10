<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class StockLevel extends Model
{
    public $timestamps = true;
    protected $fillable = ['item_id','warehouse_id','on_hand','min_qty','max_qty'];

    protected $table = 'stock_levels';
}
