<?php

namespace Modules\TitanGo\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            // API routes (Sanctum-protected)
            Route::middleware('api')
                ->group(__DIR__ . '/../Routes/api.php');

            // Admin web routes
            Route::middleware('web')
                ->group(__DIR__ . '/../Routes/web.php');
        });
    }
}
