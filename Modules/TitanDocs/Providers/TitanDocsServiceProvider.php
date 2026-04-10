<?php

namespace Modules\TitanDocs\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\TitanDocs\Console\InstallTitanDocsCommand;
use Modules\TitanDocs\Providers\RouteServiceProvider;
class TitanDocsServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'TitanDocs';

    /**
     * @var string $moduleNameLower
     *
     * NOTE: This module historically used the view hint "aidocument::".
     * We now ALSO register a stable "titandocs::" hint for Titan-branded routes
     * and admin template screens, while keeping backward compatibility.
     */
    protected $moduleNameLower = 'titandocs';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        }

    /**
     * Register config.
     */
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

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $sourcePath = module_path($this->moduleName, 'Resources/views');

        // Legacy hint: aidocument::
        $legacyHint = 'aidocument';
        $legacyViewPath = resource_path('views/modules/' . $legacyHint);
        $this->publishes([
            $sourcePath => $legacyViewPath,
        ], ['views', $legacyHint . '-module-views']);

        $this->loadViewsFrom(
            array_merge($this->getPublishableViewPathsFor($legacyHint), [$sourcePath]),
            $legacyHint
        );

        // New hint: titandocs:: (prevents "No hint path defined for [titandocs]" fatals)
        $newHint = 'titandocs';
        $newViewPath = resource_path('views/modules/' . $newHint);
        $this->publishes([
            $sourcePath => $newViewPath,
        ], ['views', $newHint . '-module-views']);

        $this->loadViewsFrom(
            array_merge($this->getPublishableViewPathsFor($newHint), [$sourcePath]),
            $newHint
        );
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'Resources/lang'));
        }

        // Also register a Titan-branded translation namespace for any new screens.
        $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), 'titandocs');
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPathsFor(string $hint): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $hint)) {
                $paths[] = $path . '/modules/' . $hint;
            }
        }
        return $paths;
    }
}
