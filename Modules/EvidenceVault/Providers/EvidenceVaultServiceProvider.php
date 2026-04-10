<?php

namespace Modules\EvidenceVault\Providers;

use Illuminate\Support\ServiceProvider;

class EvidenceVaultServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'EvidenceVault';

    public function register(): void
    {
        $this->registerConfig();
    }

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        // Titan Zero integration (capabilities registry) – optional dependency.
        if (class_exists(\Modules\TitanZero\Services\CapabilityRegistry::class)) {
            \Modules\TitanZero\Services\CapabilityRegistry::registerModuleFromConfig('EvidenceVault');
        }
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('evidence_vault.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'evidence_vault');
    }

    protected function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . strtolower($this->moduleName));

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'evidence_vault');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'evidence_vault');
        }
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'evidence_vault');

        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('views/modules/' . strtolower($this->moduleName)),
        ], 'views');
    }
}
