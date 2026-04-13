<?php

namespace Modules\NexusFieldOps\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\NexusFieldOps\Models\NexusJobNote;

/**
 * NexusJobController
 *
 * Handles job-level notes that field workers attach to orders from the
 * Nexus Field Ops Flutter app. Notes are visible to dispatchers in the
 * back-office.
 *
 * Nexus app endpoints:
 *   GET  /v1/jobs/{id}/notes
 *   POST /v1/jobs/{id}/notes
 *
 * Backend endpoints:
 *   GET  /api/nexus/v1/jobs/{id}/notes
 *   POST /api/nexus/v1/jobs/{id}/notes
 */
class NexusJobController extends Controller
{
    /**
     * GET /api/nexus/v1/jobs/{id}/notes
     *
     * Returns all notes for the given order.
     * The authenticated worker must be assigned to the order.
     */
    public function listNotes(Request $request, int $id): JsonResponse
    {
        $order = FSMOrder::findOrFail($id);
        $this->authorizeWorker($request, $order);

        $notes = NexusJobNote::with('author')
            ->where('fsm_order_id', $id)
            ->orderBy('created_at')
            ->get()
            ->map(fn(NexusJobNote $n) => [
                'id'         => $n->id,
                'body'       => $n->body,
                'author'     => $n->author ? ['id' => $n->author->id, 'name' => $n->author->name] : null,
                'created_at' => $n->created_at,
            ]);

        return response()->json(['data' => $notes]);
    }

    /**
     * POST /api/nexus/v1/jobs/{id}/notes
     *
     * Adds a new text note to the order.
     * Body: { "body": "Rear gate was locked — entered via side door." }
     */
    public function addNote(Request $request, int $id): JsonResponse
    {
        $order = FSMOrder::findOrFail($id);
        $this->authorizeWorker($request, $order);

        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $note = NexusJobNote::create([
            'fsm_order_id' => $id,
            'user_id'      => $request->user()->id,
            'body'         => $request->input('body'),
        ]);

        return response()->json([
            'message' => 'Note added.',
            'note'    => [
                'id'         => $note->id,
                'body'       => $note->body,
                'created_at' => $note->created_at,
            ],
        ], 201);
    }

    private function authorizeWorker(Request $request, FSMOrder $order): void
    {
        if ((int) $order->person_id !== (int) $request->user()->id) {
            abort(403, 'You are not assigned to this order.');
        }
    }
}
