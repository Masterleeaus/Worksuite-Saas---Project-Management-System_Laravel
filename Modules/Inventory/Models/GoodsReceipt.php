<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    protected $fillable = ['purchase_order_id','warehouse_id','received_at','reference'];
    protected $casts = ['received_at'=>'datetime'];

    public function order(){ return $this->belongsTo(PurchaseOrder::class,'purchase_order_id'); }
    public function items(){ return $this->hasMany(GoodsReceiptItem::class); }
}
