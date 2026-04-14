<?php

namespace Modules\TitanPay\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\TitanPay\Services\CryptomusPaymentService;
use Modules\TitanPay\Services\PaymentDispatcher;
use Modules\TitanPay\Services\PaymentLinkService;
use Modules\TitanPay\Services\QrPaymentService;

class TitanPayServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'TitanPay';
    protected string $moduleNameLower = 'titanpay';

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        $this->app->register(RouteServiceProvider::class);
    }

    public function register(): void
    {
        $this->app->singleton(CryptomusPaymentService::class, fn() => new CryptomusPaymentService());
        $this->app->singleton(QrPaymentService::class, fn($app) => new QrPaymentService(
            $app->make(PaymentLinkService::class)
        ));
        $this->app->singleton(PaymentLinkService::class, fn() => new PaymentLinkService());
        $this->app->singleton(PaymentDispatcher::class, fn($app) => new PaymentDispatcher(
            $app->make(CryptomusPaymentService::class)
        ));
    }

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

    protected function registerViews(): void
    {
        $viewPath   = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath,
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(
            array_merge($this->getPublishableViewPaths(), [$sourcePath]),
            $this->moduleNameLower
        );
    }

    protected function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            foreach (['Resources/lang', 'resources/lang', 'lang'] as $relativeLangPath) {
                $moduleLangPath = module_path($this->moduleName, $relativeLangPath);

                if (is_dir($moduleLangPath)) {
                    $this->loadTranslationsFrom($moduleLangPath, $this->moduleNameLower);
                    break;
                }
            }
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
