<?php

namespace Modules\FSMEquipment\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            $base = __DIR__ . '/../Routes';

            $web = $base . '/web.php';
            if (file_exists($web)) {
                Route::middleware('web')
                    ->namespace('Modules\FSMEquipment\Http\Controllers')
                    ->group($web);
            }
        });
    }
}
