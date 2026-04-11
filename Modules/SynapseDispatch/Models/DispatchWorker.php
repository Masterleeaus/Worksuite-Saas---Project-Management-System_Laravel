<?php

namespace Modules\SynapseDispatch\Models;

use Illuminate\Database\Eloquent\Model;

class DispatchWorker extends Model
{
    protected $table = 'dispatch_workers';

    protected $fillable = [
        'code',
        'name',
        'is_active',
        'team_id',
        'location_id',
        'skills',
        'business_hour',
        'flex_form_data',
        'worksuite_user_id',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'skills'         => 'array',
        'business_hour'  => 'array',
        'flex_form_data' => 'array',
    ];

    public function team()
    {
        return $this->belongsTo(DispatchTeam::class, 'team_id');
    }

    public function location()
    {
        return $this->belongsTo(DispatchLocation::class, 'location_id');
    }

    public function worksuite_user()
    {
        return $this->belongsTo(\App\Models\User::class, 'worksuite_user_id');
    }

    public function scheduledJobs()
    {
        return $this->hasMany(DispatchJob::class, 'scheduled_primary_worker_id');
    }

    public function secondaryJobs()
    {
        return $this->belongsToMany(
            DispatchJob::class,
            'dispatch_job_secondary_workers',
            'worker_id',
            'job_id'
        );
    }

    public function events()
    {
        return $this->hasMany(DispatchEvent::class, 'worker_id');
    }
}
