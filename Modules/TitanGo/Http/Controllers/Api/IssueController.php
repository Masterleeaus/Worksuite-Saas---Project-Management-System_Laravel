<?php

namespace Modules\TitanGo\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\TitanGo\Models\TitanGoIssue;

/**
 * IssueController
 *
 * Structured issue reporting from Titan Go workers during a visit.
 *
 * POST /api/nexus/v1/jobs/{id}/issues
 * GET  /api/nexus/v1/jobs/{id}/issues
 */
class IssueController extends Controller
{
    /**
     * GET /api/nexus/v1/jobs/{id}/issues
     */
    public function index(Request $request, int $id): JsonResponse
    {
        $companyId = $request->user()->company_id;
        $workerId  = $request->user()->id;

        $this->assertWorkerAssigned($companyId, $workerId, $id);

        $issues = TitanGoIssue::where('company_id', $companyId)
            ->where('visit_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($issues);
    }

    /**
     * POST /api/nexus/v1/jobs/{id}/issues
     *
     * Body: {
     *   "type":        "access_blocked",   // see TitanGoIssue::TYPES
     *   "description": "Gate code invalid"
     * }
     */
    public function store(Request $request, int $id): JsonResponse
    {
        $companyId = $request->user()->company_id;
        $workerId  = $request->user()->id;

        $this->assertWorkerAssigned($companyId, $workerId, $id);

        $request->validate([
            'type'        => 'required|in:' . implode(',', TitanGoIssue::TYPES),
            'description' => 'nullable|string|max:2000',
        ]);

        $issue = TitanGoIssue::create([
            'company_id'  => $companyId,
            'worker_id'   => $workerId,
            'visit_id'    => $id,
            'type'        => $request->type,
            'description' => $request->description,
            'status'      => 'open',
        ]);

        return response()->json($issue, 201);
    }

    private function assertWorkerAssigned(int $companyId, int $workerId, int $orderId): void
    {
        $exists = FSMOrder::where('company_id', $companyId)
            ->where('id', $orderId)
            ->where('person_id', $workerId)
            ->exists();

        if (!$exists) {
            abort(403, 'You are not assigned to this visit.');
        }
    }
}
