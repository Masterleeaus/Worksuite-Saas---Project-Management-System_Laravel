<?php

namespace Modules\FSMRoute\Models;

use Illuminate\Database\Eloquent\Model;

class FSMWorkerLocationPing extends Model
{
    protected $table = 'fsm_worker_location_pings';

    protected $fillable = ['company_id', 'person_id', 'latitude', 'longitude', 'pinged_at'];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
        'pinged_at' => 'datetime',
    ];

    public function person()
    {
        return $this->belongsTo(\App\Models\User::class, 'person_id');
    }

    /**
     * Return the most recent ping for each worker (as a base query).
     * Usage: FSMWorkerLocationPing::latestPerWorker()->get()
     */
    public static function latestPerWorker()
    {
        return static::whereIn('id', function ($sub) {
            $sub->selectRaw('MAX(id)')
                ->from('fsm_worker_location_pings')
                ->groupBy('person_id');
        });
    }
}
