<?php

namespace Modules\FSMRecurring\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\FSMRecurring\Observers\FSMOrderObserver;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [];

    public function boot(): void
    {
        parent::boot();

        // Register the observer only when FSMCore's FSMOrder model is available
        // and the fsm_orders table exists (guards against fresh-install boot ordering).
        if (class_exists(\Modules\FSMCore\Models\FSMOrder::class)
            && \Illuminate\Support\Facades\Schema::hasTable('fsm_orders')
            && \Illuminate\Support\Facades\Schema::hasColumn('fsm_orders', 'fsm_recurring_id')
        ) {
            \Modules\FSMCore\Models\FSMOrder::observe(FSMOrderObserver::class);
        }
    }
}
