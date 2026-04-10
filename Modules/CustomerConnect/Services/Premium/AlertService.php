<?php

namespace Modules\CustomerConnect\Services\Premium;

use Illuminate\Support\Facades\DB;

class AlertService
{
    public function createThreadSlaAlert(int $companyId, int $threadId, int $minutes, string $preview = ''): void
    {
        // idempotent per thread+minute bucket
        $key = 'sla:thread:'.$threadId.':'.$minutes.':'.now()->format('Y-m-d-H');
        DB::table('customerconnect_alerts')->updateOrInsert(
            ['company_id' => $companyId, 'alert_key' => $key],
            [
                'severity' => 'warning',
                'title' => 'SLA breach: awaiting response',
                'body' => 'Thread #'.$threadId.' awaiting response for >= '.$minutes.' minutes. '.$preview,
                'meta_json' => json_encode(['thread_id'=>$threadId,'minutes'=>$minutes]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
