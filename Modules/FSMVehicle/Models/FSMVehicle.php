<?php

namespace Modules\FSMVehicle\Models;

use Illuminate\Database\Eloquent\Model;

class FSMVehicle extends Model
{
    protected $table = 'fsm_vehicles';

    protected $fillable = [
        'company_id',
        'name',
        'license_plate',
        'make',
        'model',
        'year',
        'vin',
        'person_id',
        'current_mileage',
        'last_service_date',
        'next_service_mileage',
        'notes',
        'active',
    ];

    protected $casts = [
        'company_id'           => 'integer',
        'person_id'            => 'integer',
        'year'                 => 'integer',
        'current_mileage'      => 'integer',
        'next_service_mileage' => 'integer',
        'active'               => 'boolean',
        'last_service_date'    => 'date',
    ];

    public function driver()
    {
        return $this->belongsTo(\App\Models\User::class, 'person_id');
    }

    public function mileageLogs()
    {
        return $this->hasMany(FSMVehicleMileageLog::class, 'vehicle_id');
    }

    public function orders()
    {
        return $this->hasMany(\Modules\FSMCore\Models\FSMOrder::class, 'vehicle_id');
    }

    /**
     * Returns true when the vehicle is within the service-alert buffer of its next service mileage.
     */
    public function isDueForService(): bool
    {
        if (!$this->next_service_mileage) {
            return false;
        }

        $buffer = (int) config('fsmvehicle.service_alert_buffer_km', 500);

        return $this->current_mileage >= ($this->next_service_mileage - $buffer);
    }
}
