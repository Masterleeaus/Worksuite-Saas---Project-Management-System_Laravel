<?php

namespace Modules\TitanGo\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMStage;
use Modules\TitanGo\Models\TitanGoWorkerStatus;

/**
 * WorkerStatusController
 *
 * Handles quick-action status signals from the Titan Go bottom action bar.
 * Signals are persisted to titan_go_worker_statuses and optionally
 * advance the FSM order stage.
 *
 * POST /api/nexus/v1/jobs/{id}/status
 * GET  /api/nexus/v1/jobs/{id}/status
 */
class WorkerStatusController extends Controller
{
    private const STAGE_SIGNALS = [
        'arrived'      => 'On Site',
        'job_complete' => 'Completed',
    ];

    private const LEGACY_STATUS_MAP = [
        'check_in' => 'arrived',
        'checked_in' => 'arrived',
        'check_out' => 'job_complete',
        'checked_out' => 'job_complete',
        'complete' => 'job_complete',
    ];

    /**
     * GET /api/nexus/v1/jobs/{id}/status
     */
    public function index(Request $request, int $id): JsonResponse
    {
        $companyId = $request->user()->company_id;

        $statuses = TitanGoWorkerStatus::where('company_id', $companyId)
            ->where('visit_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($statuses);
    }

    /**
     * POST /api/nexus/v1/jobs/{id}/status
     *
     * Body: { "status": "arrived", "notes": "optional message" }
     */
    public function store(Request $request, int $id): JsonResponse
    {
        $companyId = $request->user()->company_id;
        $workerId  = $request->user()->id;

        $visitId = $id ?: (int)$request->get('visit_id', $request->get('booking_id', 0));
        $this->assertWorkerAssigned($companyId, $workerId, $visitId);

        $request->validate([
            'status' => 'required|string',
            'notes'  => 'nullable|string|max:1000',
        ]);

        $status = self::LEGACY_STATUS_MAP[$request->status] ?? $request->status;
        if (!in_array($status, TitanGoWorkerStatus::STATUSES, true)) {
            abort(422, 'Unsupported worker status.');
        }

        $signal = TitanGoWorkerStatus::create([
            'company_id' => $companyId,
            'worker_id'  => $workerId,
            'visit_id'   => $visitId,
            'status'     => $status,
            'notes'      => $request->notes,
        ]);

        // Optionally advance FSM stage for key lifecycle signals
        if (array_key_exists($status, self::STAGE_SIGNALS)) {
            $stageName = self::STAGE_SIGNALS[$status];
            $stage = FSMStage::where('name', $stageName)->first();
            if ($stage) {
                FSMOrder::where('id', $visitId)->update(['stage_id' => $stage->id]);
            }
        }

        return response()->json($signal, 201);
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
