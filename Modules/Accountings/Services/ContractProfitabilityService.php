<?php

namespace Modules\Accountings\Services;

use Modules\Accountings\Entities\ProfitabilitySnapshot;
use Modules\Accountings\Entities\VisitCost;
use Modules\Accountings\Events\ContractProfitabilityWarning;

class ContractProfitabilityService
{
    public function __construct(private readonly AccountingSignalService $signalService)
    {
    }

    public function recalculate(int $companyId, string $contractRef): ProfitabilitySnapshot
    {
        $costs = VisitCost::query()->where('company_id', $companyId)->where('contract_ref', $contractRef)->get();
        $revenue = (float) $costs->sum('revenue');
        $cost = (float) $costs->sum('total_cost');
        $margin = $revenue - $cost;
        $marginPercent = $revenue > 0 ? round(($margin / $revenue) * 100, 4) : 0;

        $snapshot = ProfitabilitySnapshot::query()->create([
            'company_id' => $companyId,
            'user_id' => auth()->id(),
            'scope_type' => 'contract',
            'scope_ref' => $contractRef,
            'period_start' => now()->startOfMonth()->toDateString(),
            'period_end' => now()->endOfMonth()->toDateString(),
            'revenue' => $revenue,
            'cost' => $cost,
            'margin' => $margin,
            'margin_percent' => $marginPercent,
            'flags' => [],
        ]);

        if ($marginPercent < 0) {
            event(new ContractProfitabilityWarning($companyId, ['contract_ref' => $contractRef, 'margin_percent' => $marginPercent]));
            $this->signalService->emit('account.contract.unprofitable', $companyId, ['contract_ref' => $contractRef, 'margin_percent' => $marginPercent]);
        }

        return $snapshot;
    }
}
