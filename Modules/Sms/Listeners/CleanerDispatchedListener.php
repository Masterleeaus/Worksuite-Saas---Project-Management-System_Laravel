<?php

namespace Modules\Sms\Listeners;

use Modules\Sms\Enums\SmsNotificationSlug;
use Modules\Sms\Events\CleanerDispatchedEvent;
use Modules\Sms\Services\CleaningNotificationService;

class CleanerDispatchedListener
{
    public function __construct(private CleaningNotificationService $notifier) {}

    public function handle(CleanerDispatchedEvent $event): void
    {
        try {
            $order = $event->order;

            // The client/notifiable is the person linked to the order's location
            $notifiable = $this->resolveClient($order);

            if (!$notifiable) {
                return;
            }

            $cleanerName = optional($event->cleaner)->name
                ?? optional($order->person)->name
                ?? 'your cleaner';

            $eta = $event->etaMinutes ?? '?';

            $clientName = $notifiable->name ?? 'Customer';

            $message = CleaningNotificationService::resolveTemplate(
                SmsNotificationSlug::CleanerDispatched,
                $order->company_id ?? null,
                [
                    'client'      => $clientName,
                    'cleanerName' => $cleanerName,
                    'eta'         => $eta,
                ]
            );

            $this->notifier->send($notifiable, SmsNotificationSlug::CleanerDispatched, $message);
        } catch (\Throwable $e) {
            // Never crash the request
        }
    }

    private function resolveClient($order): ?object
    {
        // Try location → person (client contact)
        if (!empty($order->location) && !empty($order->location->person_id)) {
            return \App\Models\User::find($order->location->person_id);
        }

        // Fall back to person assigned directly on order if it is a client
        if (!empty($order->person_id)) {
            $user = \App\Models\User::find($order->person_id);
            return $user;
        }

        return null;
    }
}
