<?php

namespace Modules\TitanTheme\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\TitanTheme\Services\ThemeService;
use Modules\TitanTheme\Services\NavigationService;
use Modules\TitanTheme\Services\WhiteLabelService;

class TitanThemeServiceProvider extends ServiceProvider
{
    protected string $moduleName      = 'TitanTheme';
    protected string $moduleNameLower = 'titantheme';

    public function register(): void
    {
        $this->registerConfig();

        $this->app->singleton(ThemeService::class);
        $this->app->singleton(NavigationService::class);
        $this->app->singleton(WhiteLabelService::class);
    }

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path('titantheme.php'),
        ], 'config');

        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }

    protected function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(
                module_path($this->moduleName, 'Resources/lang'),
                $this->moduleNameLower
            );
        }
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(
            module_path($this->moduleName, 'Resources/views'),
            $this->moduleNameLower
        );

        $this->publishes([
            module_path($this->moduleName, 'Resources/views') =>
                resource_path('views/modules/' . $this->moduleNameLower),
        ], 'views');
    }
}
