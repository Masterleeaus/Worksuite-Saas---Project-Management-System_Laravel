<?php

namespace Modules\Accountings\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Accountings\Services\SiteProfitabilityService;

class RecalculateSiteProfitabilityJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly int $companyId, public readonly string $siteRef)
    {
    }

    public function handle(SiteProfitabilityService $siteProfitabilityService): void
    {
        $siteProfitabilityService->recalculate($this->companyId, $this->siteRef);
    }
}
