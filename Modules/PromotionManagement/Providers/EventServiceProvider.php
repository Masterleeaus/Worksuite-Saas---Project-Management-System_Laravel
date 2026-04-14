<?php

namespace Modules\PromotionManagement\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\PromotionManagement\Events\MarketingPromotionLifecycleSignal;
use Modules\PromotionManagement\Listeners\EmitMarketingPromotionLifecycleSignalToTitanZero;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the module.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        MarketingPromotionLifecycleSignal::class => [
            EmitMarketingPromotionLifecycleSignalToTitanZero::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
