<?php

namespace Modules\FSMRoute\Models;

use Illuminate\Database\Eloquent\Model;

class FSMWorkerAvailability extends Model
{
    protected $table = 'fsm_worker_availability';
    protected $fillable = ['person_id', 'date', 'available', 'reason'];
    protected $casts = ['date' => 'date', 'available' => 'boolean'];

    public function person()
    {
        return $this->belongsTo(\App\Models\User::class, 'person_id');
    }
}
