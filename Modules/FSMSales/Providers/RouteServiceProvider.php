<?php

namespace Modules\FSMSales\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            $web = __DIR__ . '/../Routes/web.php';
            if (file_exists($web)) {
                Route::middleware('web')
                    ->namespace('Modules\FSMSales\Http\Controllers')
                    ->group($web);
            }
        });
    }
}
