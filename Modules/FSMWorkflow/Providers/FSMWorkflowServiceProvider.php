<?php

namespace Modules\FSMWorkflow\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\FSMWorkflow\Observers\FSMOrderObserver;

class FSMWorkflowServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        // Register the FSMOrder observer only when FSMCore is present.
        if (class_exists(\Modules\FSMCore\Models\FSMOrder::class)
            && \Illuminate\Support\Facades\Schema::hasTable('fsm_orders')
        ) {
            \Modules\FSMCore\Models\FSMOrder::observe(FSMOrderObserver::class);
        }
    }

    public function register(): void {}
}
