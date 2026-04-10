<?php

namespace Modules\Aitools\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        // Deterministic route loading for modern Laravel
        Route::middleware('web')
            ->group(module_path('Aitools', '/Routes/web.php'));
    }
}
