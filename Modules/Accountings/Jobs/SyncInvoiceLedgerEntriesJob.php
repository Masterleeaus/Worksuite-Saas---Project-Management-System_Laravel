<?php

namespace Modules\Accountings\Jobs;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Accountings\Actions\PostInvoiceToLedgerAction;

class SyncInvoiceLedgerEntriesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly int $invoiceId, public readonly int $companyId)
    {
    }

    public function handle(PostInvoiceToLedgerAction $postInvoiceToLedgerAction): void
    {
        $invoice = Invoice::query()->where('company_id', $this->companyId)->findOrFail($this->invoiceId);
        $postInvoiceToLedgerAction->execute($invoice, $this->companyId);
    }
}
