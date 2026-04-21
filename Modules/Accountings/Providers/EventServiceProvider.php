<?php

namespace Modules\Accountings\Providers;

use Modules\Accountings\Entities\Accounting;
use Modules\Accountings\Entities\BalanceSheet;
use Modules\Accountings\Entities\JournalType;
use Modules\Accountings\Entities\Journal;
use Modules\Accountings\Entities\Journald;
use Modules\Accountings\Entities\Pnl;
use Modules\Accountings\Entities\FinancialTransaction;
use Modules\Accountings\Entities\VisitCost;
use Modules\Accountings\Entities\ProfitabilitySnapshot;
use App\Events\NewCompanyCreatedEvent;
use Modules\Accountings\Observers\AccountingObserver;
use Modules\Accountings\Observers\JournalTypeObserver;
use Modules\Accountings\Observers\JournaldObserver;
use Modules\Accountings\Observers\JournalObserver;
use Modules\Accountings\Observers\BalanceSheetObserver;
use Modules\Accountings\Observers\PnlObserver;
use Modules\Accountings\Listeners\CompanyCreatedListener;
use Modules\Accountings\Policies\AccountingEnginePolicy;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;



class EventServiceProvider extends ServiceProvider
{
    protected $policies = [
        FinancialTransaction::class => AccountingEnginePolicy::class,
        VisitCost::class => AccountingEnginePolicy::class,
        ProfitabilitySnapshot::class => AccountingEnginePolicy::class,
    ];

/**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        NewCompanyCreatedEvent::class => [CompanyCreatedListener::class],
    ];



    protected $observers = [
        Accounting::class => [AccountingObserver::class],
        BalanceSheet::class => [BalanceSheetObserver::class],
        Pnl::class => [PnlObserver::class],
        JournalType::class => [JournalTypeObserver::class],
        Journal::class => [JournalObserver::class],
        Journald::class => [JournaldObserver::class],

    ];

}
