<?php

namespace Modules\ZeroPay\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ZeroPay\Services\ZeroPayPaymentPostingService;
use Modules\ZeroPay\Services\ZeroPaySessionService;

class ZeroPayServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'ZeroPay';
    protected string $moduleNameLower = 'zeropay';

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        $this->app->register(RouteServiceProvider::class);
    }

    public function register(): void
    {
        $this->app->singleton(ZeroPayPaymentPostingService::class, fn () => new ZeroPayPaymentPostingService());
        $this->app->singleton(ZeroPaySessionService::class, fn ($app) => new ZeroPaySessionService(
            $app->make(ZeroPayPaymentPostingService::class)
        ));
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');

        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }

    protected function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath,
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(
            array_merge($this->getPublishableViewPaths(), [$sourcePath]),
            $this->moduleNameLower
        );
    }

    protected function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];

        foreach ((array) \Config::get('view.paths', []) as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }

        return $paths;
    }
}
