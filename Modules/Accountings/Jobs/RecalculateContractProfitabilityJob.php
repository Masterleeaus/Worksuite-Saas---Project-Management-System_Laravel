<?php

namespace Modules\Accountings\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Accountings\Services\ContractProfitabilityService;

class RecalculateContractProfitabilityJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly int $companyId, public readonly string $contractRef)
    {
    }

    public function handle(ContractProfitabilityService $contractProfitabilityService): void
    {
        $contractProfitabilityService->recalculate($this->companyId, $this->contractRef);
    }
}
