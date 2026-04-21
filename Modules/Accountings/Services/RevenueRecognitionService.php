<?php

namespace Modules\Accountings\Services;

use App\Models\Invoice;
use Modules\Accountings\Events\InvoiceIssued;

class RevenueRecognitionService
{
    public function __construct(private readonly LedgerPostingService $ledgerPostingService)
    {
    }

    public function recognizeInvoice(Invoice $invoice, int $companyId): void
    {
        $this->ledgerPostingService->post([
            'company_id' => $companyId,
            'invoice_id' => $invoice->id,
            'transaction_type' => 'invoice_posted',
            'reference' => $invoice->invoice_number,
            'amount' => (float) $invoice->total,
            'debit_account_code' => 'AR',
            'credit_account_code' => 'REV',
            'currency_id' => $invoice->currency_id,
            'occurred_at' => $invoice->issue_date ?? now(),
        ]);

        event(new InvoiceIssued($companyId, ['invoice_id' => $invoice->id, 'invoice_number' => $invoice->invoice_number]));
    }
}
