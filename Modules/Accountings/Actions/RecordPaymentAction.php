<?php

namespace Modules\Accountings\Actions;

use App\Models\Payment;
use Modules\Accountings\Events\PaymentReceived;
use Modules\Accountings\Services\AccountingSignalService;
use Modules\Accountings\Services\LedgerPostingService;

class RecordPaymentAction
{
    public function __construct(private readonly LedgerPostingService $ledgerPostingService, private readonly AccountingSignalService $signalService)
    {
    }

    public function execute(Payment $payment, int $companyId): void
    {
        $this->ledgerPostingService->post([
            'company_id' => $companyId,
            'payment_id' => $payment->id,
            'invoice_id' => $payment->invoice_id,
            'transaction_type' => 'payment_received',
            'reference' => $payment->transaction_id,
            'amount' => (float) $payment->amount,
            'debit_account_code' => 'CASH',
            'credit_account_code' => 'AR',
            'occurred_at' => $payment->paid_on ?? now(),
            'meta' => ['gateway' => $payment->gateway, 'status' => $payment->status],
        ]);

        event(new PaymentReceived($companyId, ['payment_id' => $payment->id, 'invoice_id' => $payment->invoice_id, 'amount' => (float) $payment->amount]));
        $this->signalService->emit('account.payment.received', $companyId, ['payment_id' => $payment->id, 'invoice_id' => $payment->invoice_id, 'amount' => (float) $payment->amount]);
    }
}
