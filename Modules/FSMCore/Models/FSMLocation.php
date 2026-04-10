<?php

namespace Modules\FSMCore\Models;

use Illuminate\Database\Eloquent\Model;

class FSMLocation extends Model
{
    protected $table = 'fsm_locations';

    protected $fillable = [
        'company_id',
        'name',
        'partner_id',
        'territory_id',
        'street',
        'city',
        'state',
        'zip',
        'country',
        'latitude',
        'longitude',
        'notes',
        'active',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'active' => 'boolean',
        'company_id' => 'integer',
        'partner_id' => 'integer',
        'territory_id' => 'integer',
    ];

    public function territory()
    {
        return $this->belongsTo(FSMTerritory::class, 'territory_id');
    }

    public function orders()
    {
        return $this->hasMany(FSMOrder::class, 'location_id');
    }

    public function equipment()
    {
        return $this->hasMany(FSMEquipment::class, 'location_id');
    }
}
