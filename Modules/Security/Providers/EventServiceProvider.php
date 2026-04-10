<?php

namespace Modules\Security\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // Listen for security-related events
        'Modules\Security\Events\AccessCardCreated' => [
            'Modules\Security\Listeners\LogSecurityEvent',
        ],
        'Modules\Security\Events\InOutPermitApproved' => [
            'Modules\Security\Listeners\LogSecurityEvent',
        ],
        'Modules\Security\Events\WorkPermitApproved' => [
            'Modules\Security\Listeners\LogSecurityEvent',
        ],
    ];
}
