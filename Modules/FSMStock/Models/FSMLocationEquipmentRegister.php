<?php

namespace Modules\FSMStock\Models;

use Illuminate\Database\Eloquent\Model;

class FSMLocationEquipmentRegister extends Model
{
    protected $table = 'fsm_location_equipment_registers';

    protected $fillable = [
        'company_id',
        'location_id',
        'fsm_equipment_id',
        'notes',
        'active',
    ];

    protected $casts = [
        'company_id'       => 'integer',
        'location_id'      => 'integer',
        'fsm_equipment_id' => 'integer',
        'active'           => 'boolean',
    ];

    public function location()
    {
        return $this->belongsTo(\Modules\FSMCore\Models\FSMLocation::class, 'location_id');
    }

    public function equipment()
    {
        return $this->belongsTo(\Modules\FSMCore\Models\FSMEquipment::class, 'fsm_equipment_id');
    }

    public function checkEvents()
    {
        return $this->hasMany(FSMEquipmentCheckEvent::class, 'register_id');
    }
}
