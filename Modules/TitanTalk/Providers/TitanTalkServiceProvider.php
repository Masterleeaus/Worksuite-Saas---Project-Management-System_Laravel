<?php

namespace Modules\TitanTalk\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\TitanTalk\Events\TitanTalkMentionEvent;
use Modules\TitanTalk\Listeners\TitanTalkSmsListener;
use Modules\TitanTalk\Observers\TitanTalkAutoRoomObserver;
use Modules\TitanTalk\Services\TitanTalkService;

class TitanTalkServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'TitanTalk';
    protected string $moduleNameLower = 'titantalk';

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->app->register(RouteServiceProvider::class);

        // Register event → listener wiring
        $this->registerEventListeners();

        // Register model observers for auto-room creation (safe, class_exists guarded)
        $this->registerObservers();
    }

    public function register(): void
    {
        // Bind TitanTalkService as singleton so it can be injected everywhere
        $this->app->singleton(TitanTalkService::class, fn ($app) => new TitanTalkService());
    }

    // -------------------------------------------------------------------------
    // Event → Listener wiring
    // -------------------------------------------------------------------------

    private function registerEventListeners(): void
    {
        // TitanTalk mention → SMS escalation (optional, Sms module)
        Event::listen(TitanTalkMentionEvent::class, TitanTalkSmsListener::class);
    }

    // -------------------------------------------------------------------------
    // Auto-room creation observers (all guarded by class_exists)
    // -------------------------------------------------------------------------

    private function registerObservers(): void
    {
        $observer = $this->app->make(TitanTalkAutoRoomObserver::class);

        // Core Worksuite Project model
        if (class_exists(\App\Models\Project::class)) {
            \App\Models\Project::created(fn ($project) => $observer->onProjectCreated($project));
        }

        // Core Worksuite Task model (handles booking-type tasks from BookingModule)
        if (class_exists(\App\Models\Task::class)) {
            \App\Models\Task::created(fn ($task) => $observer->onTaskCreated($task));
        }

        // Core Worksuite Ticket model (support tickets / issues)
        if (class_exists(\App\Models\Ticket::class)) {
            \App\Models\Ticket::created(fn ($ticket) => $observer->onTicketCreated($ticket));
        }

        // FSMCore FSMOrder (service jobs)
        if (class_exists(\Modules\FSMCore\Models\FSMOrder::class)) {
            \Modules\FSMCore\Models\FSMOrder::created(fn ($order) => $observer->onFsmOrderCreated($order));
        }

        // BookingModule CleaningBooking
        if (class_exists(\Modules\BookingModule\Models\CleaningBooking::class)) {
            \Modules\BookingModule\Models\CleaningBooking::created(fn ($b) => $observer->onCleaningBookingCreated($b));
        }
    }

    // -------------------------------------------------------------------------
    // Standard nwidart boilerplate
    // -------------------------------------------------------------------------

    protected function registerConfig(): void
    {
        $configPath = module_path($this->moduleName, 'Config/config.php');
        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, $this->moduleNameLower);
        }
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
            $this->loadTranslationsFrom(
                module_path($this->moduleName, 'Resources/lang'),
                $this->moduleNameLower
            );
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

