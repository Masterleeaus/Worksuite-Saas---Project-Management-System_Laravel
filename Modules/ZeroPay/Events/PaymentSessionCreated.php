<?php

namespace Modules\ZeroPay\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\ZeroPay\Models\ZeroPaySession;

class PaymentSessionCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly ZeroPaySession $session)
    {
    }
}
