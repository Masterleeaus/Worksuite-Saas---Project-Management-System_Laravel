<?php

namespace Modules\ServiceManagement\Providers;

use App\Events\NewCompanyCreatedEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\ServiceManagement\Entities\Service;
use Modules\ServiceManagement\Entities\ServiceAddon;
use Modules\ServiceManagement\Entities\ServicePricingRule;
use Modules\ServiceManagement\Listeners\CompanyCreatedListener;
use Modules\ServiceManagement\Observers\ServiceAddonSignalObserver;
use Modules\ServiceManagement\Observers\ServicePricingRuleSignalObserver;
use Modules\ServiceManagement\Observers\ServiceSignalObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the module.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        NewCompanyCreatedEvent::class => [CompanyCreatedListener::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        Service::observe(ServiceSignalObserver::class);
        ServiceAddon::observe(ServiceAddonSignalObserver::class);
        ServicePricingRule::observe(ServicePricingRuleSignalObserver::class);
    }
}
