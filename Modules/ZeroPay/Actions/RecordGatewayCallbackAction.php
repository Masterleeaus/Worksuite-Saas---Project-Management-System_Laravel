<?php

namespace Modules\ZeroPay\Actions;

use Modules\ZeroPay\Models\ZeroPayAttempt;

class RecordGatewayCallbackAction
{
    public function __construct(private readonly PostPaymentToWorksuiteAction $postAction)
    {
    }

    public function execute(ZeroPayAttempt $attempt, string $gateway, array $payload): array
    {
        $status = strtolower((string) ($payload['status'] ?? $payload['event'] ?? 'pending'));
        $isSuccess = str_contains($status, 'success') || str_contains($status, 'paid') || str_contains($status, 'confirm');

        if ($isSuccess) {
            $payment = $this->postAction->execute($attempt, [
                'transaction_id' => (string) ($payload['transaction_id'] ?? $payload['id'] ?? null),
                'remarks' => ucfirst($gateway) . ' callback confirmation',
            ]);

            return ['success' => true, 'payment_id' => $payment->id];
        }

        $attempt->status = 'failed';
        $attempt->payload = array_merge((array) $attempt->payload, ['callback' => $payload, 'gateway' => $gateway]);
        $attempt->save();

        return ['success' => false, 'payment_id' => null];
    }
}
