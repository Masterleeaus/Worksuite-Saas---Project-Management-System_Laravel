<?php

namespace Modules\FSMCore\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMStage;

/**
 * WorkerOrderController
 *
 * REST endpoints for field workers to view their assigned orders and
 * update order lifecycle status from a mobile device.
 *
 * Lifecycle transitions available to workers:
 *   check-in  → sets date_start to now, advances stage to the first non-completion stage
 *   check-out → sets date_end to now
 *   complete  → advances stage to the completion stage
 */
class WorkerOrderController extends Controller
{
    /**
     * GET /api/fsm/v1/orders
     *
     * Returns orders assigned to the authenticated worker.
     * Optional query params: status (open|complete), limit (default 50)
     */
    public function index(Request $request): JsonResponse
    {
        $workerId = $request->user()->id;

        $query = FSMOrder::with(['location', 'stage', 'team', 'equipment'])
            ->where('person_id', $workerId);

        if ($request->filled('status')) {
            if ($request->status === 'complete') {
                $query->whereHas('stage', fn($q) => $q->where('is_completion_stage', true));
            } elseif ($request->status === 'open') {
                $query->whereHas('stage', fn($q) => $q->where('is_completion_stage', false));
            }
        }

        $limit  = min((int) ($request->get('limit', 50)), 100);
        $orders = $query->orderByDesc('scheduled_date_start')->paginate($limit);

        return response()->json($orders);
    }

    /**
     * GET /api/fsm/v1/orders/{id}
     *
     * Returns full details of a single order, including location, equipment,
     * and uploaded photos/signatures. The worker must be assigned to the order.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $order = FSMOrder::with([
            'location',
            'stage',
            'team',
            'equipment',
            'photos',
            'tags',
            'template',
        ])->findOrFail($id);

        $this->authorizeWorker($request, $order);

        return response()->json($order);
    }

    /**
     * POST /api/fsm/v1/orders/{id}/checkin
     *
     * Records the worker's arrival: sets date_start = now.
     * Optionally accepts: { "latitude": 12.34, "longitude": 56.78 }
     */
    public function checkIn(Request $request, int $id): JsonResponse
    {
        $order = FSMOrder::findOrFail($id);
        $this->authorizeWorker($request, $order);

        $request->validate([
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $order->date_start = now();
        $order->save();

        return response()->json([
            'message'    => 'Checked in.',
            'date_start' => $order->date_start,
        ]);
    }

    /**
     * POST /api/fsm/v1/orders/{id}/checkout
     *
     * Records the worker's departure: sets date_end = now.
     */
    public function checkOut(Request $request, int $id): JsonResponse
    {
        $order = FSMOrder::findOrFail($id);
        $this->authorizeWorker($request, $order);

        $order->date_end = now();
        $order->save();

        return response()->json([
            'message'  => 'Checked out.',
            'date_end' => $order->date_end,
        ]);
    }

    /**
     * POST /api/fsm/v1/orders/{id}/complete
     *
     * Moves the order to the completion stage (first stage with is_completion_stage = true).
     * Also sets date_end if not already set.
     */
    public function complete(Request $request, int $id): JsonResponse
    {
        $order = FSMOrder::findOrFail($id);
        $this->authorizeWorker($request, $order);

        $completionStage = FSMStage::where('is_completion_stage', true)
            ->orderBy('sequence')
            ->first();

        if ($completionStage) {
            $order->stage_id = $completionStage->id;
        }

        if (is_null($order->date_end)) {
            $order->date_end = now();
        }

        $order->save();

        return response()->json([
            'message' => 'Order completed.',
            'stage'   => $completionStage ? $completionStage->name : null,
            'date_end' => $order->date_end,
        ]);
    }

    /**
     * POST /api/fsm/v1/orders/{id}/stage
     *
     * Moves the order to an explicit stage_id.
     * Body: { "stage_id": 3 }
     */
    public function updateStage(Request $request, int $id): JsonResponse
    {
        $order = FSMOrder::findOrFail($id);
        $this->authorizeWorker($request, $order);

        $request->validate([
            'stage_id' => 'required|integer|exists:fsm_stages,id',
        ]);

        $order->stage_id = (int) $request->stage_id;
        $order->save();

        return response()->json([
            'message'  => 'Stage updated.',
            'stage_id' => $order->stage_id,
        ]);
    }

    /**
     * Ensure the authenticated worker is assigned to the order.
     */
    private function authorizeWorker(Request $request, FSMOrder $order): void
    {
        if ((int) $order->person_id !== (int) $request->user()->id) {
            abort(403, 'You are not assigned to this order.');
        }
    }
}
