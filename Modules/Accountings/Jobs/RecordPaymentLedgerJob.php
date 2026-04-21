<?php

namespace Modules\Accountings\Jobs;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Accountings\Actions\RecordPaymentAction;

/**
 * Queued job that records an individual payment into the financial ledger.
 *
 * Dispatched by PaymentAccountingObserver on payment.created / status→complete.
 */
class RecordPaymentLedgerJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 60;
    /** @var int[] */
    public array $backoff = [30, 120, 300];

    public function __construct(
        public readonly int $paymentId,
        public readonly int $companyId,
    ) {
    }

    public function handle(RecordPaymentAction $recordPaymentAction): void
    {
        $payment = Payment::query()
            ->where('company_id', $this->companyId)
            ->find($this->paymentId);

        if (!$payment) {
            return; // payment may have been deleted; silently skip
        }

        $recordPaymentAction->execute($payment, $this->companyId);
    }

    /** Unique job key: one pending ledger record per payment per company. */
    public function uniqueId(): string
    {
        return "record_payment_{$this->companyId}_{$this->paymentId}";
    }
}
