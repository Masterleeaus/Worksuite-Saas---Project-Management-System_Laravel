<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = ['item_id','from_warehouse_id','to_warehouse_id','quantity','note','status'];

    public function item(){ return $this->belongsTo(Item::class); }
    public function from(){ return $this->belongsTo(Warehouse::class, 'from_warehouse_id'); }
    public function to(){ return $this->belongsTo(Warehouse::class, 'to_warehouse_id'); }
}
