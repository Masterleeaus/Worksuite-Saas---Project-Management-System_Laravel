<?php

return [

    /*
    |--------------------------------------------------------------------------
    | MCP Server Enabled
    |--------------------------------------------------------------------------
    |
    | When disabled the MCP routes are not registered and the server
    | is completely transparent.
    |
    */

    'enabled' => env('MCP_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Server Identity
    |--------------------------------------------------------------------------
    |
    | These values are returned in the server/info response so that MCP
    | clients know which server they are connected to.
    |
    */

    'server' => [
        'name'    => env('MCP_SERVER_NAME', 'Worksuite SaaS'),
        'version' => env('MCP_SERVER_VERSION', '1.0.0'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | Requests to MCP endpoints must carry Authorization: Bearer <token>.
    | Set MCP_AUTH_TOKEN in your .env to a strong random secret.
    | Leave empty to disable bearer-token auth (not recommended for production).
    |
    */

    'auth' => [
        'token' => env('MCP_AUTH_TOKEN'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Transport
    |--------------------------------------------------------------------------
    |
    | 'http'    — Streamable HTTP (recommended, single /mcp endpoint)
    | 'sse'     — Server-Sent Events (legacy, two endpoints: /mcp/sse + /mcp/message)
    |
    */

    'transport' => env('MCP_TRANSPORT', 'http'),

    /*
    |--------------------------------------------------------------------------
    | Route Prefix & Middleware
    |--------------------------------------------------------------------------
    |
    | MCP endpoints are mounted under this prefix.
    | Add 'throttle:60,1' or custom middleware as needed.
    |
    */

    'route_prefix' => env('MCP_ROUTE_PREFIX', 'mcp'),

    'middleware' => ['api', \App\Http\Middleware\McpAuthMiddleware::class],

    /*
    |--------------------------------------------------------------------------
    | Registered Tools
    |--------------------------------------------------------------------------
    |
    | Tool classes must implement App\Mcp\Contracts\McpTool.
    | Each tool exposes one callable function to the AI assistant.
    |
    */

    'tools' => [
        \App\Mcp\Tools\GetBookingsTool::class,
        \App\Mcp\Tools\GetClientsTool::class,
        \App\Mcp\Tools\GetEmployeesTool::class,
        \App\Mcp\Tools\GetInvoicesTool::class,
        \App\Mcp\Tools\CreateBookingTool::class,
        \App\Mcp\Tools\UpdateBookingStatusTool::class,
        \App\Mcp\Tools\GetModuleStatusTool::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Registered Resources
    |--------------------------------------------------------------------------
    |
    | Resources expose read-only data (like files, DB records) to the AI.
    | Each resource class must implement App\Mcp\Contracts\McpResource.
    |
    */

    'resources' => [
        // \App\Mcp\Resources\ClientListResource::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Registered Prompts
    |--------------------------------------------------------------------------
    |
    | Prompts expose pre-written prompt templates the AI can request.
    |
    */

    'prompts' => [
        // \App\Mcp\Prompts\BookingConfirmationPrompt::class,
    ],

];
