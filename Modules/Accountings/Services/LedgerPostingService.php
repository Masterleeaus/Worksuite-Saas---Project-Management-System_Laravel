<?php

namespace Modules\Accountings\Services;

use Modules\Accountings\Entities\FinancialTransaction;

class LedgerPostingService
{
    public function __construct(private readonly AccountingSignalService $signalService)
    {
    }

    public function post(array $attributes): FinancialTransaction
    {
        $transaction = FinancialTransaction::query()->create([
            'company_id' => $attributes['company_id'],
            'user_id' => $attributes['user_id'] ?? auth()->id(),
            'invoice_id' => $attributes['invoice_id'] ?? null,
            'payment_id' => $attributes['payment_id'] ?? null,
            'visit_cost_id' => $attributes['visit_cost_id'] ?? null,
            'reversed_transaction_id' => $attributes['reversed_transaction_id'] ?? null,
            'transaction_type' => $attributes['transaction_type'],
            'reference' => $attributes['reference'] ?? null,
            'visit_ref' => $attributes['visit_ref'] ?? null,
            'job_ref' => $attributes['job_ref'] ?? null,
            'site_ref' => $attributes['site_ref'] ?? null,
            'contract_ref' => $attributes['contract_ref'] ?? null,
            'service_agreement_ref' => $attributes['service_agreement_ref'] ?? null,
            'debit_account_code' => $attributes['debit_account_code'] ?? null,
            'credit_account_code' => $attributes['credit_account_code'] ?? null,
            'amount' => $attributes['amount'],
            'currency_id' => $attributes['currency_id'] ?? null,
            'occurred_at' => $attributes['occurred_at'] ?? now(),
            'status' => $attributes['status'] ?? 'posted',
            'meta' => $attributes['meta'] ?? [],
        ]);

        $this->signalService->emit('account.transaction.posted', (int) $transaction->company_id, [
            'transaction_id' => $transaction->id,
            'amount' => (float) $transaction->amount,
            'type' => $transaction->transaction_type,
        ]);

        return $transaction;
    }
}
