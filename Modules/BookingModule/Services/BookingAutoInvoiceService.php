<?php

namespace Modules\BookingModule\Services;

use App\Models\Invoice;
use Illuminate\Support\Carbon;
use Modules\BookingModule\Models\CleaningBooking;

/**
 * BookingAutoInvoiceService
 *
 * Generates a Worksuite-core Invoice when a CleaningBooking reaches the
 * 'completed' status.  A guard on `invoice_generated` prevents double-invoicing.
 */
class BookingAutoInvoiceService
{
    /**
     * Attempt to auto-generate an invoice for a completed booking.
     *
     * Idempotent — calling this more than once on the same booking is safe.
     *
     * @return Invoice|null  The newly created invoice, or null if already generated.
     */
    public function generateForBooking(CleaningBooking $booking): ?Invoice
    {
        // Guard: never create a second invoice.
        if ($booking->invoice_generated || $booking->generated_invoice_id !== null) {
            return null;
        }

        if ($booking->booking_status !== 'completed') {
            return null;
        }

        $invoice = Invoice::create([
            'client_id'   => $booking->project?->client_id,
            'project_id'  => $booking->project_id,
            'issue_date'  => Carbon::now(),
            'due_date'    => Carbon::now()->addDays(14),
            'status'      => 'unpaid',
            'note'        => 'Auto-generated on cleaning job completion — Booking #' . ($booking->booking_id ?? $booking->id),
            'added_by'    => $booking->added_by ?? $booking->created_by,
        ]);

        // Link the invoice back to the booking atomically.
        $booking->invoice_generated    = true;
        $booking->generated_invoice_id = $invoice->id;
        $booking->saveQuietly();

        return $invoice;
    }
}
