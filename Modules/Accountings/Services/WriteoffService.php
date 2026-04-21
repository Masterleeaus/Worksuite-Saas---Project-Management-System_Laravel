<?php

namespace Modules\Accountings\Services;

use App\Models\Invoice;
use Modules\Accountings\Events\InvoiceWrittenOff;

class WriteoffService
{
    public function __construct(private readonly LedgerPostingService $ledgerPostingService, private readonly AccountingSignalService $signalService)
    {
    }

    public function writeoff(Invoice $invoice, int $companyId, float $amount, string $reason): Invoice
    {
        $amount = min((float) $invoice->due_amount, abs($amount));
        $invoice->due_amount = max(0, (float) $invoice->due_amount - $amount);
        if ((float) $invoice->due_amount === 0.0) {
            $invoice->status = 'paid';
        }
        $invoice->save();

        $this->ledgerPostingService->post([
            'company_id' => $companyId,
            'invoice_id' => $invoice->id,
            'transaction_type' => 'invoice_writeoff',
            'reference' => $invoice->invoice_number,
            'amount' => $amount,
            'debit_account_code' => 'WRITE_OFF_EXPENSE',
            'credit_account_code' => 'AR',
            'meta' => ['reason' => $reason],
        ]);

        event(new InvoiceWrittenOff($companyId, ['invoice_id' => $invoice->id, 'amount' => $amount, 'reason' => $reason]));

        $this->signalService->emit('account.invoice.adjusted', $companyId, ['invoice_id' => $invoice->id, 'writeoff_amount' => $amount, 'reason' => $reason]);

        return $invoice;
    }
}
