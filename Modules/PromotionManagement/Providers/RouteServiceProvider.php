<?php

namespace Modules\PromotionManagement\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\PromotionManagement\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        foreach ([
            module_path('PromotionManagement', '/Routes/web.php'),
            module_path('PromotionManagement', '/routes/web.php'),
        ] as $webRoutes) {
            if (file_exists($webRoutes)) {
                Route::middleware('web')
                    ->namespace($this->moduleNamespace)
                    ->group($webRoutes);
                break;
            }
        }
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        foreach ([
            module_path('PromotionManagement', '/Routes/api.php'),
            module_path('PromotionManagement', '/routes/api.php'),
            module_path('PromotionManagement', '/Routes/api/v1/api.php'),
            module_path('PromotionManagement', '/routes/api/v1/api.php'),
        ] as $apiRoutes) {
            if (file_exists($apiRoutes)) {
                Route::prefix('api')
                    ->middleware('api')
                    ->namespace($this->moduleNamespace)
                    ->group($apiRoutes);
                break;
            }
        }
    }
}
