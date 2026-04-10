<?php

namespace Modules\TitanPWA\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\TitanPWA\Models\SyncQueueItem;

/**
 * SyncQueueController
 *
 * Server-side companion to the client-side IndexedDB sync queue.
 * When connectivity is restored the client replays queued actions; this
 * controller provides the endpoints those replayed requests hit and also
 * exposes a queue listing/management API for admin inspection.
 *
 * Endpoints (all prefixed /api/titanpwa/sync)
 *   GET    /queue         — list pending items for the auth user
 *   POST   /queue         — enqueue a new offline action
 *   POST   /process       — trigger processing of all pending items
 *   DELETE /queue/{id}    — delete a specific item
 */
class SyncQueueController extends Controller
{
    /**
     * List pending sync-queue items for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $items = SyncQueueItem::where('user_id', Auth::id())
            ->orderBy('created_at')
            ->get(['id', 'type', 'payload', 'status', 'attempts', 'created_at']);

        return response()->json([
            'pending' => $items->where('status', 'pending')->values(),
            'failed'  => $items->where('status', 'failed')->values(),
            'count'   => $items->count(),
        ]);
    }

    /**
     * Add an offline action to the server-side sync queue.
     * Called by the client when it comes back online and replays queued items.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type'    => ['required', 'string', 'max:100'],
            'payload' => ['required', 'array'],
        ]);

        $item = SyncQueueItem::create([
            'user_id' => Auth::id(),
            'type'    => $validated['type'],
            'payload' => $validated['payload'],
            'status'  => 'pending',
        ]);

        return response()->json(['id' => $item->id, 'status' => 'queued'], 201);
    }

    /**
     * Process all pending items for the authenticated user.
     *
     * In production this would dispatch jobs; here we mark items as
     * processed immediately to unblock the client.
     */
    public function process(Request $request): JsonResponse
    {
        $updated = SyncQueueItem::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->update(['status' => 'processed', 'attempts' => DB::raw('attempts + 1')]);

        return response()->json(['processed' => $updated]);
    }

    /**
     * Remove a specific sync-queue item (e.g., after successful replay).
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = SyncQueueItem::where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();

        if (! $deleted) {
            return response()->json(['error' => 'Item not found or not yours'], 404);
        }

        return response()->json(['status' => 'deleted']);
    }
}
