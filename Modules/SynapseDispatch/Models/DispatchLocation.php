<?php

namespace Modules\SynapseDispatch\Models;

use Illuminate\Database\Eloquent\Model;

class DispatchLocation extends Model
{
    protected $table = 'dispatch_locations';

    protected $fillable = [
        'location_code',
        'geo_longitude',
        'geo_latitude',
        'address',
    ];

    protected $casts = [
        'geo_longitude' => 'float',
        'geo_latitude'  => 'float',
    ];

    public function workers()
    {
        return $this->hasMany(DispatchWorker::class, 'location_id');
    }

    public function jobs()
    {
        return $this->hasMany(DispatchJob::class, 'location_id');
    }
}
