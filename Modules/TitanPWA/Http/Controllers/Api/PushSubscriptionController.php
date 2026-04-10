<?php

namespace Modules\TitanPWA\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\TitanPWA\Models\PushSubscription;

/**
 * PushSubscriptionController
 *
 * Manages Web Push API subscriptions (VAPID).
 *
 * Endpoints
 *   POST   /api/titanpwa/push/subscribe    — store or update a subscription
 *   DELETE /api/titanpwa/push/unsubscribe  — remove a subscription
 *   GET    /api/titanpwa/push/vapid-key    — return the VAPID public key
 */
class PushSubscriptionController extends Controller
{
    /**
     * Return the VAPID public key so the browser can subscribe.
     */
    public function vapidPublicKey(): JsonResponse
    {
        $key = config('titanpwa.vapid_public_key', '');

        if (empty($key)) {
            return response()->json(['error' => 'VAPID keys not configured. Run: php artisan titanpwa:vapid-keys'], 503);
        }

        return response()->json(['vapid_public_key' => $key]);
    }

    /**
     * Store or update a push subscription for the authenticated user.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subscription'                   => ['required', 'array'],
            'subscription.endpoint'          => ['required', 'string', 'url'],
            'subscription.keys'              => ['required', 'array'],
            'subscription.keys.p256dh'       => ['required', 'string'],
            'subscription.keys.auth'         => ['required', 'string'],
        ]);

        $sub = $validated['subscription'];

        // Web Push endpoints must always use HTTPS (RFC 8030)
        if (! str_starts_with($sub['endpoint'], 'https://')) {
            return response()->json(['error' => 'Push subscription endpoint must use HTTPS.'], 422);
        }

        PushSubscription::updateOrCreate(
            [
                'user_id'  => Auth::id(),
                'endpoint' => $sub['endpoint'],
            ],
            [
                'p256dh'     => $sub['keys']['p256dh'],
                'auth_token' => $sub['keys']['auth'],
                'user_agent' => $request->userAgent(),
            ]
        );

        return response()->json(['status' => 'subscribed'], 201);
    }

    /**
     * Remove a push subscription for the authenticated user.
     */
    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => ['required', 'string', 'url'],
        ]);

        PushSubscription::where('user_id', Auth::id())
            ->where('endpoint', $validated['endpoint'])
            ->delete();

        return response()->json(['status' => 'unsubscribed']);
    }
}
