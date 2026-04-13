<?php

namespace Modules\NexusFieldOps\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\NexusFieldOps\Models\NexusLocationPing;

/**
 * NexusWorkerController
 *
 * Worker self-service endpoints for Titan Go:
 *   - GET  /api/nexus/v1/worker/me          — worker profile
 *   - PUT  /api/nexus/v1/worker/fcm-token   — register/update device push token
 *   - POST /api/nexus/v1/worker/status      — quick-action status signal
 *   - POST /api/nexus/v1/worker/location    — GPS heartbeat ping
 */
class NexusWorkerController extends Controller
{
    /**
     * GET /api/nexus/v1/worker/me
     *
     * Returns the authenticated worker's profile plus active token info.
     */
    public function me(Request $request): JsonResponse
    {
        $worker = $request->user()->load('roles');

        return response()->json([
            'id'             => $worker->id,
            'name'           => $worker->name,
            'email'          => $worker->email,
            'company_id'     => $worker->company_id,
            'nexus_fcm_token' => $worker->nexus_fcm_token,
            'roles'          => $worker->roles->pluck('name'),
        ]);
    }

    /**
     * PUT /api/nexus/v1/worker/fcm-token
     *
     * Body: { "fcm_token": "...", "device_id": "..." }
     * Stores the FCM token in users.nexus_fcm_token so push notifications
     * can be dispatched per worker per tenant.
     */
    public function updateFcmToken(Request $request): JsonResponse
    {
        $request->validate([
            'fcm_token' => 'required|string|max:255',
            'device_id' => 'nullable|string|max:255',
        ]);

        $request->user()->update([
            'nexus_fcm_token' => $request->fcm_token,
        ]);

        return response()->json(['message' => 'FCM token updated.']);
    }

    /**
     * POST /api/nexus/v1/worker/status
     *
     * Quick-action status signals from the Titan Go bottom action bar.
     *
     * Body: {
     *   "signal":       "arrived|running_late|access_issue|need_supplies|job_complete|report_issue",
     *   "fsm_order_id": 123,           // optional — links signal to an open order
     *   "note":         "..."          // optional free-text
     * }
     *
     * The signal is persisted as a NexusJobNote so it is visible to dispatchers
     * and is surfaced in the job timeline.
     */
    public function quickStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'signal'       => 'required|in:arrived,running_late,access_issue,need_supplies,job_complete,report_issue',
            'fsm_order_id' => 'nullable|integer|exists:fsm_orders,id',
            'note'         => 'nullable|string|max:1000',
        ]);

        $worker = $request->user();

        $noteType = match ($validated['signal']) {
            'access_issue'   => 'access',
            'need_supplies'  => 'supply',
            'report_issue'   => 'issue',
            default          => 'general',
        };

        $body = '[Quick Action: ' . str_replace('_', ' ', strtoupper($validated['signal'])) . ']';
        if (!empty($validated['note'])) {
            $body .= ' — ' . $validated['note'];
        }

        $note = null;
        if (!empty($validated['fsm_order_id'])) {
            $note = \Modules\NexusFieldOps\Models\NexusJobNote::create([
                'company_id'   => $worker->company_id,
                'fsm_order_id' => $validated['fsm_order_id'],
                'worker_id'    => $worker->id,
                'body'         => $body,
                'note_type'    => $noteType,
            ]);
        }

        return response()->json([
            'message' => 'Status signal received.',
            'signal'  => $validated['signal'],
            'note_id' => optional($note)->id,
        ]);
    }

    /**
     * POST /api/nexus/v1/worker/location
     *
     * GPS heartbeat from Titan Go during active dispatching / on-site.
     *
     * Body: {
     *   "latitude":      -33.8688,
     *   "longitude":     151.2093,
     *   "accuracy":      5.0,
     *   "fsm_order_id":  123,          // optional
     *   "tracking_mode": "foreground"  // foreground|background|heartbeat
     * }
     */
    public function locationPing(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'latitude'      => 'required|numeric|between:-90,90',
            'longitude'     => 'required|numeric|between:-180,180',
            'accuracy'      => 'nullable|numeric|min:0',
            'fsm_order_id'  => 'nullable|integer|exists:fsm_orders,id',
            'tracking_mode' => 'nullable|in:foreground,background,heartbeat',
        ]);

        $worker = $request->user();

        NexusLocationPing::create([
            'company_id'    => $worker->company_id,
            'worker_id'     => $worker->id,
            'fsm_order_id'  => $validated['fsm_order_id'] ?? null,
            'latitude'      => $validated['latitude'],
            'longitude'     => $validated['longitude'],
            'accuracy'      => $validated['accuracy'] ?? null,
            'tracking_mode' => $validated['tracking_mode'] ?? 'foreground',
            'pinged_at'     => now(),
        ]);

        return response()->json(['message' => 'Location recorded.'], 201);
    }
}
