<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by', "id");

    }

    public function subMajorCategory()
    {
        return $this->belongsTo('App\Models\SubMajorCategory', 'sub_major_category_id', "id");

    }
}
