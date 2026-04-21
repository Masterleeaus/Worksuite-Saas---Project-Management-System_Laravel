<?php

namespace Modules\Accountings\Services;

class DeferredRevenueService
{
    public function __construct(private readonly LedgerPostingService $ledgerPostingService)
    {
    }

    public function postDeferredRevenue(int $companyId, array $payload): void
    {
        $this->ledgerPostingService->post([
            'company_id' => $companyId,
            'transaction_type' => 'deferred_revenue_posted',
            'reference' => $payload['reference'] ?? null,
            'service_agreement_ref' => $payload['service_agreement_ref'] ?? null,
            'amount' => (float) ($payload['amount'] ?? 0),
            'debit_account_code' => 'CASH',
            'credit_account_code' => 'DEFERRED_REVENUE',
            'meta' => $payload,
        ]);
    }
}
