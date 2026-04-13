<?php

namespace Modules\FSMCore\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;

/**
 * WorkerTimesheetController
 *
 * Allows field workers to log and retrieve their timesheet hours
 * for individual orders via Titan Go.
 *
 * Delegates to FSMTimesheet module when present; returns stub data otherwise.
 *
 * Routes:
 *   GET  /api/fsm/v1/timesheets            — list the worker's timesheet lines
 *   POST /api/fsm/v1/timesheets            — create a timesheet entry
 */
class WorkerTimesheetController extends Controller
{
    /**
     * GET /api/fsm/v1/timesheets
     *
     * Optional query params: fsm_order_id, date (YYYY-MM-DD), limit (max 100)
     */
    public function index(Request $request): JsonResponse
    {
        $workerId = $request->user()->id;

        if (!class_exists(\Modules\FSMTimesheet\Models\FSMTimesheetLine::class)) {
            return response()->json([
                'data'    => [],
                'message' => 'FSMTimesheet module not active.',
            ]);
        }

        $query = \Modules\FSMTimesheet\Models\FSMTimesheetLine::where('user_id', $workerId);

        if ($request->filled('fsm_order_id')) {
            $query->where('fsm_order_id', (int) $request->fsm_order_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $limit = min((int) ($request->get('limit', 50)), 100);
        $lines = $query->orderByDesc('date')->paginate($limit);

        return response()->json($lines);
    }

    /**
     * POST /api/fsm/v1/timesheets
     *
     * Body: {
     *   "fsm_order_id": 1,
     *   "date":         "2026-04-13",
     *   "start_time":   "08:00",
     *   "end_time":     "16:30",
     *   "name":         "Site clean — Level 3"  // optional description
     * }
     *
     * unit_amount (hours) is auto-computed from start_time/end_time by the model.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'fsm_order_id' => 'required|integer|exists:fsm_orders,id',
            'date'         => 'required|date_format:Y-m-d',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i|after:start_time',
            'name'         => 'nullable|string|max:255',
        ]);

        $worker = $request->user();

        // Verify worker is assigned to this order
        $order = FSMOrder::where('person_id', $worker->id)
            ->findOrFail($request->fsm_order_id);

        if (!class_exists(\Modules\FSMTimesheet\Models\FSMTimesheetLine::class)) {
            return response()->json([
                'message' => 'FSMTimesheet module not active. Entry not persisted.',
            ], 503);
        }

        $line = \Modules\FSMTimesheet\Models\FSMTimesheetLine::create([
            'company_id'   => $worker->company_id,
            'fsm_order_id' => $order->id,
            'user_id'      => $worker->id,
            'date'         => $request->date,
            'name'         => $request->input('name', "Job {$order->name}"),
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            // unit_amount auto-computed by FSMTimesheetLine::booted
        ]);

        return response()->json([
            'message'      => 'Timesheet entry saved.',
            'line'         => $line,
            'hours'        => $line->computed_hours,
        ], 201);
    }
}
