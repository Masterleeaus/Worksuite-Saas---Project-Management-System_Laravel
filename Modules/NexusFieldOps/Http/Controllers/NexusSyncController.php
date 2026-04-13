<?php

namespace Modules\NexusFieldOps\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\NexusFieldOps\Models\NexusJobIssue;
use Modules\NexusFieldOps\Models\NexusJobNote;
use Modules\NexusFieldOps\Models\NexusLocationPing;

/**
 * NexusSyncController
 *
 * POST /api/nexus/v1/sync
 *
 * Titan Go workers operate offline and batch up actions in a local queue.
 * When connectivity is restored, the app POSTs a bundle of queued operations
 * to this endpoint which replays them in order.
 *
 * Supported operation types:
 *   - note        : create a NexusJobNote
 *   - issue       : create a NexusJobIssue
 *   - location    : create NexusLocationPing rows
 *   - checkin     : FSMOrder date_start
 *   - checkout    : FSMOrder date_end
 *   - stage       : FSMOrder stage_id
 *   - complete    : FSMOrder completion stage
 *
 * Request body (JSON):
 * {
 *   "operations": [
 *     {
 *       "type":       "note",
 *       "client_id":  "uuid-from-device",   // echoed back for deduplication
 *       "payload":    { "fsm_order_id": 1, "body": "...", "note_type": "general" }
 *     },
 *     ...
 *   ]
 * }
 *
 * Response:
 * {
 *   "processed": 3,
 *   "failed":    1,
 *   "results": [
 *     { "client_id": "...", "status": "ok",    "server_id": 42 },
 *     { "client_id": "...", "status": "error", "message": "..." }
 *   ]
 * }
 */
class NexusSyncController extends Controller
{
    private const MAX_OPERATIONS = 200;

    /**
     * POST /api/nexus/v1/sync
     */
    public function replay(Request $request): JsonResponse
    {
        $request->validate([
            'operations'              => 'required|array|max:' . self::MAX_OPERATIONS,
            'operations.*.type'       => 'required|string',
            'operations.*.client_id'  => 'nullable|string|max:64',
            'operations.*.payload'    => 'required|array',
        ]);

        $worker  = $request->user();
        $results = [];
        $ok      = 0;
        $failed  = 0;

        foreach ($request->operations as $op) {
            $clientId = $op['client_id'] ?? null;
            try {
                $serverId = $this->dispatch($worker, $op['type'], $op['payload']);
                $results[] = ['client_id' => $clientId, 'status' => 'ok', 'server_id' => $serverId];
                $ok++;
            } catch (\Throwable $e) {
                $results[] = ['client_id' => $clientId, 'status' => 'error', 'message' => $e->getMessage()];
                $failed++;
            }
        }

        return response()->json([
            'processed' => $ok,
            'failed'    => $failed,
            'results'   => $results,
        ]);
    }

    /**
     * Dispatch a single sync operation. Returns the new server-side record ID.
     */
    private function dispatch($worker, string $type, array $payload): ?int
    {
        return match ($type) {
            'note'     => $this->syncNote($worker, $payload),
            'issue'    => $this->syncIssue($worker, $payload),
            'location' => $this->syncLocation($worker, $payload),
            'checkin'  => $this->syncCheckin($worker, $payload),
            'checkout' => $this->syncCheckout($worker, $payload),
            'stage'    => $this->syncStage($worker, $payload),
            'complete' => $this->syncComplete($worker, $payload),
            default    => throw new \InvalidArgumentException("Unknown sync operation type: {$type}"),
        };
    }

    private function syncNote($worker, array $p): int
    {
        $note = NexusJobNote::create([
            'company_id'     => $worker->company_id,
            'fsm_order_id'   => (int) ($p['fsm_order_id'] ?? 0),
            'worker_id'      => $worker->id,
            'body'           => $p['body'] ?? '',
            'note_type'      => $p['note_type'] ?? 'general',
            'synced_offline' => true,
        ]);
        return $note->id;
    }

    private function syncIssue($worker, array $p): int
    {
        $issue = NexusJobIssue::create([
            'company_id'     => $worker->company_id,
            'fsm_order_id'   => (int) ($p['fsm_order_id'] ?? 0),
            'worker_id'      => $worker->id,
            'issue_type'     => $p['issue_type'] ?? 'equipment_missing',
            'description'    => $p['description'] ?? null,
            'status'         => 'open',
            'synced_offline' => true,
        ]);
        return $issue->id;
    }

    private function syncLocation($worker, array $p): int
    {
        $ping = NexusLocationPing::create([
            'company_id'    => $worker->company_id,
            'worker_id'     => $worker->id,
            'fsm_order_id'  => $p['fsm_order_id'] ?? null,
            'latitude'      => (float) ($p['latitude'] ?? 0),
            'longitude'     => (float) ($p['longitude'] ?? 0),
            'accuracy'      => isset($p['accuracy']) ? (float) $p['accuracy'] : null,
            'tracking_mode' => $p['tracking_mode'] ?? 'heartbeat',
            'pinged_at'     => $p['pinged_at'] ?? now()->toDateTimeString(),
        ]);
        return $ping->id;
    }

    private function syncCheckin($worker, array $p): int
    {
        $order = FSMOrder::where('company_id', $worker->company_id)
            ->where('person_id', $worker->id)
            ->findOrFail((int) ($p['fsm_order_id'] ?? 0));

        $order->date_start = $p['date_start'] ?? now()->toDateTimeString();
        $order->save();
        return $order->id;
    }

    private function syncCheckout($worker, array $p): int
    {
        $order = FSMOrder::where('company_id', $worker->company_id)
            ->where('person_id', $worker->id)
            ->findOrFail((int) ($p['fsm_order_id'] ?? 0));

        $order->date_end = $p['date_end'] ?? now()->toDateTimeString();
        $order->save();
        return $order->id;
    }

    private function syncStage($worker, array $p): int
    {
        $order = FSMOrder::where('company_id', $worker->company_id)
            ->where('person_id', $worker->id)
            ->findOrFail((int) ($p['fsm_order_id'] ?? 0));

        $order->stage_id = (int) ($p['stage_id'] ?? $order->stage_id);
        $order->save();
        return $order->id;
    }

    private function syncComplete($worker, array $p): int
    {
        $order = FSMOrder::where('company_id', $worker->company_id)
            ->where('person_id', $worker->id)
            ->findOrFail((int) ($p['fsm_order_id'] ?? 0));

        $completionStage = \Modules\FSMCore\Models\FSMStage::where('is_completion_stage', true)
            ->orderBy('sequence')
            ->first();

        if ($completionStage) {
            $order->stage_id = $completionStage->id;
        }
        if (is_null($order->date_end)) {
            $order->date_end = $p['date_end'] ?? now()->toDateTimeString();
        }
        $order->save();
        return $order->id;
    }
}
