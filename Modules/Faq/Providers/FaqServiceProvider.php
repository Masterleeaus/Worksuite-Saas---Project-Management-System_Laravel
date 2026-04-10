<?php

namespace Modules\Faq\Providers;

use Modules\Faq\Console\ActivateModuleCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FaqServiceProvider extends ServiceProvider
{

    protected string $name = 'Faq';

    protected string $nameLower = 'faq';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ActivateModuleCommand::class,
            ]);
        }

        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {

    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {

    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'lang'));
        }
    }

    /**
     * Register config.
     */
    /**
 * Register config.
 */
protected function registerConfig(): void
{
    $candidates = [
        module_path($this->name, 'Config/config.php'),
        module_path($this->name, 'config/config.php'),
    ];

    $source = null;
    foreach ($candidates as $path) {
        if (is_file($path)) {
            $source = $path;
            break;
        }
    }

    if ($source === null) {
        return;
    }

    $this->publishes([$source => config_path($this->nameLower.'.php')], 'config');
    $this->mergeConfigFrom($source, $this->nameLower);
}


    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        $componentNamespace = $this->moduleNamespace($this->name, $this->appPath(config('modules.paths.generator.component-class.path')));
        Blade::componentNamespace($componentNamespace, $this->nameLower);
    }

   /**
     * Get the services provided by the provider.
     *
     * @return string[] // Specify that this method returns an array of strings
     */
    public function provides(): array
    {
        return [];
    }

    /**
     * Get the paths to the publishable views.
     *
     * @return string[] // Specify that this method returns an array of strings
     */
    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->nameLower)) {
                $paths[] = $path . '/modules/' . $this->nameLower;
            }
        }

        return $paths;
    }


    protected function moduleNamespace(string $module, string $path): string
    {
        $path = trim(str_replace('/', '\\', $path), '\\');

        return 'Modules\\' . $module . ($path !== '' ? '\\' . $path : '');
    }

    protected function appPath(?string $path = null): string
    {
        $path = $path ?? '';

        return trim($path, '/\\');
    }

}
