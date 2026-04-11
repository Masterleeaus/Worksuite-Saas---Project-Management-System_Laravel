<?php

namespace Modules\SynapseDispatch\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\SynapseDispatch\Events\JobAssigned;
use Modules\SynapseDispatch\Listeners\NotifyWorkerOnAssignment;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        JobAssigned::class => [
            NotifyWorkerOnAssignment::class,
        ],
    ];
}
