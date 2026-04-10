<?php

namespace Modules\CustomerConnect\Services\Analytics;

use Modules\CustomerConnect\Entities\Campaign;
use Modules\CustomerConnect\Entities\Delivery;

class KpiBuilder
{
    public function forCompany(int $companyId): array
    {
        $campaigns = Campaign::where('company_id', $companyId)->count();

        $deliveries = Delivery::where('company_id', $companyId);
        $sent = (clone $deliveries)->where('status', 'sent')->count();
        $failed = (clone $deliveries)->where('status', 'failed')->count();
        $queued = (clone $deliveries)->whereIn('status', ['queued','sending'])->count();

        $byChannel = Delivery::selectRaw('channel, COUNT(*) as total')
            ->where('company_id', $companyId)
            ->groupBy('channel')
            ->pluck('total', 'channel')
            ->toArray();

        return compact('campaigns','sent','failed','queued','byChannel');
    }
}
