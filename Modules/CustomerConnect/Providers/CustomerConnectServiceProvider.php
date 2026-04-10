<?php

namespace Modules\CustomerConnect\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Modules\CustomerConnect\Console\Commands\CacheUnread;
use Modules\CustomerConnect\Console\Commands\CustomerConnectRetention;
use Modules\CustomerConnect\Console\Commands\CustomerConnectSlaCheck;
use Modules\CustomerConnect\Console\Commands\ProcessDueRuns;
use Modules\CustomerConnect\Http\Middleware\VerifyTwilioSignature;
use Modules\CustomerConnect\Http\Middleware\VerifyVonageSignature;
use Modules\CustomerConnect\Services\Channels\ChannelSenderInterface;
use Modules\CustomerConnect\Services\Channels\WorkSuiteSmsModuleSender;

class CustomerConnectServiceProvider extends ServiceProvider
{
    protected $moduleName      = 'CustomerConnect';
    protected $moduleNameLower = 'customerconnect';

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        // Register webhook signature middleware aliases
        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('customerconnect.twilio.sig', VerifyTwilioSignature::class);
        $router->aliasMiddleware('customerconnect.vonage.sig', VerifyVonageSignature::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ProcessDueRuns::class,
                CacheUnread::class,
                CustomerConnectSlaCheck::class,
                CustomerConnectRetention::class,
            ]);
        }

        // UPGRADE 1: Register capabilities with TitanZero if present
        $this->registerTitanZeroCapabilities();
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);

        // Outbound sending abstraction
        $this->app->singleton(WorkSuiteSmsModuleSender::class, function () {
            return new WorkSuiteSmsModuleSender();
        });
        $this->app->bind(ChannelSenderInterface::class, WorkSuiteSmsModuleSender::class);
    }

    protected function registerConfig(): void
    {
        // Main module config
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );

        // CustomerConnect-specific supplementary config
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/customerconnect.php'), 'customerconnect_extra'
        );

        // UPGRADE 2: Automation manifest
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/automation.php'), 'customerconnect_automation'
        );

        // UPGRADE 1: TitanZero capabilities
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/titanzero.php'), 'titanzero_customerconnect'
        );
    }

    public function registerViews(): void
    {
        $viewPath   = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath,
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(
            array_merge($this->getPublishableViewPaths(), [$sourcePath]),
            $this->moduleNameLower
        );
    }

    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(
                module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower
            );
            $this->loadJsonTranslationsFrom(
                module_path($this->moduleName, 'Resources/lang')
            );
        }
    }

    /**
     * UPGRADE 1: Register CustomerConnect capabilities with TitanZero if installed.
     * Defensive — never throws if TitanZero is not present.
     */
    protected function registerTitanZeroCapabilities(): void
    {
        try {
            if (class_exists(\Modules\TitanZero\Services\CapabilityRegistry::class)) {
                \Modules\TitanZero\Services\CapabilityRegistry::registerModuleFromConfig(
                    'CustomerConnect',
                    config('titanzero_customerconnect', [])
                );
            }
        } catch (\Throwable $e) {
            // TitanZero not installed or incompatible — fail silently
        }
    }

    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
