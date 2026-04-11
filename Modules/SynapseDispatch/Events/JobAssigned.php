<?php

namespace Modules\SynapseDispatch\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\SynapseDispatch\Models\DispatchJob;
use Modules\SynapseDispatch\Models\DispatchWorker;

class JobAssigned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly DispatchJob    $job,
        public readonly DispatchWorker $worker
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('dispatch.team.' . $this->job->team_id);
    }

    public function broadcastAs(): string
    {
        return 'job.assigned';
    }

    public function broadcastWith(): array
    {
        return [
            'job_id'      => $this->job->id,
            'job_code'    => $this->job->code,
            'worker_id'   => $this->worker->id,
            'worker_name' => $this->worker->name,
            'start'       => $this->job->scheduled_start_datetime?->toIso8601String(),
        ];
    }
}
