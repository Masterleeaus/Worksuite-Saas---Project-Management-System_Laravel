<?php

namespace Modules\FSMCore\Models;

use Illuminate\Database\Eloquent\Model;

class FSMOrder extends Model
{
    protected $table = 'fsm_orders';

    protected $fillable = [
        'company_id',
        'name',
        'location_id',
        'person_id',
        'team_id',
        'stage_id',
        'template_id',
        'priority',
        'color',
        'scheduled_date_start',
        'scheduled_date_end',
        'date_start',
        'date_end',
        'description',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'location_id' => 'integer',
        'person_id' => 'integer',
        'team_id' => 'integer',
        'stage_id' => 'integer',
        'template_id' => 'integer',
        'color' => 'integer',
        'scheduled_date_start' => 'datetime',
        'scheduled_date_end' => 'datetime',
        'date_start' => 'datetime',
        'date_end' => 'datetime',
    ];

    public function location()
    {
        return $this->belongsTo(FSMLocation::class, 'location_id');
    }

    public function person()
    {
        return $this->belongsTo(\App\Models\User::class, 'person_id');
    }

    public function team()
    {
        return $this->belongsTo(FSMTeam::class, 'team_id');
    }

    public function stage()
    {
        return $this->belongsTo(FSMStage::class, 'stage_id');
    }

    public function template()
    {
        return $this->belongsTo(FSMTemplate::class, 'template_id');
    }

    public function equipment()
    {
        return $this->belongsToMany(FSMEquipment::class, 'fsm_order_equipment', 'fsm_order_id', 'fsm_equipment_id');
    }

    public function tags()
    {
        return $this->belongsToMany(FSMTag::class, 'fsm_order_tag', 'fsm_order_id', 'fsm_tag_id');
    }

    public function isUrgent(): bool
    {
        return $this->priority === '1';
    }
}
