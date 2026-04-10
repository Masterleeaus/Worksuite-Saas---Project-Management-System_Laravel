<?php

namespace Modules\Inventory\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string $webNamespace = 'Modules\\Inventory\\Http\\Controllers';

    public function boot(): void
    {
        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware(['web','auth'])
            ->prefix('inventory')
            ->name('inventory.')
            ->group(module_path('Inventory', '/Routes/web.php'));
    }

    protected function mapApiRoutes(): void
    {
        Route::middleware(['api','auth:sanctum'])
            ->prefix('api/inventory')
            ->name('inventory.api.')
            ->group(module_path('Inventory', '/Routes/api.php'));
    }
}
