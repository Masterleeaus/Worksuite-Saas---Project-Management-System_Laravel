<?php

namespace Modules\CustomerConnect\Console\Commands;

use Illuminate\Console\Command;
use Modules\CustomerConnect\Jobs\ExecuteCampaignRun;
use Modules\CustomerConnect\Services\CampaignScheduler;

class ProcessDueRuns extends Command
{
    protected $signature = 'customerconnect:process-due {--limit=50}';
    protected $description = 'Process due CustomerConnect campaign runs (Pass-2: marks runs complete; Pass-3 will send deliveries).';

    public function handle(CampaignScheduler $scheduler): int
    {
        $limit = (int)$this->option('limit');
        $runs = $scheduler->dueRuns($limit);

        foreach ($runs as $run) {
            ExecuteCampaignRun::dispatch($run->id);
        }

        $this->info('Dispatched ' . $runs->count() . ' runs.');
        return self::SUCCESS;
    }
}
