<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by', "id");

    }
    public function contactedBy()
    {
        return $this->belongsTo('App\Models\User', 'contact_by_id', "id");
    }
    public function supervisor()
    {
        return $this->belongsTo('App\Models\User', 'supervisor_id', "id");
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status_id', "id");
    }
    
}
