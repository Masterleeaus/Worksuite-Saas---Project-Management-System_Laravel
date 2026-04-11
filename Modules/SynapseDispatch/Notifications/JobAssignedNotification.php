<?php

namespace Modules\SynapseDispatch\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\SynapseDispatch\Models\DispatchJob;
use Modules\SynapseDispatch\Models\DispatchWorker;

class JobAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly DispatchJob    $job,
        public readonly DispatchWorker $worker
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'job_id'   => $this->job->id,
            'job_code' => $this->job->code,
            'job_name' => $this->job->name,
            'start'    => $this->job->scheduled_start_datetime?->toIso8601String(),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Job Assigned: ' . $this->job->code)
            ->line('You have been assigned job ' . $this->job->code . '.')
            ->line('Scheduled start: ' . ($this->job->scheduled_start_datetime?->toDateTimeString() ?? 'TBD'));
    }
}
