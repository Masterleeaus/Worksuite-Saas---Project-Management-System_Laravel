<?php

namespace Modules\NexusFieldOps\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMStage;

/**
 * NexusSyncController
 *
 * Handles the offline batch-sync payload from the Nexus Field Ops Flutter app.
 * When the device regains connectivity it posts an array of queued actions that
 * were recorded while offline; this controller replays them in order.
 *
 * Nexus app endpoint: POST /v1/sync
 * Backend endpoint  : POST /api/nexus/v1/sync
 *
 * Supported action types:
 *   checkin   — sets date_start on an order
 *   checkout  — sets date_end on an order
 *   complete  — moves order to completion stage
 *   stage     — moves order to explicit stage_id
 *   note      — appends a text note (delegates to nexus_job_notes)
 */
class NexusSyncController extends Controller
{
    /**
     * POST /api/nexus/v1/sync
     *
     * Body:
     * {
     *   "actions": [
     *     { "type": "checkin",  "order_id": 12, "occurred_at": "2026-04-13T08:00:00Z", "latitude": -33.8, "longitude": 151.2 },
     *     { "type": "note",     "order_id": 12, "body": "Gate was open." },
     *     { "type": "checkout", "order_id": 12, "occurred_at": "2026-04-13T09:30:00Z" },
     *     { "type": "complete", "order_id": 12 }
     *   ]
     * }
     *
     * Returns a results array with per-action outcome.
     */
    public function sync(Request $request): JsonResponse
    {
        $request->validate([
            'actions'              => 'required|array|min:1|max:100',
            'actions.*.type'       => 'required|string|in:checkin,checkout,complete,stage,note',
            'actions.*.order_id'   => 'required|integer',
            'actions.*.occurred_at'=> 'nullable|date',
            'actions.*.latitude'   => 'nullable|numeric|between:-90,90',
            'actions.*.longitude'  => 'nullable|numeric|between:-180,180',
            'actions.*.stage_id'   => 'nullable|integer|exists:fsm_stages,id',
            'actions.*.body'       => 'nullable|string|max:2000',
        ]);

        $userId  = $request->user()->id;
        $results = [];

        foreach ($request->input('actions') as $index => $action) {
            $result = $this->processAction($action, $userId);
            $results[] = array_merge(['index' => $index], $result);
        }

        return response()->json(['results' => $results]);
    }

    private function processAction(array $action, int $userId): array
    {
        $orderId = (int) $action['order_id'];

        try {
            $order = FSMOrder::findOrFail($orderId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ['order_id' => $orderId, 'status' => 'error', 'message' => 'Order not found.'];
        }

        if ((int) $order->person_id !== $userId) {
            return ['order_id' => $orderId, 'status' => 'error', 'message' => 'Not assigned to this order.'];
        }

        $occurredAt = isset($action['occurred_at']) ? \Carbon\Carbon::parse($action['occurred_at']) : now();

        switch ($action['type']) {
            case 'checkin':
                $order->date_start = $occurredAt;
                $order->save();
                return ['order_id' => $orderId, 'status' => 'ok', 'action' => 'checkin'];

            case 'checkout':
                $order->date_end = $occurredAt;
                $order->save();
                return ['order_id' => $orderId, 'status' => 'ok', 'action' => 'checkout'];

            case 'complete':
                $completionStage = FSMStage::where('is_completion_stage', true)->orderBy('sequence')->first();
                if ($completionStage) {
                    $order->stage_id = $completionStage->id;
                }
                if (is_null($order->date_end)) {
                    $order->date_end = $occurredAt;
                }
                $order->save();
                return ['order_id' => $orderId, 'status' => 'ok', 'action' => 'complete'];

            case 'stage':
                if (empty($action['stage_id'])) {
                    return ['order_id' => $orderId, 'status' => 'error', 'message' => 'stage_id is required.'];
                }
                $order->stage_id = (int) $action['stage_id'];
                $order->save();
                return ['order_id' => $orderId, 'status' => 'ok', 'action' => 'stage'];

            case 'note':
                if (empty($action['body'])) {
                    return ['order_id' => $orderId, 'status' => 'error', 'message' => 'body is required for note actions.'];
                }
                \Modules\NexusFieldOps\Models\NexusJobNote::create([
                    'fsm_order_id' => $orderId,
                    'user_id'      => $userId,
                    'body'         => $action['body'],
                ]);
                return ['order_id' => $orderId, 'status' => 'ok', 'action' => 'note'];
        }

        return ['order_id' => $orderId, 'status' => 'error', 'message' => 'Unknown action type.'];
    }
}
