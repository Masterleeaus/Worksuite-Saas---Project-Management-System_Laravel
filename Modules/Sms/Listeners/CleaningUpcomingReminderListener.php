<?php

namespace Modules\Sms\Listeners;

use Modules\Sms\Enums\SmsNotificationSlug;
use Modules\Sms\Events\CleaningUpcomingReminderEvent;
use Modules\Sms\Services\CleaningNotificationService;

class CleaningUpcomingReminderListener
{
    public function __construct(private CleaningNotificationService $notifier) {}

    public function handle(CleaningUpcomingReminderEvent $event): void
    {
        try {
            $order = $event->order;
            $notifiable = $this->resolveClient($order);

            if (!$notifiable) {
                return;
            }

            $scheduledTime = optional($order->scheduled_date_start)
                ? $order->scheduled_date_start->format('g:i A')
                : 'the scheduled time';

            $message = CleaningNotificationService::resolveTemplate(
                SmsNotificationSlug::CleaningUpcomingReminder,
                $order->company_id ?? null,
                [
                    'time' => $scheduledTime,
                ]
            );

            $this->notifier->send($notifiable, SmsNotificationSlug::CleaningUpcomingReminder, $message);
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
