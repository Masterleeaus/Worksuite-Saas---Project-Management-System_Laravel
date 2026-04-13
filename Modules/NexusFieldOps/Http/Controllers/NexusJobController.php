<?php

namespace Modules\NexusFieldOps\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\NexusFieldOps\Models\NexusJobIssue;
use Modules\NexusFieldOps\Models\NexusJobNote;

/**
 * NexusJobController
 *
 * Handles Titan Go job-level actions that extend FSMCore:
 *   - worker notes on a job
 *   - structured issue escalation
 *
 * All routes are prefixed: /api/nexus/v1/jobs
 */
class NexusJobController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    // Notes
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * GET /api/nexus/v1/jobs/{id}/notes
     *
     * List all notes for an order, newest first.
     */
    public function noteIndex(Request $request, int $id): JsonResponse
    {
        $order = $this->resolveOrder($request, $id);

        $notes = NexusJobNote::where('fsm_order_id', $order->id)
            ->with('worker:id,name')
            ->latest()
            ->paginate(50);

        return response()->json($notes);
    }

    /**
     * POST /api/nexus/v1/jobs/{id}/notes
     *
     * Body: { "body": "...", "note_type": "general|issue|access|supply", "synced_offline": false }
     */
    public function noteStore(Request $request, int $id): JsonResponse
    {
        $order = $this->resolveOrder($request, $id);
        $worker = $request->user();

        $validated = $request->validate([
            'body'           => 'required|string|max:5000',
            'note_type'      => 'nullable|in:general,issue,access,supply',
            'synced_offline' => 'nullable|boolean',
        ]);

        $note = NexusJobNote::create([
            'company_id'     => $worker->company_id,
            'fsm_order_id'   => $order->id,
            'worker_id'      => $worker->id,
            'body'           => $validated['body'],
            'note_type'      => $validated['note_type'] ?? 'general',
            'synced_offline' => $validated['synced_offline'] ?? false,
        ]);

        return response()->json(['message' => 'Note saved.', 'note' => $note], 201);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Issues
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * GET /api/nexus/v1/jobs/{id}/issues
     *
     * List issues for an order.
     */
    public function issueIndex(Request $request, int $id): JsonResponse
    {
        $order = $this->resolveOrder($request, $id);

        $issues = NexusJobIssue::where('fsm_order_id', $order->id)
            ->with('worker:id,name')
            ->latest()
            ->get();

        return response()->json(['data' => $issues]);
    }

    /**
     * POST /api/nexus/v1/jobs/{id}/issues
     *
     * Body: {
     *   "issue_type": "access_blocked|customer_absent|damage|extra_work|safety_risk|equipment_missing",
     *   "description": "...",
     *   "synced_offline": false
     * }
     */
    public function issueStore(Request $request, int $id): JsonResponse
    {
        $order  = $this->resolveOrder($request, $id);
        $worker = $request->user();

        $validated = $request->validate([
            'issue_type'     => 'required|in:' . implode(',', NexusJobIssue::TYPES),
            'description'    => 'nullable|string|max:5000',
            'synced_offline' => 'nullable|boolean',
        ]);

        $issue = NexusJobIssue::create([
            'company_id'     => $worker->company_id,
            'fsm_order_id'   => $order->id,
            'worker_id'      => $worker->id,
            'issue_type'     => $validated['issue_type'],
            'description'    => $validated['description'] ?? null,
            'status'         => 'open',
            'synced_offline' => $validated['synced_offline'] ?? false,
        ]);

        return response()->json(['message' => 'Issue reported.', 'issue' => $issue], 201);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Resolve an FSMOrder and confirm the authenticated worker is assigned to it.
     */
    private function resolveOrder(Request $request, int $id): FSMOrder
    {
        $order = FSMOrder::where('company_id', $request->user()->company_id)
            ->findOrFail($id);

        if ((int) $order->person_id !== (int) $request->user()->id) {
            abort(403, 'You are not assigned to this job.');
        }

        return $order;
    }
}
