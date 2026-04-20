<?php

namespace Modules\ZeroPay\ViewModels;

use Modules\ZeroPay\Models\ZeroPaySession;

class SessionPageViewModel
{
    public function __construct(private readonly ZeroPaySession $session)
    {
    }

    public function toArray(): array
    {
        return [
            'session' => $this->session,
            'methods' => array_merge(
                (array) config('zeropay.rails.zero_fee', []),
                (array) config('zeropay.rails.processing_fee', [])
            ),
            'recommended_rail' => in_array('payid', (array) config('zeropay.rails.zero_fee', []), true) ? 'payid' : 'bank_transfer',
        ];
    }
}
