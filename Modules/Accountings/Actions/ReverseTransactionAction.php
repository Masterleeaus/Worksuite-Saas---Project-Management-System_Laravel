<?php

namespace Modules\Accountings\Actions;

use Modules\Accountings\Entities\FinancialTransaction;
use Modules\Accountings\Services\LedgerPostingService;

class ReverseTransactionAction
{
    public function __construct(private readonly LedgerPostingService $ledgerPostingService)
    {
    }

    public function execute(FinancialTransaction $transaction): FinancialTransaction
    {
        $transaction->status = 'reversed';
        $transaction->save();

        return $this->ledgerPostingService->post([
            'company_id' => (int) $transaction->company_id,
            'user_id' => auth()->id(),
            'invoice_id' => $transaction->invoice_id,
            'payment_id' => $transaction->payment_id,
            'visit_cost_id' => $transaction->visit_cost_id,
            'reversed_transaction_id' => $transaction->id,
            'transaction_type' => 'transaction_reversal',
            'reference' => $transaction->reference,
            'amount' => (float) $transaction->amount,
            'debit_account_code' => $transaction->credit_account_code,
            'credit_account_code' => $transaction->debit_account_code,
            'occurred_at' => now(),
            'meta' => ['reversed_transaction_id' => $transaction->id],
        ]);
    }
}
