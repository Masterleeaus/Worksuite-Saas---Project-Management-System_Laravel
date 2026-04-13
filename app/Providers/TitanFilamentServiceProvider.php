<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * TitanFilamentServiceProvider
 *
 * Plugin-ready service provider for the Titan Filament panel.
 * Allows future registration of:
 *   - Filament plugins
 *   - Custom widgets
 *   - Multi-panel configurations
 *   - Voice UI bridge
 *   - PWA shell
 *
 * This provider is intentionally kept as a thin orchestration layer.
 * It DOES NOT replace any existing Worksuite provider.
 */
class TitanFilamentServiceProvider extends ServiceProvider
{
    /**
     * Plugins and extensions to register with the Titan panel.
     *
     * Add Filament plugin class strings here as the platform grows.
     *
     * @var array<class-string>
     */
    protected array $plugins = [
        // Example: \App\Filament\Plugins\TitanChatPlugin::class,
    ];

    /**
     * Register application services.
     */
    public function register(): void
    {
        // Bind the panel provider so it can be resolved from the container.
        $this->app->singleton(TitanPanelProvider::class);
    }

    /**
     * Bootstrap application services.
     */
    public function boot(): void
    {
        $this->registerPanelProvider();
        $this->registerPlugins();
    }

    /**
     * Register the Titan panel provider with Filament's panel registry
     * if the Filament facade is available (i.e. the package is installed).
     */
    protected function registerPanelProvider(): void
    {
        if (!class_exists(\Filament\Facades\Filament::class)) {
            return;
        }

        \Filament\Facades\Filament::registerPanel(
            $this->app->make(TitanPanelProvider::class)->panel(
                \Filament\Panel::make()
            )
        );
    }

    /**
     * Register each plugin listed in $this->plugins.
     */
    protected function registerPlugins(): void
    {
        if (!class_exists(\Filament\Facades\Filament::class)) {
            return;
        }

        foreach ($this->plugins as $pluginClass) {
            if (class_exists($pluginClass)) {
                \Filament\Facades\Filament::registerPlugin(new $pluginClass());
            }
        }
    }
}
