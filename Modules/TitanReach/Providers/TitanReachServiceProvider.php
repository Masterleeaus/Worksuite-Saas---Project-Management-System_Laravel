<?php

namespace Modules\TitanReach\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\TitanReach\Console\Commands\RunSmsCampaignCommand;
use Modules\TitanReach\Console\Commands\RunCallCampaignCommand;

class TitanReachServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'titanreach');

        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'titanreach');

        if ($this->app->runningInConsole()) {
            $this->commands([
                RunSmsCampaignCommand::class,
                RunCallCampaignCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'titanreach');
    }
}
