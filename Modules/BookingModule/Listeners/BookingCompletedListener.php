<?php

namespace Modules\BookingModule\Listeners;

use Modules\BookingModule\Events\BookingCompleted;
use Modules\BookingModule\Jobs\EmitBookingCompletedSignalJob;
use Modules\BookingModule\Jobs\GenerateBookingInvoiceJob;
use Modules\BookingModule\Jobs\SyncDispatchBoardJob;

/**
 * BookingCompletedListener
 *
 * Reacts to the BookingCompleted domain event by dispatching downstream
 * queue-safe jobs.  This keeps the event handler thin and decouples the
 * trigger from execution.
 */
class BookingCompletedListener
{
    public function handle(BookingCompleted $event): void
    {
        $booking   = $event->booking;
        $companyId = $booking->company_id ?? 0;

        // 1. Auto-generate invoice (idempotent service — safe to retry)
        GenerateBookingInvoiceJob::dispatch($booking->id, $companyId)
            ->onQueue('default');

        // 2. Refresh dispatch board cache
        SyncDispatchBoardJob::dispatch($companyId, null)
            ->onQueue('default');

        // 3. Emit automation signal (no-op if no adapters configured)
        EmitBookingCompletedSignalJob::dispatch($booking->id, $companyId, [
            'booking_status' => $booking->booking_status,
            'service_type'   => $booking->service_type,
        ])->onQueue('default');
    }
}
