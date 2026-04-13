<?php

namespace Modules\NexusFieldOps\Models;

use Illuminate\Database\Eloquent\Model;

class NexusJobNote extends Model
{
    protected $table = 'nexus_job_notes';

    protected $fillable = [
        'company_id',
        'fsm_order_id',
        'worker_id',
        'body',
        'note_type',
        'synced_offline',
    ];

    protected $casts = [
        'company_id'     => 'integer',
        'fsm_order_id'   => 'integer',
        'worker_id'      => 'integer',
        'synced_offline' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(\Modules\FSMCore\Models\FSMOrder::class, 'fsm_order_id');
    }

    public function worker()
    {
        return $this->belongsTo(\App\Models\User::class, 'worker_id');
    }
}
