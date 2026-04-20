<?php

namespace Modules\ZeroPay\Services;

class PaymentRailResolverService
{
    public function resolve(string $method, float $amount): array
    {
        $zeroFee = (array) config('zeropay.rails.zero_fee', []);
        $isZeroFee = in_array($method, $zeroFee, true);

        return [
            'rail_type' => $isZeroFee ? 'zero_fee' : 'processing_fee',
            'status' => $isZeroFee && in_array($method, ['cash', 'payid', 'bank_transfer'], true)
                ? 'awaiting_confirmation'
                : 'pending_gateway',
            'fee_amount' => $isZeroFee ? 0.0 : round($amount * 0.02, 2),
        ];
    }
}
