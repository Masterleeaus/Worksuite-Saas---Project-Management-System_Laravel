<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class McpServiceProvider extends ServiceProvider
{
    /**
     * Register MCP tool classes into the container.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            base_path('config/mcp.php'),
            'mcp'
        );
    }

    /**
     * Load MCP routes and publish config.
     */
    public function boot(): void
    {
        if (!config('mcp.enabled', true)) {
            return;
        }

        $this->loadRoutesFrom(base_path('routes/mcp.php'));

        $this->publishes([
            base_path('config/mcp.php') => config_path('mcp.php'),
        ], 'mcp-config');
    }
}
