<?php

namespace Modules\SynapseDispatch\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\SynapseDispatch\Models\DispatchJob;
use Modules\SynapseDispatch\Services\HeuristicPlannerService;
use Modules\SynapseDispatch\Services\JobAssignmentService;

class DispatchJobAssignment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public readonly DispatchJob $job) {}

    public function handle(HeuristicPlannerService $planner, JobAssignmentService $assigner): void
    {
        // Reload fresh copy to avoid stale data after queue delay
        $job = $this->job->fresh();

        if (!$job) {
            return; // job deleted before queue processed
        }

        $worker = $planner->plan($job);

        if ($worker) {
            $assigner->assign($job, $worker, 'SYSTEM');
        }
    }
}
