# PASS L — Provider snippet

Add to `Modules/CustomerConnect/Providers/CustomerConnectServiceProvider.php` within `boot()` or `register()` where commands are registered:

```php
if ($this->app->runningInConsole()) {
    $this->commands([
        \Modules\CustomerConnect\Console\Commands\CustomerConnectRetention::class,
    ]);
}
```

This keeps runtime safe and avoids side effects during sidebar rendering.
