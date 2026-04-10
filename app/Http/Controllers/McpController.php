<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * MCP (Model Context Protocol) HTTP Transport Controller
 *
 * Implements the Streamable HTTP transport for MCP 2025-03-26.
 * Single endpoint: POST /mcp  (accepts JSON-RPC 2.0 messages)
 *
 * Supported methods:
 *   initialize          → server handshake
 *   tools/list          → enumerate registered tools
 *   tools/call          → invoke a tool
 *   ping                → health check
 */
class McpController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $body = $request->json()->all();

        $method = $body['method']  ?? null;
        $id     = $body['id']      ?? null;
        $params = $body['params']  ?? [];

        return match ($method) {
            'initialize'   => $this->initialize($id, $params),
            'tools/list'   => $this->toolsList($id),
            'tools/call'   => $this->toolsCall($id, $params),
            'ping'         => $this->ping($id),
            default        => $this->methodNotFound($id, $method),
        };
    }

    // ─── Handlers ────────────────────────────────────────────────────────────

    private function initialize(mixed $id, array $params): JsonResponse
    {
        return $this->ok($id, [
            'protocolVersion' => '2025-03-26',
            'serverInfo'      => [
                'name'    => config('mcp.server.name', 'Worksuite SaaS'),
                'version' => config('mcp.server.version', '1.0.0'),
            ],
            'capabilities'    => [
                'tools'     => ['listChanged' => false],
                'resources' => ['listChanged' => false],
                'prompts'   => ['listChanged' => false],
            ],
        ]);
    }

    private function toolsList(mixed $id): JsonResponse
    {
        $tools = [];

        foreach (config('mcp.tools', []) as $toolClass) {
            /** @var \App\Mcp\Contracts\McpTool $tool */
            $tool = app($toolClass);

            $tools[] = [
                'name'        => $tool->name(),
                'description' => $tool->description(),
                'inputSchema' => $tool->inputSchema(),
            ];
        }

        return $this->ok($id, ['tools' => $tools]);
    }

    private function toolsCall(mixed $id, array $params): JsonResponse
    {
        $toolName  = $params['name']      ?? null;
        $arguments = $params['arguments'] ?? [];

        foreach (config('mcp.tools', []) as $toolClass) {
            /** @var \App\Mcp\Contracts\McpTool $tool */
            $tool = app($toolClass);

            if ($tool->name() !== $toolName) {
                continue;
            }

            try {
                $content = $tool->handle($arguments);
            } catch (\Throwable $e) {
                return $this->internalError($id, $e->getMessage());
            }

            return $this->ok($id, [
                'content' => $content,
                'isError' => false,
            ]);
        }

        return $this->error($id, -32602, "Tool '{$toolName}' not found.");
    }

    private function ping(mixed $id): JsonResponse
    {
        return $this->ok($id, []);
    }

    private function methodNotFound(mixed $id, ?string $method): JsonResponse
    {
        return $this->error($id, -32601, "Method not found: {$method}");
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function ok(mixed $id, mixed $result): JsonResponse
    {
        return response()->json([
            'jsonrpc' => '2.0',
            'id'      => $id,
            'result'  => $result,
        ]);
    }

    private function error(mixed $id, int $code, string $message): JsonResponse
    {
        return response()->json([
            'jsonrpc' => '2.0',
            'id'      => $id,
            'error'   => ['code' => $code, 'message' => $message],
        ]);
    }

    private function internalError(mixed $id, string $message): JsonResponse
    {
        return $this->error($id, -32603, "Internal error: {$message}");
    }
}
