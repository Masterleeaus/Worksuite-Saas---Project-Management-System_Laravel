<?php

namespace Modules\TitanPWA\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TitanPWAServiceProvider extends ServiceProvider
{
    protected string $name      = 'TitanPWA';
    protected string $nameLower = 'titanpwa';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerConfig();
        $this->registerViews();
        $this->registerTranslations();
        $this->publishAssets();

        // Only load migrations when the directory exists
        $migrationsPath = module_path($this->name, 'Database/Migrations');
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }

        // Web routes
        $web = module_path($this->name, 'Routes/web.php');
        if (file_exists($web)) {
            Route::middleware('web')->group($web);
        }

        // API routes
        $api = module_path($this->name, 'Routes/api.php');
        if (file_exists($api)) {
            Route::middleware('api')->prefix('api')->group($api);
        }

        // Console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Modules\TitanPWA\Console\Commands\GenerateVapidKeysCommand::class,
            ]);
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(module_path($this->name, 'Config/config.php'), $this->nameLower);
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $source = module_path($this->name, 'Config/config.php');

        if (is_file($source)) {
            $this->publishes(
                [$source => config_path($this->nameLower . '.php')],
                'titanpwa-config'
            );
        }
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $sourcePath = module_path($this->name, 'Resources/views');
        $viewPath   = resource_path('views/modules/' . $this->nameLower);

        $this->publishes(
            [$sourcePath => $viewPath],
            ['views', $this->nameLower . '-module-views']
        );

        $this->loadViewsFrom(
            array_merge($this->getPublishableViewPaths($sourcePath), [$sourcePath]),
            $this->nameLower
        );
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
        } else {
            $moduleLang = module_path($this->name, 'Resources/lang');
            if (is_dir($moduleLang)) {
                $this->loadTranslationsFrom($moduleLang, $this->nameLower);
            }
        }
    }

    /**
     * Publish static assets (JS, CSS, icons, service worker, manifest template).
     *
     * Consumers run: php artisan vendor:publish --tag=titanpwa-assets
     * The service worker is published to public/ so browsers can register it at root scope.
     */
    protected function publishAssets(): void
    {
        // Service Worker → public/titanpwa-sw.js  (must be at root scope)
        $this->publishes([
            module_path($this->name, 'Resources/js/titanpwa-sw.js') => public_path('titanpwa-sw.js'),
        ], 'titanpwa-sw');

        // Icons → public/vendor/titanpwa/icons/
        $this->publishes([
            module_path($this->name, 'Resources/icons') => public_path('vendor/titanpwa/icons'),
        ], 'titanpwa-icons');

        // Compiled CSS/JS → public/vendor/titanpwa/
        $this->publishes([
            module_path($this->name, 'Resources/assets/js')  => public_path('vendor/titanpwa/js'),
            module_path($this->name, 'Resources/assets/css') => public_path('vendor/titanpwa/css'),
        ], 'titanpwa-assets');

        // Offline HTML fallback → public/offline.html
        $this->publishes([
            module_path($this->name, 'Resources/static/offline.html') => public_path('offline.html'),
        ], 'titanpwa-offline');

        // All assets combined (convenience tag)
        $this->publishes([
            module_path($this->name, 'Resources/js/titanpwa-sw.js')   => public_path('titanpwa-sw.js'),
            module_path($this->name, 'Resources/icons')                => public_path('vendor/titanpwa/icons'),
            module_path($this->name, 'Resources/assets/js')            => public_path('vendor/titanpwa/js'),
            module_path($this->name, 'Resources/assets/css')           => public_path('vendor/titanpwa/css'),
            module_path($this->name, 'Resources/static/offline.html')  => public_path('offline.html'),
        ], 'titanpwa');
    }

    /**
     * Get paths to previously published views.
     */
    private function getPublishableViewPaths(string $sourcePath): array
    {
        $paths = [];

        foreach (config('view.paths', []) as $path) {
            $candidate = $path . '/modules/' . $this->nameLower;
            if (is_dir($candidate)) {
                $paths[] = $candidate;
            }
        }

        return $paths;
    }
}
