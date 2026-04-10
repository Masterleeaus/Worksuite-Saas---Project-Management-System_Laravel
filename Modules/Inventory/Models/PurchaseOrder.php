<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = ['supplier_id','status','ordered_at','reference','currency','notes','total'];
    protected $casts = ['ordered_at'=>'datetime'];

    public function supplier(){ return $this->belongsTo(Supplier::class); }
    public function items(){ return $this->hasMany(PurchaseOrderItem::class); }
}
