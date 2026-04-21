<?php

namespace Modules\Accountings\Services;

use Illuminate\Support\Collection;
use Modules\Accountings\Entities\FinancialTransaction;

class TransactionAggregationService
{
    public function dailyRevenue(int $companyId): float
    {
        return (float) FinancialTransaction::query()->where('company_id', $companyId)->where('transaction_type', 'invoice_posted')->whereDate('occurred_at', now()->toDateString())->sum('amount');
    }

    public function marginBySite(int $companyId): Collection
    {
        return FinancialTransaction::query()->where('company_id', $companyId)->selectRaw('site_ref, SUM(amount) as total')->groupBy('site_ref')->orderByDesc('total')->get();
    }

    public function unreconciledPayments(int $companyId): int
    {
        return FinancialTransaction::query()->where('company_id', $companyId)->where('transaction_type', 'payment_received')->where('status', '!=', 'reconciled')->count();
    }
}
