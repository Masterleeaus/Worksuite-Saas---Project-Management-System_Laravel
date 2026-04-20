<?php

namespace Modules\ZeroPay\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $moduleNamespace = 'Modules\\ZeroPay\\Http\\Controllers';

    public function boot(): void
    {
        parent::boot();

        $channelsPath = module_path('ZeroPay', '/Routes/channels.php');

        if (file_exists($channelsPath)) {
            require $channelsPath;
        }
    }

    public function map(): void
    {
        $this->mapWebRoutes();
        $this->mapAdminRoutes();
        $this->mapUserRoutes();
        $this->mapApiRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(module_path('ZeroPay', '/Routes/web.php'));
    }

    protected function mapAdminRoutes(): void
    {
        Route::middleware(['web', 'auth'])
            ->namespace($this->moduleNamespace)
            ->group(module_path('ZeroPay', '/Routes/admin.php'));
    }

    protected function mapUserRoutes(): void
    {
        Route::middleware(['web', 'auth'])
            ->namespace($this->moduleNamespace)
            ->group(module_path('ZeroPay', '/Routes/user.php'));
    }

    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('ZeroPay', '/Routes/api.php'));
    }
}
