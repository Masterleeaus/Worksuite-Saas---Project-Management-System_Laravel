<?php

namespace Modules\Accountings\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Accountings\Services\ContractProfitabilityService;
use Modules\Accountings\Services\SiteProfitabilityService;

class GenerateAccountingSnapshotJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries   = 3;
    public int $timeout = 300;
    /** @var int[] */
    public array $backoff = [30, 120, 300];

    public function __construct(public readonly int $companyId, public readonly string $siteRef, public readonly string $contractRef)
    {
    }

    /** One snapshot per company+site+contract combination at a time. */
    public function uniqueId(): string
    {
        return "accounting_snapshot_{$this->companyId}_{$this->siteRef}_{$this->contractRef}";
    }

    public function handle(SiteProfitabilityService $siteProfitabilityService, ContractProfitabilityService $contractProfitabilityService): void
    {
        $siteProfitabilityService->recalculate($this->companyId, $this->siteRef);
        $contractProfitabilityService->recalculate($this->companyId, $this->contractRef);
    }
}
