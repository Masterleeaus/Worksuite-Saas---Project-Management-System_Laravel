<?php

namespace Modules\Accountings\Workflows;

use App\Models\Invoice;
use App\Models\Payment;
use Modules\Accountings\Actions\FinalizeJobCostAction;
use Modules\Accountings\Actions\PostInvoiceToLedgerAction;
use Modules\Accountings\Actions\PostVisitCostAction;
use Modules\Accountings\Actions\RecordPaymentAction;
use Modules\Accountings\Jobs\RecalculateContractProfitabilityJob;

class AccountingWorkflowOrchestrator
{
    public function __construct(
        private readonly PostVisitCostAction $postVisitCostAction,
        private readonly FinalizeJobCostAction $finalizeJobCostAction,
        private readonly PostInvoiceToLedgerAction $postInvoiceToLedgerAction,
        private readonly RecordPaymentAction $recordPaymentAction,
    ) {
    }

    public function visitCompleted(array $payload): void
    {
        $visitCost = $this->postVisitCostAction->execute($payload);
        $this->finalizeJobCostAction->execute($visitCost);
    }

    public function invoiceIssued(Invoice $invoice, int $companyId): void
    {
        $this->postInvoiceToLedgerAction->execute($invoice, $companyId);
    }

    public function paymentReceived(Payment $payment, int $companyId): void
    {
        $this->recordPaymentAction->execute($payment, $companyId);
    }

    public function contractCycleClosed(int $companyId, string $contractRef): void
    {
        RecalculateContractProfitabilityJob::dispatch($companyId, $contractRef);
    }
}
