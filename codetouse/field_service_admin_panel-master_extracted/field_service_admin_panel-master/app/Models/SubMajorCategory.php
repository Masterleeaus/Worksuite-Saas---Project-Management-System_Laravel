<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubMajorCategory extends Model
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
}
