<?php

namespace Modules\Accountings\Actions;

use App\Models\Invoice;
use Modules\Accountings\Services\InvoiceAdjustmentService;

class AdjustInvoiceAction
{
    public function __construct(private readonly InvoiceAdjustmentService $invoiceAdjustmentService)
    {
    }

    public function execute(Invoice $invoice, int $companyId, float $amount, string $reason): Invoice
    {
        return $this->invoiceAdjustmentService->adjust($invoice, $companyId, $amount, $reason);
    }
}
