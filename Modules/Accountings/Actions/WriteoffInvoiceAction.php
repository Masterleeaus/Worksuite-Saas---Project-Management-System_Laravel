<?php

namespace Modules\Accountings\Actions;

use App\Models\Invoice;
use Modules\Accountings\Services\WriteoffService;

class WriteoffInvoiceAction
{
    public function __construct(private readonly WriteoffService $writeoffService)
    {
    }

    public function execute(Invoice $invoice, int $companyId, float $amount, string $reason): Invoice
    {
        return $this->writeoffService->writeoff($invoice, $companyId, $amount, $reason);
    }
}
