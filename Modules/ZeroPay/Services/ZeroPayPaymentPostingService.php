<?php

namespace Modules\ZeroPay\Services;

use Modules\ZeroPay\Actions\PostPaymentToWorksuiteAction;
use Modules\ZeroPay\Models\ZeroPayAttempt;

class ZeroPayPaymentPostingService
{
    public function __construct(private readonly PostPaymentToWorksuiteAction $action)
    {
    }

    public function postConfirmedAttempt(ZeroPayAttempt $attempt, array $payload = [])
    {
        return $this->action->execute($attempt, $payload);
    }
}
