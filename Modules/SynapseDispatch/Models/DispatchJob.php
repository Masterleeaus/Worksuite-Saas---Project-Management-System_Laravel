<?php

namespace Modules\SynapseDispatch\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\SynapseDispatch\Enums\PlanningStatus;
use Modules\SynapseDispatch\Enums\LifeCycleStatus;

class DispatchJob extends Model
{
    protected $table = 'dispatch_jobs';

    protected $fillable = [
        'code',
        'job_type',
        'name',
        'description',
        'planning_status',
        'life_cycle_status',
        'auto_planning',
        'team_id',
        'requested_start_datetime',
        'requested_duration_minutes',
        'scheduled_start_datetime',
        'scheduled_duration_minutes',
        'requested_primary_worker_id',
        'scheduled_primary_worker_id',
        'location_id',
        'flex_form_data',
        'worksuite_project_id',
    ];

    protected $casts = [
        'auto_planning'              => 'boolean',
        'requested_start_datetime'   => 'datetime',
        'scheduled_start_datetime'   => 'datetime',
        'requested_duration_minutes' => 'float',
        'scheduled_duration_minutes' => 'float',
        'flex_form_data'             => 'array',
        'planning_status'            => PlanningStatus::class,
        'life_cycle_status'          => LifeCycleStatus::class,
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $job) {
            if (empty($job->code)) {
                $job->code = 'JOB-' . str_pad((self::max('id') ?? 0) + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function team()
    {
        return $this->belongsTo(DispatchTeam::class, 'team_id');
    }

    public function location()
    {
        return $this->belongsTo(DispatchLocation::class, 'location_id');
    }

    public function requestedPrimaryWorker()
    {
        return $this->belongsTo(DispatchWorker::class, 'requested_primary_worker_id');
    }

    public function scheduledPrimaryWorker()
    {
        return $this->belongsTo(DispatchWorker::class, 'scheduled_primary_worker_id');
    }

    public function secondaryWorkers()
    {
        return $this->belongsToMany(
            DispatchWorker::class,
            'dispatch_job_secondary_workers',
            'job_id',
            'worker_id'
        );
    }

    public function events()
    {
        return $this->hasMany(DispatchEvent::class, 'job_id');
    }

    public function scheduledEndDatetime(): ?\Carbon\Carbon
    {
        if ($this->scheduled_start_datetime && $this->scheduled_duration_minutes) {
            return $this->scheduled_start_datetime->copy()->addMinutes((int) $this->scheduled_duration_minutes);
        }
        return null;
    }
}
