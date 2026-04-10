# PASS J - Provider wiring (required)

In `Modules/CustomerConnect/Providers/CustomerConnectServiceProvider.php` ensure you have:

```php
public function boot()
{
    $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    $this->loadViewsFrom(__DIR__.'/../Resources/views', 'customerconnect');
    $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

    if ($this->app->runningInConsole()) {
        $this->commands([
            \Modules\CustomerConnect\Console\Commands\CustomerConnectSlaCheck::class,
        ]);
    }
}
```

This pass adds migrations + an SLA check command.
