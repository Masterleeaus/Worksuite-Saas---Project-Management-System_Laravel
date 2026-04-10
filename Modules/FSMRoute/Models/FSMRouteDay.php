<?php

namespace Modules\FSMRoute\Models;

use Illuminate\Database\Eloquent\Model;

class FSMRouteDay extends Model
{
    protected $table = 'fsm_route_days';
    public $timestamps = false;
    protected $fillable = ['name', 'day_index'];

    public function routes()
    {
        return $this->belongsToMany(FSMRoute::class, 'fsm_route_day_pivot', 'fsm_route_day_id', 'fsm_route_id');
    }
}
