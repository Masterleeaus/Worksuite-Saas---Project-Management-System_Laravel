<?php

namespace Modules\Accountings\Observers;

use Illuminate\Support\Facades\Log;
use Modules\Accountings\Jobs\RecordPaymentLedgerJob;

/**
 * Observer for the core Payment model.
 *
 * Responsibilities:
 *  - Dispatch RecordPaymentLedgerJob (queued) when a payment is successfully created,
 *    so the accounting engine posts a ledger entry and emits an account.payment.received signal.
 */
class PaymentAccountingObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(mixed $payment): void
    {
        $this->dispatchLedgerRecord($payment);
    }

    /**
     * Handle the Payment "updated" event – re-dispatch only if status changed to complete.
     */
    public function updated(mixed $payment): void
    {
        if ($payment->isDirty('status') && $payment->status === 'complete') {
            $this->dispatchLedgerRecord($payment);
        }
    }

    private function dispatchLedgerRecord(mixed $payment): void
    {
        try {
            $companyId = $payment->company_id ?? null;
            if (!$companyId || !$payment->id) {
                return;
            }

            // Only process complete / captured payments.
            if (!in_array($payment->status ?? '', ['complete', 'captured', 'paid'], true)) {
                return;
            }

            RecordPaymentLedgerJob::dispatch((int) $payment->id, (int) $companyId);
        } catch (\Throwable $e) {
            Log::warning('[PaymentAccountingObserver] Could not dispatch RecordPaymentLedgerJob', [
                'payment_id' => $payment->id ?? null,
                'error'      => $e->getMessage(),
            ]);
        }
    }
}
