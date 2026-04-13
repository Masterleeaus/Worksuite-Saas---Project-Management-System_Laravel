<?php

namespace Modules\NexusFieldOps\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;

/**
 * NexusChecklistController
 *
 * Bridges the Inspection module's checklist/template system to the Titan Go
 * mobile app. Titan Go requests checklists for a given order and submits
 * item completions through this controller.
 *
 * Routes:
 *   GET  /api/nexus/v1/jobs/{id}/checklist   — checklist items for an order
 *   POST /api/nexus/v1/jobs/{id}/checklist   — mark items complete
 */
class NexusChecklistController extends Controller
{
    /**
     * GET /api/nexus/v1/jobs/{id}/checklist
     *
     * Returns the inspection schedule items attached to this order's template.
     * Falls back to an empty list when the Inspection module is not active.
     */
    public function index(Request $request, int $id): JsonResponse
    {
        $worker = $request->user();
        $order  = FSMOrder::where('company_id', $worker->company_id)
            ->where('person_id', $worker->id)
            ->with(['template'])
            ->findOrFail($id);

        // If Inspection module not present, return empty checklist
        if (!class_exists(\Modules\Inspection\Models\ScheduleInspection::class)) {
            return response()->json([
                'fsm_order_id' => $order->id,
                'template'     => optional($order->template)->name,
                'items'        => [],
                'note'         => 'Inspection module not active.',
            ]);
        }

        // Load inspection items linked to this order via Inspection schedules
        $schedules = \Modules\Inspection\Models\ScheduleInspection::where('fsm_order_id', $order->id)
            ->with('items')
            ->get();

        $items = $schedules->flatMap(fn($schedule) => $schedule->items ?? collect());

        return response()->json([
            'fsm_order_id' => $order->id,
            'template'     => optional($order->template)->name,
            'items'        => $items,
        ]);
    }

    /**
     * POST /api/nexus/v1/jobs/{id}/checklist
     *
     * Mark checklist items as completed.
     *
     * Body: {
     *   "items": [
     *     { "item_id": 12, "completed": true, "note": "Done" },
     *     { "item_id": 13, "completed": false, "note": "Skipped — area locked" }
     *   ]
     * }
     */
    public function complete(Request $request, int $id): JsonResponse
    {
        $worker = $request->user();
        FSMOrder::where('company_id', $worker->company_id)
            ->where('person_id', $worker->id)
            ->findOrFail($id);

        $request->validate([
            'items'                => 'required|array',
            'items.*.item_id'      => 'required|integer',
            'items.*.completed'    => 'required|boolean',
            'items.*.note'         => 'nullable|string|max:1000',
        ]);

        if (!class_exists(\Modules\Inspection\Models\ScheduleItem::class)) {
            return response()->json(['message' => 'Inspection module not active. Items acknowledged only.']);
        }

        $updated = 0;
        foreach ($request->items as $itemData) {
            $item = \Modules\Inspection\Models\ScheduleItem::find($itemData['item_id']);
            if ($item) {
                $item->update([
                    'is_completed'   => $itemData['completed'],
                    'inspector_note' => $itemData['note'] ?? null,
                ]);
                $updated++;
            }
        }

        return response()->json([
            'message' => "{$updated} checklist item(s) updated.",
            'updated' => $updated,
        ]);
    }
}
