<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Uom extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by', "id");

    }
}
