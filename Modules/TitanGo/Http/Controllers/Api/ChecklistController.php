<?php

namespace Modules\TitanGo\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\TitanGo\Models\TitanGoChecklistCompletion;

/**
 * ChecklistController
 *
 * Serves checklist steps for a visit, merged with per-worker completion state.
 * The checklist is defined by the FSMTemplate linked to the FSMOrder.
 *
 * GET  /api/nexus/v1/jobs/{id}/checklist
 * POST /api/nexus/v1/jobs/{id}/checklist/step
 */
class ChecklistController extends Controller
{
    /**
     * GET /api/nexus/v1/jobs/{id}/checklist
     *
     * Returns the ordered list of checklist steps for the visit, each annotated
     * with the worker's most-recent completion record (if any).
     *
     * Response shape:
     * {
     *   "visit_id": 123,
     *   "template_name": "Standard Clean",
     *   "steps": [
     *     {
     *       "index": 0,
     *       "instruction": "Check all equipment",
     *       "completed": true,
     *       "completed_at": "2026-04-13T09:22:00.000000Z",
     *       "has_photo": false,
     *       "has_signature": false,
     *       "notes": null
     *     },
     *     ...
     *   ],
     *   "completed_count": 1,
     *   "total_count": 5
     * }
     */
    public function index(Request $request, int $id): JsonResponse
    {
        $companyId = $request->user()->company_id;
        $workerId  = $request->user()->id;

        $order = FSMOrder::with('template')
            ->where('company_id', $companyId)
            ->where('person_id', $workerId)
            ->findOrFail($id);

        $rawSteps = $order->template?->checklist ?? [];

        // Load this worker's completions for this visit
        $completions = TitanGoChecklistCompletion::where('company_id', $companyId)
            ->where('visit_id', $id)
            ->where('worker_id', $workerId)
            ->orderByDesc('completed_at')
            ->get()
            ->keyBy('step_index');

        $steps = collect($rawSteps)->values()->map(function ($step, int $idx) use ($completions) {
            // Support both rich objects and plain strings (backward-compat)
            if (is_array($step)) {
                $instruction      = $step['instruction'] ?? '';
                $photoRequired    = (bool) ($step['photo_required'] ?? false);
                $sigRequired      = (bool) ($step['signature_required'] ?? false);
                $isRequired       = (bool) ($step['is_required'] ?? true);
            } else {
                $instruction      = (string) $step;
                $photoRequired    = false;
                $sigRequired      = false;
                $isRequired       = true;
            }

            $c = $completions->get($idx);
            return [
                'index'              => $idx,
                'instruction'        => $instruction,
                'photo_required'     => $photoRequired,
                'signature_required' => $sigRequired,
                'is_required'        => $isRequired,
                'completed'          => (bool) $c,
                'completed_at'       => $c?->completed_at,
                'has_photo'          => $c && !empty($c->getRawOriginal('photo_data')),
                'has_signature'      => $c && !empty($c->getRawOriginal('signature_data')),
                'notes'              => $c?->notes,
            ];
        });

        $completedCount = $steps->filter(fn($s) => $s['completed'])->count();

        return response()->json([
            'visit_id'        => $id,
            'template_name'   => $order->template?->name,
            'steps'           => $steps,
            'completed_count' => $completedCount,
            'total_count'     => $steps->count(),
        ]);
    }

    /**
     * POST /api/nexus/v1/jobs/{id}/checklist/step
     *
     * Mark a single step as complete (upserts by visit_id + step_index).
     *
     * Body:
     * {
     *   "step_index":     0,
     *   "notes":          "Optional notes",
     *   "photo_data":     "data:image/jpeg;base64,...",   // optional
     *   "signature_data": "data:image/png;base64,..."     // optional
     * }
     */
    public function completeStep(Request $request, int $id): JsonResponse
    {
        $companyId = $request->user()->company_id;
        $workerId  = $request->user()->id;

        // Verify assignment
        $order = FSMOrder::with('template')
            ->where('company_id', $companyId)
            ->where('person_id', $workerId)
            ->findOrFail($id);

        $rawSteps = $order->template?->checklist ?? [];
        $maxIndex = max(0, count($rawSteps) - 1);

        $request->validate([
            'step_index'     => 'required|integer|min:0|max:' . $maxIndex,
            'notes'          => 'nullable|string|max:2000',
            'photo_data'     => 'nullable|string',
            'signature_data' => 'nullable|string',
        ]);

        $idx = (int) $request->step_index;

        // Extract instruction from rich or plain step
        $rawStep     = $rawSteps[$idx] ?? null;
        $instruction = is_array($rawStep) ? ($rawStep['instruction'] ?? null) : $rawStep;

        $completion = TitanGoChecklistCompletion::where('company_id', $companyId)
            ->where('visit_id', $id)
            ->where('worker_id', $workerId)
            ->where('step_index', $idx)
            ->first();

        $attributes = [
            'company_id'     => $companyId,
            'visit_id'       => $id,
            'worker_id'      => $workerId,
            'step_index'     => $idx,
            'instruction'    => $instruction,
            'notes'          => $request->notes,
            'photo_data'     => $request->photo_data,
            'signature_data' => $request->signature_data,
            'completed_at'   => now(),
        ];

        if ($completion) {
            $completion->update($attributes);
        } else {
            $completion = TitanGoChecklistCompletion::create($attributes);
        }

        return response()->json([
            'index'       => $idx,
            'instruction' => $attributes['instruction'],
            'completed'   => true,
            'completed_at'=> $completion->completed_at,
        ], 201);
    }
}
