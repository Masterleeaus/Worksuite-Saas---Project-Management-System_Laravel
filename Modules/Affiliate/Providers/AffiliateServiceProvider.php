<?php

namespace Modules\Affiliate\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class AffiliateServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();

        $migrationsPath = module_path('Affiliate') . '/Database/Migrations';
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
    
        // Titan Zero + Titan Go integration (capabilities registry)
        if (class_exists(\Modules\TitanZero\Services\CapabilityRegistry::class)) {
            \Modules\TitanZero\Services\CapabilityRegistry::registerModuleFromConfig('Affiliate');
        }
}

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register the module route service provider
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $source = module_path('Affiliate') . '/Config/config.php';

        if (file_exists($source)) {
            $this->publishes([
                $source => config_path('affiliate.php'),
            ], 'config');

            $this->mergeConfigFrom($source, 'affiliate');
        }
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath   = resource_path('views/modules/affiliate');
        $sourcePath = module_path('Affiliate') . '/Resources/views';

        if (is_dir($sourcePath)) {
            $this->publishes([
                $sourcePath => $viewPath,
            ], 'views');

            $this->loadViewsFrom($this->getPublishableViewPaths($sourcePath), 'affiliate');
        }
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/affiliate');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'affiliate');
        } else {
            $moduleLang = module_path('Affiliate') . '/Resources/lang';
            if (is_dir($moduleLang)) {
                $this->loadTranslationsFrom($moduleLang, 'affiliate');
            }
        }
    }

    /**
     * Register factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            $factoryPath = module_path('Affiliate') . '/Database/factories';

            if (is_dir($factoryPath)) {
                app(Factory::class)->load($factoryPath);
            }
        }
    }

    /**
     * Get the publishable view paths for this module.
     *
     * @param  string  $sourcePath
     * @return array
     */
    private function getPublishableViewPaths($sourcePath)
    {
        $paths = [];

        foreach (config('view.paths', []) as $path) {
            $moduleViewPath = $path . '/modules/affiliate';

            if (is_dir($moduleViewPath)) {
                $paths[] = $moduleViewPath;
            }
        }

        if (is_dir($sourcePath)) {
            $paths[] = $sourcePath;
        }

        return $paths;
    }
}