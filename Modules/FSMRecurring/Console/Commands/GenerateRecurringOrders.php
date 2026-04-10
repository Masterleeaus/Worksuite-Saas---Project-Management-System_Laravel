<?php

namespace Modules\FSMRecurring\Console\Commands;

use Illuminate\Console\Command;
use Modules\FSMRecurring\Models\FSMRecurring;

class GenerateRecurringOrders extends Command
{
    protected $signature   = 'fsm:recurring:generate {--dry-run : Show what would be generated without saving}';
    protected $description = 'Generate FSM orders for all active recurring schedules and auto-close expired ones.';

    public function handle(): int
    {
        if ($this->option('dry-run')) {
            $this->info('[dry-run] No orders will be created.');
        }

        if (!$this->option('dry-run')) {
            $created = FSMRecurring::cronGenerateOrders();
            $this->info("Generated {$created} FSM order(s).");

            $closed = FSMRecurring::cronManageExpiration();
            $this->info("Auto-closed {$closed} expired recurring schedule(s).");
        } else {
            $count = FSMRecurring::where('state', FSMRecurring::STATE_PROGRESS)->count();
            $this->line("Active recurring schedules: {$count}");
        }

        return self::SUCCESS;
    }
}
