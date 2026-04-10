<?php

namespace Modules\FSMRoute\Models;

use Illuminate\Database\Eloquent\Model;

class FSMRoute extends Model
{
    protected $table = 'fsm_routes';
    protected $fillable = ['company_id', 'name', 'person_id', 'max_order', 'active'];
    protected $casts = ['active' => 'boolean', 'max_order' => 'integer'];

    public function days()
    {
        return $this->belongsToMany(FSMRouteDay::class, 'fsm_route_day_pivot', 'fsm_route_id', 'fsm_route_day_id');
    }

    public function locations()
    {
        return $this->belongsToMany(
            \Modules\FSMCore\Models\FSMLocation::class,
            'fsm_route_location',
            'fsm_route_id',
            'fsm_location_id'
        );
    }

    public function person()
    {
        return $this->belongsTo(\App\Models\User::class, 'person_id');
    }

    public function dayRoutes()
    {
        return $this->hasMany(FSMDayRoute::class, 'route_id');
    }

    public function runsOn(\Carbon\Carbon $date): bool
    {
        $idx = (int) $date->format('N') - 1; // Mon=0..Sun=6
        return $this->days->contains('day_index', $idx);
    }
}
