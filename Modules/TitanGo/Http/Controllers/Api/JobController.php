<?php

namespace Modules\TitanGo\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\TitanGo\Models\NexusJobNote;

/**
 * JobController
 *
 * Visit-level API for Titan Go workers.
 *
 * GET    /api/nexus/v1/jobs           — list worker's visits (paginated)
 * GET    /api/nexus/v1/jobs/{id}      — single visit detail
 * GET    /api/nexus/v1/jobs/{id}/notes — fetch notes for a visit
 * POST   /api/nexus/v1/jobs/{id}/notes — add a note to a visit
 */
class JobController extends Controller
{
    /**
     * GET /api/nexus/v1/jobs
     *
     * Returns the authenticated worker's visits with optional filters.
     * Query params: status (open|complete), date (YYYY-MM-DD), limit (default 50)
     */
    public function index(Request $request): JsonResponse
    {
        $workerId  = $request->user()->id;
        $companyId = $request->user()->company_id;

        $query = FSMOrder::with(['location', 'stage'])
            ->where('company_id', $companyId)
            ->where('person_id', $workerId);

        if ($request->filled('status')) {
            if ($request->status === 'complete') {
                $query->whereHas('stage', fn($q) => $q->where('is_completion_stage', true));
            } elseif ($request->status === 'open') {
                $query->whereHas('stage', fn($q) => $q->where('is_completion_stage', false));
            }
        }

        if ($request->filled('date')) {
            $query->whereDate('scheduled_date_start', $request->date);
        }

        $limit = min((int) $request->get('limit', 50), 100);

        return response()->json(
            $query->orderBy('scheduled_date_start')->paginate($limit)
        );
    }

    /**
     * GET /api/nexus/v1/jobs/{id}
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $companyId = $request->user()->company_id;
        $workerId  = $request->user()->id;

        $order = FSMOrder::with(['location', 'stage', 'template', 'photos'])
            ->where('company_id', $companyId)
            ->where('person_id', $workerId)
            ->findOrFail($id);

        return response()->json($order);
    }

    /**
     * GET /api/nexus/v1/jobs/{id}/notes
     */
    public function notes(Request $request, int $id): JsonResponse
    {
        $companyId = $request->user()->company_id;
        $workerId  = $request->user()->id;

        $this->assertWorkerAssigned($companyId, $workerId, $id);

        $notes = NexusJobNote::where('company_id', $companyId)
            ->where('fsm_order_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($notes);
    }

    /**
     * POST /api/nexus/v1/jobs/{id}/notes
     *
     * Body: { "body": "..." }
     */
    public function storeNote(Request $request, int $id): JsonResponse
    {
        $companyId = $request->user()->company_id;
        $workerId  = $request->user()->id;

        $this->assertWorkerAssigned($companyId, $workerId, $id);

        $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $note = NexusJobNote::create([
            'company_id'   => $companyId,
            'fsm_order_id' => $id,
            'worker_id'    => $workerId,
            'body'         => $request->body,
        ]);

        return response()->json($note, 201);
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
