<?php

namespace Modules\TitanAgents\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\TitanAgents\Services\GptService;

/**
 * TitanAgents controller
 *
 * Pass 3: specialist agent execution is mediated by TitanZero.
 * This controller is a thin UI/API wrapper only.
 */
class TitanAgentsController extends Controller
{
    public function __construct(protected GptService $gpt) {}

    public function run(Request $request): JsonResponse
    {
        $data = $request->validate([
            'agent_slug' => ['nullable', 'string'],
            'input' => ['required', 'string'],
            'tenant_id' => ['nullable', 'integer'],
        ]);

        $tenantId = $data['tenant_id'] ?? null;
        $result = $this->gpt->interpretQuery($data['input'], $data['agent_slug'] ?? null, $tenantId);

        return response()->json($result);
    }
}
