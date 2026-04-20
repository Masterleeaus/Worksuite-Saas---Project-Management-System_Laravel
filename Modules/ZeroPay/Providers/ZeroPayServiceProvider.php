<?php

namespace Modules\ZeroPay\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ZeroPay\Actions\PostPaymentToWorksuiteAction;
use Modules\ZeroPay\Services\BankMatchService;
use Modules\ZeroPay\Services\DownloadTokenService;
use Modules\ZeroPay\Services\FollowupOrchestratorService;
use Modules\ZeroPay\Services\PaymentRailResolverService;
use Modules\ZeroPay\Services\PaymentRecommendationService;
use Modules\ZeroPay\Services\PaymentSessionService;
use Modules\ZeroPay\Services\SessionExpiryService;
use Modules\ZeroPay\Services\VoiceFollowupService;
use Modules\ZeroPay\Services\ZeroPayPaymentPostingService;
use Modules\ZeroPay\Services\ZeroPaySessionService;
use Modules\ZeroPay\Workflows\SessionLifecycleWorkflow;

class ZeroPayServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'ZeroPay';
    protected string $moduleNameLower = 'zeropay';

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(FilamentServiceProvider::class);
        $this->app->register(ModuleBootServiceProvider::class);

        $this->app->singleton(PaymentRailResolverService::class, fn () => new PaymentRailResolverService());
        $this->app->singleton(DownloadTokenService::class, fn () => new DownloadTokenService());
        $this->app->singleton(FollowupOrchestratorService::class, fn () => new FollowupOrchestratorService());
        $this->app->singleton(VoiceFollowupService::class, fn () => new VoiceFollowupService());
        $this->app->singleton(BankMatchService::class, fn () => new BankMatchService());
        $this->app->singleton(SessionExpiryService::class, fn () => new SessionExpiryService());
        $this->app->singleton(PaymentRecommendationService::class, fn ($app) => new PaymentRecommendationService($app->make(PaymentRailResolverService::class)));

        $this->app->singleton(PostPaymentToWorksuiteAction::class, fn () => new PostPaymentToWorksuiteAction());

        $this->app->singleton(PaymentSessionService::class, fn ($app) => new PaymentSessionService(
            $app->make(PaymentRailResolverService::class),
            $app->make(DownloadTokenService::class),
            $app->make(FollowupOrchestratorService::class),
            $app->make(VoiceFollowupService::class),
            $app->make(BankMatchService::class),
            $app->make(PostPaymentToWorksuiteAction::class)
        ));

        $this->app->singleton(SessionLifecycleWorkflow::class, fn ($app) => new SessionLifecycleWorkflow(
            $app->make(PaymentSessionService::class),
            $app->make(SessionExpiryService::class)
        ));

        // Backward-compatible facades for previously introduced classes.
        $this->app->singleton(ZeroPayPaymentPostingService::class, fn ($app) => new ZeroPayPaymentPostingService($app->make(PostPaymentToWorksuiteAction::class)));
        $this->app->singleton(ZeroPaySessionService::class, fn ($app) => new ZeroPaySessionService($app->make(PaymentSessionService::class)));
    }

    protected function registerConfig(): void
    {
        $configs = [
            'config.php' => $this->moduleNameLower,
            'features.php' => $this->moduleNameLower . '_features',
            'permissions.php' => $this->moduleNameLower . '_permissions',
            'navigation.php' => $this->moduleNameLower . '_navigation',
            'package.php' => $this->moduleNameLower . '_package',
            'ai.php' => $this->moduleNameLower . '_ai',
        ];

        foreach ($configs as $file => $key) {
            $source = module_path($this->moduleName, 'Config/' . $file);
            $target = config_path($key . '.php');

            $this->publishes([$source => $target], 'config');
            $this->mergeConfigFrom($source, $key);
        }
    }

    protected function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);
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

        foreach ((array) \Config::get('view.paths', []) as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }

        return $paths;
    }
}
