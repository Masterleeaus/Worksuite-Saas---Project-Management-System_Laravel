<?php

namespace Modules\Suppliers\Providers;

use Illuminate\Support\ServiceProvider;

class SuppliersServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Suppliers';
    protected string $moduleNameLower = 'suppliers';

    public function register(): void
    {
        $this->mergeConfigFrom(module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->registerViews();
        $this->registerTranslations();
    }

    protected function registerViews(): void
    {
        $viewPath   = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower . '-module-views']);
        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
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

        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }

        return $paths;
    }
}
