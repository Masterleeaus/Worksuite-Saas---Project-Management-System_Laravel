<?php

namespace Modules\TitanCore\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

class TitanCoreServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerViews();

        // Migrations
        $migrationsPath = module_path('TitanCore') . '/Database/Migrations';
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }

        // Super Admin lock middleware
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('titancore.superadmin', \App\Http\Middleware\SuperAdmin::class);

        // Web routes
        $web = __DIR__ . '/../Routes/web.php';
        if (file_exists($web)) {
            Route::middleware('web')->group($web);
        }

        // API routes (mounted under /api)
        $api = __DIR__ . '/../Routes/api.php';
        if (file_exists($api)) {
            Route::middleware('api')->prefix('api')->group($api);
        }

        // Console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
            \Modules\TitanCore\Console\Commands\SyncTitanDocsKnowledgeCommand::class,
                \Modules\TitanCore\Console\SyncTitanAgentsCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'titancore');
        $this->mergeConfigFrom(__DIR__ . '/../Config/titan_agents.php', 'titan_agents');

        // Bind Titan AI client/provider/router (lazy + safe)
        $this->app->singleton(\Modules\TitanCore\Services\TitanAiClient::class, function () {
            $cfg = config('titancore.providers.titanai', []);
            return new \Modules\TitanCore\Services\TitanAiClient(
                (string)($cfg['base_url'] ?? ''),
                (string)($cfg['api_key'] ?? ''),
                (int)($cfg['timeout_seconds'] ?? 60),
            );
        });

        $this->app->singleton(\Modules\TitanCore\Services\Providers\TitanAiProvider::class, function ($app) {
            return new \Modules\TitanCore\Services\Providers\TitanAiProvider(
                $app->make(\Modules\TitanCore\Services\TitanAiClient::class)
            );
        });

        $this->app->singleton(\Modules\TitanCore\Services\TitanCoreRouter::class, function ($app) {
            return new \Modules\TitanCore\Services\TitanCoreRouter(
                $app->make(\Modules\TitanCore\Services\Providers\TitanAiProvider::class)
            );
        });

        // no bindings; keep lightweight
    }

    public function registerViews(): void
    {
        $viewPath   = resource_path('views/modules/titancore');
        $sourcePath = module_path('TitanCore') . '/Resources/views';

        if (is_dir($sourcePath)) {
            $this->publishes([
                $sourcePath => $viewPath,
            ], 'views');

            $this->loadViewsFrom($this->getPublishableViewPaths($sourcePath), 'titancore');
        }
    }

    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/titancore');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'titancore');
        } else {
            $moduleLang = module_path('TitanCore') . '/Resources/lang';
            if (is_dir($moduleLang)) {
                $this->loadTranslationsFrom($moduleLang, 'titancore');
            }
        }
    }

    private function getPublishableViewPaths(string $sourcePath): array
    {
        $paths = [];

        foreach (config('view.paths') as $path) {
            $candidate = $path . '/modules/titancore';

            if (is_dir($candidate)) {
                $paths[] = $candidate;
            }
        }

        $paths[] = $sourcePath;

        return $paths;
    }
}
