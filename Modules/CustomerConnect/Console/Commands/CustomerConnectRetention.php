<?php

namespace Modules\CustomerConnect\Console\Commands;

use Illuminate\Console\Command;
use Modules\CustomerConnect\Services\Premium\RetentionService;

class CustomerConnectRetention extends Command
{
    protected $signature = 'customerconnect:retention {--archive-days=90} {--delete-days=365} {--no-delete}';
    protected $description = 'Archive and optionally prune old CustomerConnect inbox/campaign data';

    public function handle(RetentionService $service): int
    {
        $archiveDays = (int) $this->option('archive-days');
        $deleteDays = $this->option('no-delete') ? null : (int) $this->option('delete-days');

        $result = $service->run($archiveDays, $deleteDays);

        $this->info('CustomerConnect retention complete');
        foreach ($result as $k => $v) {
            $this->line(sprintf(' - %s: %s', $k, $v));
        }

        return self::SUCCESS;
    }
}
