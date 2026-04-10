<?php

namespace Modules\TitanZero\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\TitanZero\Console\Commands\TitanZeroImportPdf;
use Modules\TitanZero\Console\Commands\TitanZeroClassifyDocs;
use Modules\TitanZero\Console\Commands\TitanZeroClassifyDocsV2;

class TitanZeroServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'titanzero');
    }

    public function boot(): void
    {
        // Views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'titanzero');

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        // Load module routes (support both Routes/ and routes/ folder casing)
        foreach ([__DIR__ . '/../Routes/web.php', __DIR__ . '/../routes/web.php'] as $routes) {
            if (file_exists($routes)) {
                $this->loadRoutesFrom($routes);
                break;
            }
        }

        // Admin routes (support both casings)
        foreach ([__DIR__ . '/../Routes/admin.php', __DIR__ . '/../routes/admin.php'] as $routes) {
            if (file_exists($routes)) {
                $this->loadRoutesFrom($routes);
                break;
            }
        }

        // Account routes (Worksuite account-prefixed area) — ensure Titan Zero path + names
        foreach ([__DIR__ . '/../routes/account.php', __DIR__ . '/../Routes/account.php'] as $accountRoutes) {
            if (file_exists($accountRoutes)) {
                Route::middleware(['web', 'auth'])
                    ->prefix('account/titan/zero')
                    ->name('titan.zero.')
                    ->group($accountRoutes);
                break;
            }
        }

        // API routes (job-access encrypted notes + existing gateway)
        foreach ([__DIR__ . '/../Routes/api.php', __DIR__ . '/../routes/api.php'] as $apiRoutes) {
            if (file_exists($apiRoutes)) {
                Route::middleware('api')
                    ->prefix('api')
                    ->group($apiRoutes);
                break;
            }
        }

        // ✅ Console-only registrations MUST be inside boot()
        if ($this->app->runningInConsole()) {
            $this->commands([
                TitanZeroImportPdf::class,
                TitanZeroClassifyDocs::class,
                TitanZeroClassifyDocsV2::class,
            ]);

            // Publish public assets (if present)
            $publicPath = __DIR__ . '/../Public/vendor/titanzero';
            if (is_dir($publicPath)) {
                $this->publishes([
                    $publicPath => public_path('vendor/titanzero'),
                ], 'titanzero-assets');
            }
        }
    }
}
