<?php

namespace Modules\Suppliers\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string $namespace = 'Modules\\Suppliers\\Http\\Controllers';

    public function boot(): void
    {
        $this->map();
    }

    protected function map(): void
    {
        Route::middleware(['web', 'auth'])
            ->group(module_path('Suppliers', '/Routes/web.php'));
    }
}
