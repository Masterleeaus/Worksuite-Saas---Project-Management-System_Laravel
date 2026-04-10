<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class McpAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $configuredToken = config('mcp.auth.token');

        // If no token is configured, allow through (dev/local only).
        if (empty($configuredToken)) {
            return $next($request);
        }

        $bearer = $request->bearerToken();

        if (!$bearer || !hash_equals($configuredToken, $bearer)) {
            return response()->json([
                'jsonrpc' => '2.0',
                'error'   => [
                    'code'    => -32001,
                    'message' => 'Unauthorized: invalid or missing MCP auth token.',
                ],
                'id'      => null,
            ], 401);
        }

        return $next($request);
    }
}
