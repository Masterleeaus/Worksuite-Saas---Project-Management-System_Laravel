<?php

namespace Modules\FSMActivity\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\FSMActivity\Models\FSMActivity;

class NotifyOverdueActivities extends Command
{
    protected $signature = 'fsm:activities:notify-overdue';

    protected $description = 'Mark open activities with a past due date as overdue.';

    public function handle(): int
    {
        $count = FSMActivity::where('state', 'open')
            ->whereDate('due_date', '<', now()->toDateString())
            ->update(['state' => 'overdue']);

        Log::info("FSMActivity: {$count} activities marked as overdue.");
        $this->info("Marked {$count} activities as overdue.");

        return self::SUCCESS;
    }
}
