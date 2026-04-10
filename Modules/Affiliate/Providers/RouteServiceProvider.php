<?php

namespace Modules\Affiliate\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Controller namespace for module.
     *
     * @var string|null
     */
    protected $namespace = 'Modules\Affiliate\Http\Controllers';

    /**
     * Called after all services are registered.
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Map module routes.
     */
    public function map()
    {
        $this->mapWebRoutes();
    }

    /**
     * Load the Affiliate module web routes.
     */
    protected function mapWebRoutes()
    {
        $routes = module_path('Affiliate') . '/Routes/web.php';

        if (file_exists($routes)) {
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group($routes);
        }
    }
}