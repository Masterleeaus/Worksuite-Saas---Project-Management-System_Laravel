<?php

namespace Modules\Sms\Listeners;

use Modules\Sms\Enums\SmsNotificationSlug;
use Modules\Sms\Events\CleanerCheckedInEvent;
use Modules\Sms\Services\CleaningNotificationService;

class CleanerCheckedInListener
{
    public function __construct(private CleaningNotificationService $notifier) {}

    public function handle(CleanerCheckedInEvent $event): void
    {
        try {
            $order = $event->order;
            $notifiable = $this->resolveClient($order);

            if (!$notifiable) {
                return;
            }

            $address = optional($order->location)->address ?? 'the service address';
            $time = $event->checkedInAt
                ?? now()->format('g:i A');

            $message = CleaningNotificationService::resolveTemplate(
                SmsNotificationSlug::CleanerCheckedIn,
                $order->company_id ?? null,
                [
                    'address' => $address,
                    'time'    => $time,
                ]
            );

            $this->notifier->send($notifiable, SmsNotificationSlug::CleanerCheckedIn, $message);
        } catch (\Throwable $e) {
            // fail silently
        }
    }

    private function resolveClient($order): ?object
    {
        if (!empty($order->location) && !empty($order->location->person_id)) {
            return \App\Models\User::find($order->location->person_id);
        }

        if (!empty($order->person_id)) {
            return \App\Models\User::find($order->person_id);
        }

        return null;
    }
}
