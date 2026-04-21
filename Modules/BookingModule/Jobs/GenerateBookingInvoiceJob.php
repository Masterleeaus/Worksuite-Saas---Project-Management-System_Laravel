<?php

namespace Modules\BookingModule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\BookingModule\Models\CleaningBooking;
use Modules\BookingModule\Services\BookingAutoInvoiceService;

/**
 * GenerateBookingInvoiceJob
 *
 * Dispatched when a CleaningBooking reaches 'completed' status.
 * Delegates all logic to BookingAutoInvoiceService which is idempotent.
 */
class GenerateBookingInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of times the job may be attempted.
     * The underlying service is idempotent so retries are safe.
     */
    public int $tries = 3;

    /**
     * Seconds before the job is considered timed out.
     */
    public int $timeout = 60;

    public function __construct(
        public readonly int $bookingId,
        public readonly int $companyId,
    ) {}

    public function handle(BookingAutoInvoiceService $service): void
    {
        $booking = CleaningBooking::find($this->bookingId);

        if (!$booking) {
            return; // booking deleted — safe to discard
        }

        // Already invoiced — idempotency guard (service also checks, but short-circuit here)
        if ($booking->invoice_generated || $booking->generated_invoice_id !== null) {
            return;
        }

        $service->generateForBooking($booking);
    }

    /**
     * Unique job ID to prevent duplicate queuing for the same booking.
     */
    public function uniqueId(): string
    {
        return 'generate-invoice-' . $this->bookingId;
    }
}
