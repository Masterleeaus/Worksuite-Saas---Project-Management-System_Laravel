<?php

namespace Modules\ClientPulse\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\Sms\Events\CleaningJobCompleteEvent;
use Modules\ClientPulse\Mail\RatingPromptMail;

/**
 * Listens for the CleaningJobCompleteEvent (fired by the Sms module observer
 * when an FSMOrder stage is set to a completion stage) and sends the client
 * a rating prompt email (and optionally an SMS).
 *
 * This listener is registered in EventServiceProvider only when the
 * Sms module is available.
 */
class SendPostJobRatingPrompt implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(CleaningJobCompleteEvent $event): void
    {
        $order = $event->order;

        if (!$order || !isset($order->location)) {
            return;
        }

        try {
            $location = $order->location;

            if (!$location) {
                return;
            }

            // Resolve the client user from the location's partner_id
            $client = \App\Models\User::find($location->partner_id ?? null);

            if (!$client || !$client->email) {
                return;
            }

            // Build the rating URL
            $ratingUrl = route('clientpulse.portal.rating.show', $order->id);

            // Send the rating prompt email
            if (config('clientpulse.send_rating_email', true)) {
                Mail::to($client->email)->send(new RatingPromptMail($order, $client, $ratingUrl));
            }

            // Send rating prompt via SMS if the Sms module is available
            if (config('clientpulse.send_rating_sms', true)
                && class_exists(\Modules\Sms\Services\CleaningNotificationService::class)
            ) {
                $this->sendRatingSms($client, $ratingUrl, $order);
            }
        } catch (\Throwable $e) {
            // Never let the listener break the job completion flow
        }
    }

    /**
     * Use the Sms module's CleaningNotificationService to send the rating link via SMS.
     */
    private function sendRatingSms($client, string $ratingUrl, $order): void
    {
        try {
            /** @var \Modules\Sms\Services\CleaningNotificationService $svc */
            $svc = app(\Modules\Sms\Services\CleaningNotificationService::class);

            if (method_exists($svc, 'sendRatingPrompt')) {
                $svc->sendRatingPrompt($client, $order, $ratingUrl);
            }
        } catch (\Throwable $e) {
            // SMS send failure is non-fatal
        }
    }
}
