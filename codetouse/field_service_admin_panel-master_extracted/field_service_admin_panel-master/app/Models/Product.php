<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by', "id");

    }

    public function majorCategory()
    {
        return $this->belongsTo('App\Models\MajorCategory', 'major_category_id', "id");
    }

    public function subMajorCategory()
    {
        return $this->belongsTo('App\Models\SubMajorCategory', 'sub_major_category_id', "id");
    }

    public function subCategory()
    {
        return $this->belongsTo('App\Models\SubCategory', 'sub_category_id', "id");
    }

    public function parentBrand()
    {
        return $this->belongsTo('App\Models\ParentBrand', 'parent_brand_id', "id");
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id', "id");
    }

    public function uomType()
    {
        return $this->belongsTo('App\Models\UomType', 'uom_type_id', "id");
    }
    public function uom()
    {
        return $this->belongsTo('App\Models\Uom', 'uom_id', "id");
    }
    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status_id', "id");
    }



}
