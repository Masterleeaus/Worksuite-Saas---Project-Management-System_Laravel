<?php

namespace Modules\ZeroPay\Actions;

use Modules\ZeroPay\Models\ZeroPayAttempt;
use Modules\ZeroPay\Models\ZeroPaySession;

class ConfirmBankTransferAction
{
    public function __construct(private readonly PostPaymentToWorksuiteAction $postAction)
    {
    }

    public function execute(ZeroPaySession $session)
    {
        $attempt = ZeroPayAttempt::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'method' => 'bank_transfer',
            'rail_type' => 'zero_fee',
            'status' => 'confirmed',
            'amount' => $session->amount,
            'confirmed_at' => now(),
            'payload' => ['source' => 'operator_bank_confirm'],
        ]);

        return $this->postAction->execute($attempt, ['remarks' => 'Bank transfer confirmed by operator']);
    }
}
