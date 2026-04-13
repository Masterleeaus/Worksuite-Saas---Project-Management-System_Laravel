<?php

namespace Modules\NexusFieldOps\Models;

use Illuminate\Database\Eloquent\Model;

class NexusLocationPing extends Model
{
    protected $table = 'nexus_location_pings';

    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'worker_id',
        'fsm_order_id',
        'latitude',
        'longitude',
        'accuracy',
        'tracking_mode',
        'pinged_at',
    ];

    protected $casts = [
        'company_id'   => 'integer',
        'worker_id'    => 'integer',
        'fsm_order_id' => 'integer',
        'latitude'     => 'float',
        'longitude'    => 'float',
        'accuracy'     => 'float',
        'pinged_at'    => 'datetime',
    ];

    public function worker()
    {
        return $this->belongsTo(\App\Models\User::class, 'worker_id');
    }

    public function order()
    {
        return $this->belongsTo(\Modules\FSMCore\Models\FSMOrder::class, 'fsm_order_id');
    }
}
