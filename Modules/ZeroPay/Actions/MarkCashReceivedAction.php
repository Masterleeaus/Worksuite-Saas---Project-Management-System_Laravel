<?php

namespace Modules\ZeroPay\Actions;

use Modules\ZeroPay\Models\ZeroPayAttempt;
use Modules\ZeroPay\Models\ZeroPaySession;

class MarkCashReceivedAction
{
    public function __construct(private readonly PostPaymentToWorksuiteAction $postAction)
    {
    }

    public function execute(ZeroPaySession $session)
    {
        $attempt = ZeroPayAttempt::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'method' => 'cash',
            'rail_type' => 'zero_fee',
            'status' => 'confirmed',
            'amount' => $session->amount,
            'confirmed_at' => now(),
            'payload' => ['source' => 'operator_mark_cash'],
        ]);

        return $this->postAction->execute($attempt, ['remarks' => 'Cash payment confirmed by operator']);
    }
}
