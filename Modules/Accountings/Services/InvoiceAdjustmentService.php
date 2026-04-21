<?php

namespace Modules\Accountings\Services;

use App\Models\Invoice;
use Modules\Accountings\Events\InvoiceAdjusted;

class InvoiceAdjustmentService
{
    public function __construct(private readonly LedgerPostingService $ledgerPostingService)
    {
    }

    public function adjust(Invoice $invoice, int $companyId, float $amount, string $reason): Invoice
    {
        $invoice->total = max(0, (float) $invoice->total + $amount);
        $invoice->due_amount = max(0, (float) $invoice->due_amount + $amount);
        $invoice->save();

        $this->ledgerPostingService->post([
            'company_id' => $companyId,
            'invoice_id' => $invoice->id,
            'transaction_type' => 'invoice_adjusted',
            'reference' => $invoice->invoice_number,
            'amount' => abs($amount),
            'debit_account_code' => $amount >= 0 ? 'AR' : 'REV',
            'credit_account_code' => $amount >= 0 ? 'REV' : 'AR',
            'meta' => ['reason' => $reason],
        ]);

        event(new InvoiceAdjusted($companyId, ['invoice_id' => $invoice->id, 'amount' => $amount, 'reason' => $reason]));

        return $invoice;
    }
}
