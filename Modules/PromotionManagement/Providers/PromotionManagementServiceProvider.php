<?php

namespace Modules\PromotionManagement\Providers;

use Modules\PromotionManagement\Console\ActivateModuleCommand;
use Modules\PromotionManagement\Entities\Advertisement;
use Modules\PromotionManagement\Entities\Banner;
use Modules\PromotionManagement\Entities\Campaign;
use Modules\PromotionManagement\Entities\Coupon;
use Modules\PromotionManagement\Entities\Discount;
use Modules\PromotionManagement\Entities\PushNotification;
use Modules\PromotionManagement\Observers\BookingObserver;
use Modules\PromotionManagement\Observers\MarketingPromotionLifecycleObserver;
use Modules\PromotionManagement\Services\Ai\ProposalRunner;
use Modules\PromotionManagement\Services\Ai\TitanZeroBridge;
use Modules\PromotionManagement\Services\Bridge\InstantAdsEntityBridge;
use Modules\PromotionManagement\Services\PromotionService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class PromotionManagementServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'PromotionManagement';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'promotionmanagement';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ActivateModuleCommand::class,
            ]);
        }

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->bindCmsRenderingSlots();

        // Register booking observer (guarded — BookingModule may not be installed)
        if (class_exists(\Modules\BookingModule\Entities\Booking::class)) {
            \Modules\BookingModule\Entities\Booking::observe(BookingObserver::class);
        }

        $this->registerPromotionLifecycleDispatchers();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Load module helper functions (global functions used across the module)
        $helpers = module_path($this->moduleName, 'Lib/Promotion.php');
        if (file_exists($helpers)) { require_once $helpers; }

        $this->app->singleton(PromotionService::class);
        $this->app->singleton(TitanZeroBridge::class);
        $this->app->singleton(ProposalRunner::class);
        $this->app->singleton(InstantAdsEntityBridge::class);

        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );

        foreach (['ai', 'automation'] as $extraConfig) {
            $source = module_path($this->moduleName, 'Config/' . $extraConfig . '.php');

            if (!file_exists($source)) {
                continue;
            }

            $this->publishes([
                $source => config_path($this->moduleNameLower . '_' . $extraConfig . '.php'),
            ], 'config');

            $this->mergeConfigFrom($source, $this->moduleNameLower . '_' . $extraConfig);
        }
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
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

    private function registerPromotionLifecycleDispatchers(): void
    {
        foreach ([Discount::class, Coupon::class, Campaign::class, Advertisement::class, Banner::class, PushNotification::class] as $entityClass) {
            if (class_exists($entityClass)) {
                $entityClass::observe(MarketingPromotionLifecycleObserver::class);
            }
        }
    }

    private function bindCmsRenderingSlots(): void
    {
        $bladeCompiler = Blade::getFacadeRoot();

        if ($bladeCompiler && method_exists($bladeCompiler, 'includeIf')) {
            $bladeCompiler->includeIf('promotionmanagement::sections.sidebar', 'promotionmanagement_sidebar');
        } elseif ($bladeCompiler && method_exists($bladeCompiler, 'include') && view()->exists('promotionmanagement::sections.sidebar')) {
            $bladeCompiler->include('promotionmanagement::sections.sidebar', 'promotionmanagement_sidebar');
        }

        view()->share('promotionManagementSidebarView', 'promotionmanagement::sections.sidebar');
    }
}
