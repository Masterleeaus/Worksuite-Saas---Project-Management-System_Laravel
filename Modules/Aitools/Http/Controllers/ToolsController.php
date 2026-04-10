<?php

namespace Modules\Aitools\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Aitools\Tools\DTO\AitoolsContext;
use Modules\Aitools\Tools\ToolRegistry;

class ToolsController extends Controller
{
    public function index(ToolRegistry $registry): JsonResponse
    {
        return response()->json([
            'success' => true,
            'tools' => $registry->all(),
        ]);
    }

    public function run(Request $request, ToolRegistry $registry, string $name): JsonResponse
    {
        $toolClass = $registry->resolve($name);
        if (!$toolClass) {
            return response()->json(['success' => false, 'error' => 'Tool not found.'], 404);
        }

        // Best-effort context: Worksuite typically has auth user with company_id
        $user = $request->user();
        $companyId = (int) ($user->company_id ?? $user->companyId ?? 0);
        $userId = (int) ($user->id ?? 0);

        if ($companyId <= 0 || $userId <= 0) {
            return response()->json(['success' => false, 'error' => 'Missing authenticated tenant context.'], 401);
        }

        $ctx = new AitoolsContext($companyId, $userId, config('app.timezone', 'UTC'));
        $tool = app($toolClass);

        $args = (array) $request->input('args', []);
        $result = $tool->execute($ctx, $args);

        return response()->json($result);
    }
}
