<?php

namespace Modules\Accountings\Services;

use Modules\Accountings\Entities\ProfitabilitySnapshot;
use Modules\Accountings\Entities\VisitCost;
use Modules\Accountings\Events\SiteMarginBelowThreshold;

class SiteProfitabilityService
{
    public function __construct(private readonly AccountingSignalService $signalService)
    {
    }

    public function recalculate(int $companyId, string $siteRef): ProfitabilitySnapshot
    {
        $costs = VisitCost::query()->where('company_id', $companyId)->where('site_ref', $siteRef)->get();
        $revenue = (float) $costs->sum('revenue');
        $cost = (float) $costs->sum('total_cost');
        $margin = $revenue - $cost;
        $marginPercent = $revenue > 0 ? round(($margin / $revenue) * 100, 4) : 0;

        $snapshot = ProfitabilitySnapshot::query()->create([
            'company_id' => $companyId,
            'user_id' => auth()->id(),
            'scope_type' => 'site',
            'scope_ref' => $siteRef,
            'period_start' => now()->startOfMonth()->toDateString(),
            'period_end' => now()->endOfMonth()->toDateString(),
            'revenue' => $revenue,
            'cost' => $cost,
            'margin' => $margin,
            'margin_percent' => $marginPercent,
            'flags' => [],
        ]);

        if ($marginPercent < 0) {
            event(new SiteMarginBelowThreshold($companyId, ['site_ref' => $siteRef, 'margin_percent' => $marginPercent]));
            $this->signalService->emit('account.margin.warning', $companyId, ['scope' => 'site', 'site_ref' => $siteRef, 'margin_percent' => $marginPercent]);
        }

        return $snapshot;
    }
}
