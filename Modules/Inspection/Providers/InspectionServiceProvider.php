<?php

namespace Modules\Inspection\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Modules\Inspection\Console\Commands\AutoCreateRecurringSchedules;

class InspectionServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Inspection';

    public function register(): void
    {
        $this->registerConfig();
        $this->registerCommands();
    }

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerViews();
        if (config('inspection.bridge_load_migrations', false)) {
            $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        }
        $this->scheduleCommands();
        $this->registerTemplatesViaQcRegistry();

        // Titan Zero + Titan Go integration (capabilities registry)
        if (class_exists(\Modules\TitanZero\Services\CapabilityRegistry::class)) {
            \Modules\TitanZero\Services\CapabilityRegistry::registerModuleFromConfig('Inspection');
        }
    }

    protected function registerConfig(): void
    {
        // module config.php (if present)
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('inspection.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'inspection');

        // integrations (Titan link-outs only)
        $this->publishes([
            __DIR__ . '/../Config/integrations.php' => config_path('inspection_integrations.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/../Config/integrations.php', 'inspection_integrations');
    }

    protected function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . strtolower($this->moduleName));

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'inspection');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'inspection');
        }
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'inspection');

        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('views/modules/' . strtolower($this->moduleName)),
        ], 'views');
    }

    protected function registerCommands(): void
    {
        $this->commands([
            AutoCreateRecurringSchedules::class,
        ]);
    }

    protected function scheduleCommands(): void
    {
        // Worksuite commonly defines app.cron_timezone; fall back to app.timezone.
        $timezone = config('app.cron_timezone') ?: config('app.timezone', 'UTC');

        /** @var Schedule $schedule */
        $schedule = $this->app->make(Schedule::class);

        $schedule->command('recurring-schedule-create')
            ->daily()
            ->timezone($timezone);
    }

    /**
     * Register Inspection template packs into the QualityControl template registry.
     *
     * Inspection is the planning module; its default template packs (cleaner, builder,
     * electrician, plumber) are made available in the QC template registry so that
     * the verification engine can reference them without circular coupling.
     */
    protected function registerTemplatesViaQcRegistry(): void
    {
        if (! class_exists(\Modules\QualityControl\Support\TemplatePacks::class)) {
            return;
        }

        // The QC TemplatePacks::defaultPacks() is the canonical source already containing
        // the same pack definitions. Registering via the QC class here acts as a bridge
        // hook, giving future QC registry implementations a chance to enrich packs with
        // Inspection-sourced metadata (e.g. recommended frequency, site type).
        //
        // If a QC template registry service exists, delegate to it; otherwise no-op.
        if (! class_exists(\Modules\QualityControl\Services\TemplateRegistryService::class)) {
            return;
        }

        try {
            $registry = $this->app->make(\Modules\QualityControl\Services\TemplateRegistryService::class);
            $packs    = \Modules\Inspection\Support\TemplatePacks::defaultPacks();

            foreach ($packs as $trade => $items) {
                $registry->registerPack($trade, $items, 'inspection');
            }
        } catch (\Throwable) {
            // Non-fatal: template registry is optional infrastructure.
        }
    }
}
