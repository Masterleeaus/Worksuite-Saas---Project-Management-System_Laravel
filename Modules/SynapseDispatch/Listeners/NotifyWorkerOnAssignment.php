<?php

namespace Modules\SynapseDispatch\Listeners;

use Modules\SynapseDispatch\Events\JobAssigned;

class NotifyWorkerOnAssignment
{
    public function handle(JobAssigned $event): void
    {
        $job    = $event->job;
        $worker = $event->worker;

        // Integrate with Worksuite Sms module if available (cleaner-dispatched slug).
        if (class_exists(\Modules\Sms\Services\CleaningNotificationService::class)
            && \Illuminate\Support\Facades\Schema::hasTable('sms_settings')
        ) {
            try {
                /** @var \Modules\Sms\Services\CleaningNotificationService $sms */
                $sms = app(\Modules\Sms\Services\CleaningNotificationService::class);
                if (method_exists($sms, 'notifyWorkerDispatched')) {
                    $sms->notifyWorkerDispatched($worker->worksuite_user_id, $job);
                }
            } catch (\Throwable) {
                // Non-fatal — SMS failure must not block dispatch
            }
        }

        // Laravel database notification fallback
        if ($worker->worksuite_user_id) {
            try {
                $user = \App\Models\User::find($worker->worksuite_user_id);
                if ($user) {
                    $user->notify(new \Modules\SynapseDispatch\Notifications\JobAssignedNotification($job, $worker));
                }
            } catch (\Throwable) {
                // Non-fatal
            }
        }
    }
}
