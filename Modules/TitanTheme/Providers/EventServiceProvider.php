<?php

namespace Modules\TitanTheme\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'eloquent.created: App\Models\Company' => [
            \Modules\TitanTheme\Listeners\EnsureTitanThemeModuleSettings::class,
        ],
    ];
}
