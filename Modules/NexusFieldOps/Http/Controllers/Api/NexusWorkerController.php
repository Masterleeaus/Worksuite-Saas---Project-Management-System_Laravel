<?php

namespace Modules\NexusFieldOps\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * NexusWorkerController
 *
 * Handles field-worker profile actions specific to the Nexus Field Ops app,
 * currently limited to FCM (Firebase Cloud Messaging) device-token registration
 * so that dispatchers can send push notifications to the worker's phone.
 *
 * Nexus app endpoint: PUT /v1/worker/fcm-token
 * Backend endpoint  : PUT /api/nexus/v1/worker/fcm-token
 */
class NexusWorkerController extends Controller
{
    /**
     * PUT /api/nexus/v1/worker/fcm-token
     *
     * Registers (or replaces) the FCM device token for the authenticated worker.
     * The Flutter app calls this after login and whenever
     * FirebaseMessaging.instance.onTokenRefresh fires.
     *
     * Body: { "fcm_token": "eXpLic..." }
     */
    public function updateFcmToken(Request $request): JsonResponse
    {
        $request->validate([
            'fcm_token' => 'required|string|max:512',
        ]);

        $request->user()->forceFill([
            'nexus_fcm_token' => $request->input('fcm_token'),
        ])->save();

        return response()->json(['message' => 'FCM token updated.']);
    }
}
