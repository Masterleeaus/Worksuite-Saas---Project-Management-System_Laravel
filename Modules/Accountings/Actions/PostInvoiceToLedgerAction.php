<?php

namespace Modules\Accountings\Actions;

use App\Models\Invoice;
use Modules\Accountings\Services\RevenueRecognitionService;

class PostInvoiceToLedgerAction
{
    public function __construct(private readonly RevenueRecognitionService $revenueRecognitionService)
    {
    }

    public function execute(Invoice $invoice, int $companyId): void
    {
        $this->revenueRecognitionService->recognizeInvoice($invoice, $companyId);
    }
}
