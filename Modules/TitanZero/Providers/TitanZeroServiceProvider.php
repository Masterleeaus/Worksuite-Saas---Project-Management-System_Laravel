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

        // Admin routes
        $adminRoutes = __DIR__ . '/../Routes/admin.php';
        if (file_exists($adminRoutes)) {
            $this->loadRoutesFrom($adminRoutes);
        }

        // Account routes (Worksuite account-prefixed area) — ensure Titan Zero path + names
        $accountRoutes = __DIR__ . '/../Routes/account.php';
        if (file_exists($accountRoutes)) {
            Route::middleware(['web', 'auth'])
                ->prefix('account/titan/zero')
                ->name('titan.zero.')
                ->group($accountRoutes);
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
