<?php

namespace Modules\Accountings\Services;

use Modules\Accountings\Entities\VisitCost;
use Modules\Accountings\Events\VisitCostCalculated;

class VisitCostService
{
    public function __construct(private readonly AccountingSignalService $signalService)
    {
    }

    public function calculateAndStore(array $data): VisitCost
    {
        $totalCost = (float) ($data['labour_cost'] ?? 0) + (float) ($data['travel_cost'] ?? 0) + (float) ($data['equipment_cost'] ?? 0) + (float) ($data['consumables_cost'] ?? 0) + (float) ($data['overhead_cost'] ?? 0);
        $revenue = (float) ($data['revenue'] ?? 0);
        $margin = $revenue - $totalCost;
        $marginPercent = $revenue > 0 ? round(($margin / $revenue) * 100, 4) : 0;

        $visitCost = VisitCost::query()->updateOrCreate(
            ['company_id' => $data['company_id'], 'visit_ref' => $data['visit_ref']],
            [
                'user_id' => $data['user_id'] ?? auth()->id(),
                'job_ref' => $data['job_ref'] ?? null,
                'site_ref' => $data['site_ref'] ?? null,
                'contract_ref' => $data['contract_ref'] ?? null,
                'service_agreement_ref' => $data['service_agreement_ref'] ?? null,
                'labour_cost' => $data['labour_cost'] ?? 0,
                'travel_cost' => $data['travel_cost'] ?? 0,
                'equipment_cost' => $data['equipment_cost'] ?? 0,
                'consumables_cost' => $data['consumables_cost'] ?? 0,
                'overhead_cost' => $data['overhead_cost'] ?? 0,
                'total_cost' => $totalCost,
                'revenue' => $revenue,
                'margin' => $margin,
                'margin_percent' => $marginPercent,
                'status' => 'calculated',
                'occurred_at' => $data['occurred_at'] ?? now(),
                'meta' => $data['meta'] ?? [],
            ]
        );

        event(new VisitCostCalculated((int) $data['company_id'], ['visit_cost_id' => $visitCost->id, 'visit_ref' => $visitCost->visit_ref]));

        if ((float) $visitCost->margin_percent < 0) {
            $this->signalService->emit('account.margin.warning', (int) $data['company_id'], [
                'visit_ref' => $visitCost->visit_ref,
                'site_ref' => $visitCost->site_ref,
                'contract_ref' => $visitCost->contract_ref,
                'margin_percent' => (float) $visitCost->margin_percent,
            ]);
        }

        return $visitCost;
    }
}
