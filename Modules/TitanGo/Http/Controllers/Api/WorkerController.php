<?php

namespace Modules\TitanGo\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * WorkerController
 *
 * Worker profile and FCM token management.
 *
 * GET  /api/nexus/v1/worker/profile   — current worker profile
 * PUT  /api/nexus/v1/worker/fcm-token — register FCM push token + device ID
 */
class WorkerController extends Controller
{
    /**
     * GET /api/nexus/v1/worker/profile
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'company_id' => $user->company_id,
            'device_id'  => $user->titan_go_device_id,
        ]);
    }

    /**
     * PUT /api/nexus/v1/worker/fcm-token
     *
     * Body: { "fcm_token": "...", "device_id": "..." }
     *
     * Registers the FCM device token for the authenticated worker so that
     * push notifications can be delivered via Firebase Cloud Messaging.
     * Also stores the device_id for multi-device tenant header validation.
     */
    public function updateFcmToken(Request $request): JsonResponse
    {
        $request->validate([
            'fcm_token' => 'required|string|max:512',
            'device_id' => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $user->titan_go_fcm_token = $request->fcm_token;

        if ($request->filled('device_id')) {
            $user->titan_go_device_id = $request->device_id;
        }

        $user->save();

        return response()->json(['message' => 'FCM token registered.']);
    }
}
