<?php

namespace Modules\TitanCore\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

class TitanCoreServiceProvider extends ServiceProvider
{
    private static bool $registered = false;

    private static bool $booted = false;

    public function boot(): void
    {
        if (self::$booted) {
            return;
        }

        self::$booted = true;

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
        $router->aliasMiddleware('ai.policy', \Modules\TitanCore\Http\Middleware\CheckAiPolicy::class);

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
                \Modules\TitanCore\Console\AiSmokeCommand::class,
                \Modules\TitanCore\Console\UninstallCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../Resources/views/components/create-with-ai.blade.php' =>
                    resource_path('views/vendor/titancore/components/create-with-ai.blade.php'),
            ], 'titancore-ui');
        }
    }

    public function register(): void
    {
        if (self::$registered) {
            return;
        }

        self::$registered = true;

        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'titancore');
        $this->mergeConfigFrom(__DIR__ . '/../Config/titan_agents.php', 'titan_agents');
        $this->mergeConfigFrom(__DIR__.'/../Config/ai.php', 'titancore');
        $this->mergeConfigFrom(__DIR__.'/../Config/tools.php', 'titancore.tools');
        $this->mergeConfigFrom(__DIR__.'/../Config/permissions.php', 'titancore.permissions');
        $this->mergeConfigFrom(__DIR__.'/../Config/policies.php', 'titancore.policies');
        $this->mergeConfigFrom(__DIR__.'/../Config/metrics.php', 'titancore.metrics');

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

        $this->app->bind(\Modules\TitanCore\Contracts\AI\ClientInterface::class, function () {
            return new \Modules\TitanCore\AI\Adapters\OpenAIAdapter([
                'api_key' => (string) (config('ai.providers.openai.api_key') ?? env('OPENAI_API_KEY') ?? ''),
                'base_url' => (string) (config('ai.providers.openai.base_url') ?? 'https://api.openai.com/v1'),
                'chat_model' => (string) (config('ai.providers.openai.chat_model') ?? 'gpt-4o-mini'),
                'embed_model' => (string) (config('ai.providers.openai.embed_model') ?? 'text-embedding-3-small'),
            ]);
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
