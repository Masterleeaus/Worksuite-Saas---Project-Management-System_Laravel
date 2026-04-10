<?php

namespace Modules\FSMRoute\Services;

use Modules\FSMCore\Models\FSMOrder;
use App\Models\User;

/**
 * Sends ETA / status SMS notifications for FSMRoute dispatching.
 * Guards against the Sms module being absent.
 */
class ETANotificationService
{
    /**
     * Notify the client that a worker is en route to their job.
     */
    public function notifyEnRoute(FSMOrder $order): void
    {
        $this->send($order, 'cleaner-dispatched', function (User $worker, FSMOrder $order) {
            return "Your worker {$worker->name} is now on the way to your job ({$order->name}). We'll see you soon!";
        });
    }

    /**
     * Notify the client that the worker has checked in at the job site.
     */
    public function notifyCheckIn(FSMOrder $order): void
    {
        $this->send($order, 'cleaner-checked-in', function (User $worker, FSMOrder $order) {
            return "Your worker {$worker->name} has arrived and checked in for job {$order->name}.";
        });
    }

    /**
     * Notify the client that the job has been completed.
     */
    public function notifyComplete(FSMOrder $order): void
    {
        $this->send($order, 'cleaning-job-complete', function (User $worker, FSMOrder $order) {
            return "Job {$order->name} has been completed by {$worker->name}. Thank you for choosing us!";
        });
    }

    /**
     * Resolve the order's client (via location.partner_id), then send SMS if the
     * Sms module is available and the notification is enabled.
     */
    private function send(FSMOrder $order, string $slug, callable $messageFactory): void
    {
        if (!class_exists(\Modules\Sms\Services\CleaningNotificationService::class)) {
            return;
        }

        if (!class_exists(\Modules\Sms\Enums\SmsNotificationSlug::class)) {
            return;
        }

        $notificationSlug = \Modules\Sms\Enums\SmsNotificationSlug::tryFrom($slug);
        if (!$notificationSlug) {
            return;
        }

        // Resolve worker
        $worker = $order->person_id ? User::find($order->person_id) : null;
        if (!$worker) {
            return;
        }

        // Resolve client via order location partner
        $client = null;
        if ($order->location && $order->location->partner_id) {
            $client = User::find($order->location->partner_id);
        }

        if (!$client) {
            return;
        }

        $message = $messageFactory($worker, $order);

        app(\Modules\Sms\Services\CleaningNotificationService::class)
            ->send($client, $notificationSlug, $message);
    }
}
