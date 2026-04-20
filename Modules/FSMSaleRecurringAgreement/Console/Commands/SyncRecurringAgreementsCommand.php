<?php

namespace Modules\FSMSaleRecurringAgreement\Console\Commands;

use Illuminate\Console\Command;
use Modules\FSMRecurring\Models\FSMRecurring;

class SyncRecurringAgreementsCommand extends Command
{
    protected $signature = 'fsm:sync-recurring-agreements';
    protected $description = 'Report FSM recurring schedules with and without agreement links.';

    public function handle(): int
    {
        $linked   = FSMRecurring::whereNotNull('agreement_id')->count();
        $unlinked = FSMRecurring::whereNull('agreement_id')->count();

        $this->table(['Status', 'Count'], [
            ['Linked to agreement', $linked],
            ['Not linked',          $unlinked],
        ]);

        return 0;
    }
}
