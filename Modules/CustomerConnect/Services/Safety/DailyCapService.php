<?php

namespace Modules\CustomerConnect\Services\Safety;

use Modules\CustomerConnect\Entities\Delivery;
use Carbon\Carbon;

class DailyCapService
{
    public function isOverCap(int $companyId, string $channel, ?string $tz = null): bool
    {
        $caps = config('customerconnect.daily_caps', []);
        if (!($caps['enabled'] ?? false)) {
            return false;
        }
        $cap = (int)($caps[$channel] ?? 0);
        if ($cap <= 0) {
            return false;
        }

        $now = $tz ? Carbon::now($tz) : Carbon::now();
        $start = $now->copy()->startOfDay();
        $end   = $now->copy()->endOfDay();

        $count = Delivery::query()
            ->where('company_id', $companyId)
            ->where('channel', $channel)
            ->whereIn('status', ['sent','queued','sending'])
            ->whereBetween('created_at', [$start, $end])
            ->count();

        return $count >= $cap;
    }
}
