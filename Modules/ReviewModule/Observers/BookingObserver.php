<?php

namespace Modules\ReviewModule\Observers;

use Illuminate\Support\Facades\Log;
use Modules\ReviewModule\Services\ReviewRequestService;

/**
 * Observes Booking model updates. When a booking transitions to
 * 'completed' status, schedules a review request 2 hours later.
 *
 * The actual 2-hour delay is handled by the `reviews:send-requests`
 * console command. This observer marks the booking as needing a request
 * by triggering an immediate review record creation only when the
 * delayed command approach is not available (i.e., it does nothing
 * at update time — the command polls for completed bookings in the window).
 *
 * However, for immediate use cases (e.g., testing), this observer also
 * optionally dispatches directly if REVIEW_REQUEST_IMMEDIATE=true in .env.
 *
 * @see \Modules\ReviewModule\Console\SendReviewRequestsCommand
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

            // Only send immediately if env flag is set; otherwise the command handles the 2h delay
            if (config('reviewmodule.immediate_review_request', false)) {
                /** @var ReviewRequestService $service */
                $service = app(ReviewRequestService::class);
                $service->createForBooking($booking);
            }
        } catch (\Throwable $e) {
            Log::warning('ReviewModule BookingObserver error: ' . $e->getMessage());
        }
    }
}
