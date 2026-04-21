<?php

namespace Modules\Accountings\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccountingSignalEmitted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly int $companyId, public readonly array $payload = [])
    {
    }
}
