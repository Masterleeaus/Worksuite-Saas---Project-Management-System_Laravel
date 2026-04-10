<?php

namespace Modules\Security\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Security\Services\ApprovalWorkflowService;
use Modules\Security\Services\AccessLogService;

class SecurityServiceProvider extends ServiceProvider
{
    protected $moduleName = 'Security';
    protected $moduleNameLower = 'security';

    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        $this->registerBindings();
    }

    protected function registerBindings()
    {
        // Register shared services
        $this->app->singleton(ApprovalWorkflowService::class, function ($app) {
            return new ApprovalWorkflowService();
        });

        $this->app->singleton(AccessLogService::class, function ($app) {
            return new AccessLogService();
        });
    }

    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
    }

    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    public function registerFactories()
    {
        // Register factories if needed
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('modules.paths.modules_assets_path') as $path) {
            if (is_dir($path)) {
                $paths[] = $path . DIRECTORY_SEPARATOR . $this->moduleNameLower;
            }
        }
        return $paths;
    }

    public function provides(): array
    {
        return [];
    }
}
