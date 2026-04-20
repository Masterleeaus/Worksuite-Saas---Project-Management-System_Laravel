<?php

namespace Modules\FSMSaleAgreement\Console\Commands;

use Illuminate\Console\Command;
use Modules\FSMSaleAgreement\Services\SaleAgreementPropagationService;

class SyncSaleAgreementsCommand extends Command
{
    protected $signature = 'fsm:sync-sale-agreements';

    protected $description = 'Propagate agreement_id from FSM invoices to their linked FSM orders.';

    public function handle(SaleAgreementPropagationService $service): int
    {
        $count = $service->syncAll();
        $this->info("Updated {$count} FSM order(s) with agreement_id.");
        return 0;
    }
}
