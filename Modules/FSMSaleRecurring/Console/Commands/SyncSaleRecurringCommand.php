<?php

namespace Modules\FSMSaleRecurring\Console\Commands;

use Illuminate\Console\Command;

class SyncSaleRecurringCommand extends Command
{
    protected $signature = 'fsm:sync-sale-recurring';
    protected $description = 'Report FSM recurring schedules with and without invoice line links.';

    public function handle(): int
    {
        $linked   = \Modules\FSMRecurring\Models\FSMRecurring::whereNotNull('invoice_line_id')->count();
        $unlinked = \Modules\FSMRecurring\Models\FSMRecurring::whereNull('invoice_line_id')->count();
        $this->table(['Status', 'Count'], [
            ['Linked to invoice line', $linked],
            ['Not linked', $unlinked],
        ]);
        return 0;
    }
}
