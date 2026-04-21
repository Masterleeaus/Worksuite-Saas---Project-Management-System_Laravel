<?php

namespace Modules\BookingModule\Listeners;

use Modules\BookingModule\Events\BookingCompleted;
use Modules\BookingModule\Jobs\EmitBookingCompletedSignalJob;
use Modules\BookingModule\Jobs\GenerateBookingInvoiceJob;
use Modules\BookingModule\Jobs\SyncDispatchBoardJob;
use Modules\BookingModule\Services\AppointmentSettingsService;

/**
 * BookingCompletedListener
 *
 * Reacts to the BookingCompleted domain event by dispatching downstream
 * queue-safe jobs.  Each job is gated by its corresponding automation setting
 * so companies can opt out of invoice auto-generation or dispatch sync.
 */
class BookingCompletedListener
{
    public function __construct(protected AppointmentSettingsService $settingsService) {}

    public function handle(BookingCompleted $event): void
    {
        $booking   = $event->booking;
        $companyId = (int) ($booking->company_id ?? 0);

        // 1. Auto-generate invoice (idempotent service — safe to retry)
        $autoInvoice = $this->setting('automation.invoice.auto_generate', $companyId, true);
        if ($autoInvoice) {
            GenerateBookingInvoiceJob::dispatch($booking->id, $companyId)
                ->onQueue('default');
        }

        // 2. Refresh dispatch board cache
        $syncDispatch = $this->setting('automation.dispatch.sync_on_complete', $companyId, true);
        if ($syncDispatch) {
            SyncDispatchBoardJob::dispatch($companyId, null)
                ->onQueue('default');
        }

        // 3. Emit automation signal (no-op if no adapters configured — always fires)
        EmitBookingCompletedSignalJob::dispatch($booking->id, $companyId, [
            'booking_status' => $booking->booking_status,
            'service_type'   => $booking->service_type,
        ])->onQueue('default');
    }

    /**
     * Read a boolean automation setting for a specific company with config/hard-coded fallback.
     */
    private function setting(string $key, int $companyId, bool $default): bool
    {
        $value = $companyId > 0
            ? $this->settingsService->getForCompany($key, $companyId)
            : $this->settingsService->get($key);

        if ($value !== null) {
            return (bool) $value;
        }

        return $default;
    }
}
