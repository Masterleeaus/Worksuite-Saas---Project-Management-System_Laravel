<?php

namespace Modules\CustomerFeedback\Observers;

use Illuminate\Support\Facades\Log;
use Modules\CustomerFeedback\Services\NpsSurveyService;

/**
 * Observes the BookingModule Booking model.
 *
 * When booking_status transitions to 'completed', a post-service NPS survey
 * is automatically dispatched to the booking's client.
 *
 * Registered from CustomerFeedbackServiceProvider only when BookingModule is installed.
 */
class BookingObserver
{
    public function updated($booking): void
    {
        try {
            if (!$booking->wasChanged('booking_status')) {
                return;
            }

            if ($booking->booking_status !== 'completed') {
                return;
            }

            $clientId  = $booking->customer_id ?? $booking->user_id ?? null;
            $bookingId = $booking->id ?? null;

            if ($clientId === null) {
                return;
            }

            /** @var NpsSurveyService $service */
            $service = app(NpsSurveyService::class);
            $service->dispatchSurvey((int) $clientId, $bookingId !== null ? (int) $bookingId : null);
        } catch (\Throwable $e) {
            // Observer must never break booking updates
            Log::warning('CustomerFeedback: BookingObserver failed to dispatch survey', [
                'booking_id' => $booking->id ?? null,
                'error'      => $e->getMessage(),
            ]);
        }
    }
}
