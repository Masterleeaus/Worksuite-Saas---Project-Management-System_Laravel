<?php

namespace Modules\NexusFieldOps\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            $api = __DIR__ . '/../Routes/api.php';
            if (file_exists($api)) {
                Route::namespace('Modules\NexusFieldOps\Http\Controllers')
                    ->group($api);
            }
        });
    }
}
