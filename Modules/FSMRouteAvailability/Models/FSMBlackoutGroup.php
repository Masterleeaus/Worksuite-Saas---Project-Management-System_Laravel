<?php

namespace Modules\FSMRouteAvailability\Models;

use Illuminate\Database\Eloquent\Model;

class FSMBlackoutGroup extends Model
{
    protected $table = 'fsm_blackout_groups';

    protected $fillable = ['company_id', 'name', 'description'];

    protected $casts = ['company_id' => 'integer'];

    public function blackoutDays()
    {
        return $this->hasMany(FSMBlackoutDay::class, 'blackout_group_id');
    }

    public function routes()
    {
        return $this->belongsToMany(
            \Modules\FSMRoute\Models\FSMRoute::class,
            'fsm_route_blackout_group',
            'blackout_group_id',
            'fsm_route_id'
        );
    }
}
