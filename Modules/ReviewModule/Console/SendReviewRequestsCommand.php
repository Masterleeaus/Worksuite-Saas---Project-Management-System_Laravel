<?php

namespace Modules\ReviewModule\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\ReviewModule\Services\ReviewRequestService;

/**
 * Sends review request notifications to customers whose bookings were
 * completed 2 hours ago and have not yet received a review request.
 *
 * Schedule this command every 15 minutes:
 *   php artisan reviews:send-requests
 */
class SendReviewRequestsCommand extends Command
{
    protected $signature = 'reviews:send-requests';

    protected $description = 'Send review request emails/SMS to customers 2 hours after booking completion';

    public function handle(ReviewRequestService $service): int
    {
        if (!class_exists(\Modules\BookingModule\Entities\Booking::class)) {
            $this->warn('BookingModule not installed — skipping review requests.');
            return self::SUCCESS;
        }

        $delayHours  = (int) config('reviewmodule.request_delay_hours', 2);
        $windowStart = Carbon::now()->subHours($delayHours + 1);
        $windowEnd   = Carbon::now()->subHours($delayHours);

        $bookings = \Modules\BookingModule\Entities\Booking::query()
            ->where('booking_status', 'completed')
            ->whereBetween('updated_at', [$windowStart, $windowEnd])
            ->whereDoesntHave('reviews', function ($q) {
                $q->whereNotNull('review_token');
            })
            ->with(['customer', 'details'])
            ->get();

        $this->info("Found {$bookings->count()} booking(s) eligible for review requests.");

        foreach ($bookings as $booking) {
            $review = $service->createForBooking($booking);
            if ($review) {
                $this->line("  → Review request sent for booking {$booking->readable_id}");
            }
        }

        return self::SUCCESS;
    }
}
