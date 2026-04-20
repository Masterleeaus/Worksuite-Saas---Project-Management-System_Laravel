<?php

namespace Modules\ZeroPay\Actions;

use Modules\ZeroPay\Models\ZeroPayAttempt;
use Modules\ZeroPay\Models\ZeroPayChannelLog;
use Modules\ZeroPay\Models\ZeroPaySession;
use Modules\ZeroPay\Services\PaymentRailResolverService;

class SelectPaymentMethodAction
{
    public function __construct(private readonly PaymentRailResolverService $resolver)
    {
    }

    public function execute(ZeroPaySession $session, string $method): ZeroPayAttempt
    {
        $resolution = $this->resolver->resolve($method, (float) $session->amount);

        $attempt = ZeroPayAttempt::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'method' => $method,
            'rail_type' => $resolution['rail_type'],
            'status' => $resolution['status'],
            'amount' => (float) $session->amount,
            'fee_amount' => (float) $resolution['fee_amount'],
            'payload' => ['selected_at' => now()->toIso8601String()],
        ]);

        $session->selected_method = $method;
        $session->status = $attempt->status;
        $session->save();

        ZeroPayChannelLog::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'channel' => 'web',
            'status' => 'logged',
            'subject' => 'Method selected',
            'body' => 'Customer selected payment method: ' . $method,
            'sent_at' => now(),
        ]);

        return $attempt;
    }
}
