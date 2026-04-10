<?php

namespace Modules\FSMRoute\Models;

use Illuminate\Database\Eloquent\Model;

class FSMDayRoute extends Model
{
    protected $table = 'fsm_day_routes';
    protected $fillable = [
        'company_id', 'name', 'route_id', 'date', 'person_id', 'vehicle_id',
        'state', 'date_start_planned', 'work_time', 'max_allow_time',
    ];
    protected $casts = [
        'date'               => 'date',
        'date_start_planned' => 'datetime',
        'work_time'          => 'float',
        'max_allow_time'     => 'float',
    ];

    public function route()
    {
        return $this->belongsTo(FSMRoute::class, 'route_id');
    }

    public function person()
    {
        return $this->belongsTo(\App\Models\User::class, 'person_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(\Modules\FSMVehicle\Models\FSMVehicle::class, 'vehicle_id');
    }

    public function orders()
    {
        return $this->hasMany(\Modules\FSMCore\Models\FSMOrder::class, 'dayroute_id')
                    ->orderBy('route_sequence');
    }

    public function orderCount(): int
    {
        return $this->orders()->count();
    }

    public function remainingCapacity(): int
    {
        return max(0, ($this->route?->max_order ?? 0) - $this->orderCount());
    }
}
