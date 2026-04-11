<?php

namespace Modules\ClientPulse\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'clientpulse');
    }
}
