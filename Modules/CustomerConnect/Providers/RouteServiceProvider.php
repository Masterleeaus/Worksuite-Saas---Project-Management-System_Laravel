<?php

namespace Modules\CustomerConnect\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace for controller string-based routing (kept for legacy blade use).
     */
    protected $moduleNamespace = 'Modules\CustomerConnect\Http\Controllers';

    public function boot(): void
    {
        parent::boot();
    }

    public function map(): void
    {
        $this->mapWebRoutes();
        $this->mapWebhookRoutes();
        $this->mapApiRoutes();
    }

    /**
     * Tenant UI routes — session state, CSRF, auth middleware.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(module_path('CustomerConnect', '/Routes/web.php'));
    }

    /**
     * Provider webhook routes — stateless, no CSRF, no auth.
     * Signature verification handled by middleware aliases registered in the service provider.
     * Prefix: webhook/customerconnect
     */
    protected function mapWebhookRoutes(): void
    {
        Route::prefix('webhook/customerconnect')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('CustomerConnect', '/Routes/webhooks.php'));
    }

    /**
     * Tenant-facing API routes (AJAX / mobile).
     * Webhook callbacks are in mapWebhookRoutes(), not here.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('CustomerConnect', '/Routes/api.php'));
    }
}
