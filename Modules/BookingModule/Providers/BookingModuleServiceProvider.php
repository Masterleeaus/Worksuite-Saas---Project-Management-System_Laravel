<?php

namespace Modules\BookingModule\Providers;

use Modules\BookingModule\Console\ActivateModuleCommand;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\BookingModule\Services\BookingFSMService;
use Modules\BookingModule\Services\BookingAutoInvoiceService;

class BookingModuleServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'BookingModule';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'bookingmodule';

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
        if (class_exists(\Modules\BookingModule\Entities\Appointment::class) && class_exists(\Modules\BookingModule\Observers\AppointmentObserver::class)) { \Modules\BookingModule\Entities\Appointment::observe(\Modules\BookingModule\Observers\AppointmentObserver::class); }
        if (class_exists(\Modules\BookingModule\Entities\Schedule::class) && class_exists(\Modules\BookingModule\Observers\ScheduleObserver::class)) { \Modules\BookingModule\Entities\Schedule::observe(\Modules\BookingModule\Observers\ScheduleObserver::class); }
        if (isset($this->app['router']) && class_exists(\Modules\BookingModule\Http\Middleware\PublicBookingHoneypot::class)) { $this->app['router']->aliasMiddleware('appointment.public.honeypot', \Modules\BookingModule\Http\Middleware\PublicBookingHoneypot::class); }
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        if (class_exists(\Modules\BookingModule\Providers\AuthServiceProvider::class)) { $this->app->register(\Modules\BookingModule\Providers\AuthServiceProvider::class); }
        if (class_exists(\Modules\BookingModule\Providers\EventServiceProvider::class)) { $this->app->register(\Modules\BookingModule\Providers\EventServiceProvider::class); }

        // Bind FSM services as singletons.
        $this->app->singleton(BookingFSMService::class);
        $this->app->singleton(BookingAutoInvoiceService::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        foreach (['config','auto_assign','dispatch','legacy_import','notifications','permissions'] as $cfg) {
            $cfgPath = module_path($this->moduleName, 'Config/' . $cfg . '.php');
            if (file_exists($cfgPath)) {
                $this->publishes([$cfgPath => config_path($this->moduleNameLower . '_' . $cfg . '.php')], 'config');
                $this->mergeConfigFrom($cfgPath, $this->moduleNameLower . '.' . $cfg);
            }
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
}
