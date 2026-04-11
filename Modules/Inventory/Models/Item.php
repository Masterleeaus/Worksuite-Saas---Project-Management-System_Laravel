<?php

namespace Modules\Inventory\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes, HasCompany;

    protected $table = 'inventory_items';

    protected $fillable = [
        'company_id',
        'field_item_id',
        'name',
        'sku',
        'unit',
    ];

    /**
     * Relation to FieldItems item (item catalogue source-of-truth).
     * Guarded against FieldItems module not being present.
     */
    public function fieldItem()
    {
        if (class_exists(\Modules\FieldItems\Entities\Item::class)) {
            return $this->belongsTo(\Modules\FieldItems\Entities\Item::class, 'field_item_id');
        }
        return null;
    }

    public function stockLevels()
    {
        return $this->hasMany(StockLevel::class, 'item_id');
    }

    public function movements()
    {
        return $this->hasMany(Movement::class, 'item_id');
    }
}
