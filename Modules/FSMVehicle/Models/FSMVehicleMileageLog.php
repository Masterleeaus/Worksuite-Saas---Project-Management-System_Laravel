<?php

namespace Modules\FSMVehicle\Models;

use Illuminate\Database\Eloquent\Model;

class FSMVehicleMileageLog extends Model
{
    protected $table = 'fsm_vehicle_mileage_logs';

    protected $fillable = [
        'vehicle_id',
        'fsm_order_id',
        'logged_by',
        'odometer_start',
        'odometer_end',
        'log_date',
        'notes',
    ];

    protected $casts = [
        'vehicle_id'     => 'integer',
        'fsm_order_id'   => 'integer',
        'logged_by'      => 'integer',
        'odometer_start' => 'integer',
        'odometer_end'   => 'integer',
        'km_driven'      => 'integer',
        'log_date'       => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(FSMVehicle::class, 'vehicle_id');
    }

    public function order()
    {
        return $this->belongsTo(\Modules\FSMCore\Models\FSMOrder::class, 'fsm_order_id');
    }

    public function logger()
    {
        return $this->belongsTo(\App\Models\User::class, 'logged_by');
    }
}
