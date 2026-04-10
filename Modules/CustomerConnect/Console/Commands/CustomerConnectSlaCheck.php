<?php

namespace Modules\CustomerConnect\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\CustomerConnect\Services\Premium\AlertService;

class CustomerConnectSlaCheck extends Command
{
    // UPGRADE 6: --minutes now falls back to config('customerconnect.sla.default_threshold_minutes')
    protected $signature = 'customerconnect:sla-check {--minutes= : Override threshold in minutes (default: customerconnect.sla.default_threshold_minutes config)}';
    protected $description = 'Create alerts for threads awaiting response beyond the SLA threshold.';

    public function handle(AlertService $alerts): int
    {
        // Config-backed default; CLI option overrides
        $minutes = $this->option('minutes') !== null
            ? (int) $this->option('minutes')
            : (int) config('customerconnect.sla.default_threshold_minutes', 60);

        if ($minutes < 1) {
            $this->error('SLA threshold must be at least 1 minute.');
            return self::FAILURE;
        }

        $this->line("Checking threads awaiting response for >= {$minutes} minutes...");

        $rows = DB::table('customerconnect_threads')
            ->where('status', 'open')
            ->whereNotNull('last_message_at')
            ->whereRaw('TIMESTAMPDIFF(MINUTE, last_message_at, NOW()) >= ?', [$minutes])
            ->limit(200)
            ->get(['id', 'company_id', 'assigned_to_user_id', 'channel', 'last_message_at', 'last_message_preview']);

        foreach ($rows as $t) {
            $alerts->createThreadSlaAlert(
                companyId: (int) $t->company_id,
                threadId:  (int) $t->id,
                minutes:   $minutes,
                preview:   (string)($t->last_message_preview ?? '')
            );
        }

        $this->info("SLA check complete. Threads checked: {$rows->count()}");
        return self::SUCCESS;
    }
}
