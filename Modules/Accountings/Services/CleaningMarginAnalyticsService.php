<?php

namespace Modules\Accountings\Services;

use Illuminate\Support\Collection;
use Modules\Accountings\Entities\VisitCost;

class CleaningMarginAnalyticsService
{
    public function __construct(private readonly AccountingSignalService $signalService)
    {
    }

    public function detectProfitabilityAnomalies(int $companyId): Collection
    {
        $anomalies = collect();
        $negativeMarginVisits = VisitCost::query()->where('company_id', $companyId)->where('margin_percent', '<', 0)->limit(100)->get();

        foreach ($negativeMarginVisits as $visitCost) {
            $anomaly = [
                'type' => 'negative_margin_visit',
                'visit_ref' => $visitCost->visit_ref,
                'site_ref' => $visitCost->site_ref,
                'contract_ref' => $visitCost->contract_ref,
                'margin_percent' => (float) $visitCost->margin_percent,
            ];
            $anomalies->push($anomaly);
            $this->signalService->emit('account.margin.warning', $companyId, $anomaly);
        }

        return $anomalies;
    }
}
