<?php

namespace Modules\Sms\Listeners;

use Modules\Sms\Enums\SmsNotificationSlug;
use Modules\Sms\Events\CleaningJobCompleteEvent;
use Modules\Sms\Services\CleaningNotificationService;

class CleaningJobCompleteListener
{
    public function __construct(private CleaningNotificationService $notifier) {}

    public function handle(CleaningJobCompleteEvent $event): void
    {
        try {
            $order = $event->order;
            $notifiable = $this->resolveClient($order);

            if (!$notifiable) {
                return;
            }

            $address = optional($order->location)->address ?? 'the service address';
            $link = $event->feedbackLink ?? url('/feedback/' . $order->id);

            $message = CleaningNotificationService::resolveTemplate(
                SmsNotificationSlug::CleaningJobComplete,
                $order->company_id ?? null,
                [
                    'address' => $address,
                    'link'    => $link,
                ]
            );

            $this->notifier->send($notifiable, SmsNotificationSlug::CleaningJobComplete, $message);
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
