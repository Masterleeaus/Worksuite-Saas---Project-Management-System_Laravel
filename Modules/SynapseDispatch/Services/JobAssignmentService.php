<?php

namespace Modules\SynapseDispatch\Services;

use Modules\SynapseDispatch\Models\DispatchJob;
use Modules\SynapseDispatch\Models\DispatchWorker;
use Modules\SynapseDispatch\Models\DispatchEvent;
use Modules\SynapseDispatch\Enums\PlanningStatus;
use Modules\SynapseDispatch\Events\JobAssigned;

class JobAssignmentService
{
    /**
     * Assign a worker to a job, persist status changes, record an audit event,
     * and fire the JobAssigned broadcast event.
     */
    public function assign(DispatchJob $job, DispatchWorker $worker, string $source = 'PLANNER'): void
    {
        $job->update([
            'scheduled_primary_worker_id' => $worker->id,
            'scheduled_start_datetime'    => $job->requested_start_datetime,
            'scheduled_duration_minutes'  => $job->requested_duration_minutes,
            'planning_status'             => PlanningStatus::DISPATCHED,
        ]);

        DispatchEvent::create([
            'started_at'  => now(),
            'description' => "Job {$job->code} dispatched to worker {$worker->name} ({$worker->code}).",
            'job_id'      => $job->id,
            'worker_id'   => $worker->id,
            'source'      => $source,
        ]);

        event(new JobAssigned($job, $worker));
    }

    /**
     * Reschedule an already-assigned job to a new worker and/or time.
     */
    public function reschedule(DispatchJob $job, DispatchWorker $worker, \Carbon\Carbon $start): void
    {
        $job->update([
            'scheduled_primary_worker_id' => $worker->id,
            'scheduled_start_datetime'    => $start,
            'planning_status'             => PlanningStatus::DISPATCHED,
        ]);

        DispatchEvent::create([
            'started_at'  => now(),
            'description' => "Job {$job->code} rescheduled to worker {$worker->name} at {$start->toDateTimeString()}.",
            'job_id'      => $job->id,
            'worker_id'   => $worker->id,
            'source'      => 'MANUAL',
        ]);

        event(new JobAssigned($job, $worker));
    }
}
