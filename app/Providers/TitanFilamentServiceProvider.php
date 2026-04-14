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
 *
 * NOTE: In Filament v3, panels are registered by listing PanelProviders
 * in config/app.php (providers array).  There is no Filament::registerPanel()
 * API.  This service provider handles only plugins and singleton binding.
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
        // Bind the panel provider so it can be resolved from the container
        // for programmatic introspection (e.g. titan:filament-check command).
        $this->app->singleton(TitanPanelProvider::class);
    }

    /**
     * Bootstrap application services.
     */
    public function boot(): void
    {
        $this->registerPlugins();
    }

    /**
     * Register each plugin listed in $this->plugins.
     *
     * Plugins are registered via Filament::serving() so they are only applied
     * after the panel registry has been fully booted.
     */
    protected function registerPlugins(): void
    {
        if (!class_exists(\Filament\Facades\Filament::class)) {
            return;
        }

        if (empty($this->plugins)) {
            return;
        }

        \Filament\Facades\Filament::serving(function () {
            foreach ($this->plugins as $pluginClass) {
                if (class_exists($pluginClass)) {
                    \Filament\Facades\Filament::getCurrentPanel()?->plugin(new $pluginClass());
                }
            }
        });
    }
}
