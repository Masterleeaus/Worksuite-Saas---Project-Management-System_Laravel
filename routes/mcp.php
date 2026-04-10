<?php

use App\Http\Controllers\McpController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MCP (Model Context Protocol) Routes
|--------------------------------------------------------------------------
|
| These routes expose this Laravel app as an MCP server so that AI
| assistants (Claude, GitHub Copilot, etc.) can call tools and query data.
|
| Authentication: Bearer token via McpAuthMiddleware (set MCP_AUTH_TOKEN).
|
| Endpoint: POST /mcp
|
| To connect Claude Code to this server, add to ~/.claude/settings.json:
|   {
|     "mcpServers": {
|       "worksuite": {
|         "type": "http",
|         "url": "https://your-domain.com/mcp",
|         "headers": { "Authorization": "Bearer <MCP_AUTH_TOKEN>" }
|       }
|     }
|   }
|
*/

if (config('mcp.enabled', true)) {

    Route::prefix(config('mcp.route_prefix', 'mcp'))
        ->middleware(config('mcp.middleware', ['api']))
        ->group(function () {

            // Streamable HTTP transport — single endpoint
            Route::post('/', [McpController::class, 'handle'])->name('mcp.handle');

            // Health check (no auth required)
            Route::get('/health', fn () => response()->json([
                'status' => 'ok',
                'server' => config('mcp.server.name'),
            ]))->withoutMiddleware(\App\Http\Middleware\McpAuthMiddleware::class);

        });

}
