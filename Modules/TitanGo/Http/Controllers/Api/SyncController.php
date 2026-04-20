<?php

namespace Modules\TitanGo\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\FSMCore\Models\FSMOrder;
use Modules\TitanGo\Models\NexusJobNote;
use Modules\TitanGo\Models\TitanGoLocationPing;
use Modules\TitanGo\Models\TitanGoIssue;
use Modules\TitanGo\Models\TitanGoWorkerStatus;
use Modules\TitanGo\Models\TitanGoChecklistCompletion;

/**
 * SyncController
 *
 * Offline sync replay endpoint.  Titan Go queues mutations locally while
 * offline and replays the batch when connectivity is restored.
 *
 * POST /api/nexus/v1/sync
 *
 * Body: {
 *   "queue": [
 *     {
 *       "id":        "local-uuid",
 *       "type":      "note|status|issue|location_ping|checkin|checkout|complete",
 *       "visit_id":  123,
 *       "payload":   { ... },
 *       "timestamp": 1700000000000
 *     },
 *     ...
 *   ]
 * }
 *
 * Returns per-item results so the client can clear successfully replayed items.
 */
class SyncController extends Controller
{
    public function replay(Request $request): JsonResponse
    {
        $request->validate([
            'queue'           => 'required|array|min:1|max:200',
            'queue.*.id'      => 'required|string',
            'queue.*.type'    => 'required|string',
            'queue.*.payload' => 'required|array',
        ]);

        $companyId = $request->user()->company_id;
        $workerId  = $request->user()->id;
        $results   = [];

        foreach ($request->queue as $item) {
            $localId = $item['id'];
            $type    = $item['type'];
            $payload = $item['payload'];
            $visitId = $item['visit_id'] ?? ($payload['visit_id'] ?? null);

            try {
                DB::beginTransaction();

                match ($type) {
                    'note'           => $this->replayNote($companyId, $workerId, (int) $visitId, $payload),
                    'status'         => $this->replayStatus($companyId, $workerId, (int) $visitId, $payload),
                    'issue'          => $this->replayIssue($companyId, $workerId, (int) $visitId, $payload),
                    'location_ping'  => $this->replayLocationPing($companyId, $workerId, (int) $visitId, $payload),
                    'route_point'    => $this->replayRoutePoint($companyId, $workerId, (int) $visitId, $payload),
                    'checkin'        => $this->replayCheckin((int) $visitId, $workerId),
                    'checkout'       => $this->replayCheckout((int) $visitId, $workerId),
                    'complete'       => $this->replayComplete((int) $visitId, $workerId),
                    'checklist_step' => $this->replayChecklistStep($companyId, $workerId, (int) $visitId, $payload),
                    default          => null,
                };

                DB::commit();
                $results[$localId] = ['status' => 'ok'];
            } catch (\Throwable $e) {
                DB::rollBack();
                $results[$localId] = ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        return response()->json(['results' => $results]);
    }

    private function replayNote(int $companyId, int $workerId, int $visitId, array $payload): void
    {
        NexusJobNote::create([
            'company_id'   => $companyId,
            'fsm_order_id' => $visitId,
            'worker_id'    => $workerId,
            'body'         => $payload['body'] ?? '',
        ]);
    }

    private function replayStatus(int $companyId, int $workerId, int $visitId, array $payload): void
    {
        TitanGoWorkerStatus::create([
            'company_id' => $companyId,
            'worker_id'  => $workerId,
            'visit_id'   => $visitId,
            'status'     => $payload['status'] ?? 'arrived',
            'notes'      => $payload['notes'] ?? null,
        ]);
    }

    private function replayIssue(int $companyId, int $workerId, int $visitId, array $payload): void
    {
        TitanGoIssue::create([
            'company_id'  => $companyId,
            'worker_id'   => $workerId,
            'visit_id'    => $visitId,
            'type'        => $payload['type'] ?? 'access_blocked',
            'description' => $payload['description'] ?? null,
            'status'      => 'open',
        ]);
    }

    private function replayLocationPing(int $companyId, int $workerId, int $visitId, array $payload): void
    {
        $lat = $payload['latitude'] ?? $payload['lat'] ?? null;
        $lng = $payload['longitude'] ?? $payload['lng'] ?? null;
        if ($lat === null || $lng === null) {
            return;
        }

        TitanGoLocationPing::create([
            'company_id' => $companyId,
            'worker_id' => $workerId,
            'visit_id' => $visitId ?: null,
            'latitude' => $lat,
            'longitude' => $lng,
            'accuracy' => $payload['accuracy'] ?? null,
            'tracking_mode' => $payload['tracking_mode'] ?? 'background',
            'created_at' => $payload['recorded_at'] ?? now(),
            'updated_at' => $payload['recorded_at'] ?? now(),
        ]);
    }

    private function replayRoutePoint(int $companyId, int $workerId, int $visitId, array $payload): void
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('titan_go_route_trails')) {
            return;
        }

        $lat = $payload['latitude'] ?? $payload['lat'] ?? null;
        $lng = $payload['longitude'] ?? $payload['lng'] ?? null;
        if ($lat === null || $lng === null) {
            return;
        }

        \Illuminate\Support\Facades\DB::table('titan_go_route_trails')->insert([
            'company_id' => $companyId,
            'worker_id' => $workerId,
            'visit_id' => $visitId ?: null,
            'booking_id' => isset($payload['booking_id']) ? (string)$payload['booking_id'] : null,
            'latitude' => $lat,
            'longitude' => $lng,
            'accuracy' => $payload['accuracy'] ?? null,
            'speed' => $payload['speed'] ?? null,
            'heading' => $payload['heading'] ?? null,
            'sequence' => (int)($payload['sequence'] ?? 0),
            'recorded_at' => $payload['recorded_at'] ?? now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function replayCheckin(int $visitId, int $workerId): void
    {
        FSMOrder::where('id', $visitId)
            ->where('person_id', $workerId)
            ->whereNull('date_start')
            ->update(['date_start' => now()]);
    }

    private function replayCheckout(int $visitId, int $workerId): void
    {
        FSMOrder::where('id', $visitId)
            ->where('person_id', $workerId)
            ->whereNull('date_end')
            ->update(['date_end' => now()]);
    }

    private function replayComplete(int $visitId, int $workerId): void
    {
        $order = FSMOrder::where('id', $visitId)
            ->where('person_id', $workerId)
            ->first();

        if (!$order) {
            return;
        }

        if (is_null($order->date_end)) {
            $order->date_end = now();
            $order->save();
        }
    }

    private function replayChecklistStep(int $companyId, int $workerId, int $visitId, array $payload): void
    {
        $idx = isset($payload['step_index']) ? (int) $payload['step_index'] : null;
        if ($idx === null) {
            return;
        }

        $existing = TitanGoChecklistCompletion::where('company_id', $companyId)
            ->where('visit_id', $visitId)
            ->where('worker_id', $workerId)
            ->where('step_index', $idx)
            ->first();

        $attributes = [
            'company_id'     => $companyId,
            'visit_id'       => $visitId,
            'worker_id'      => $workerId,
            'step_index'     => $idx,
            'instruction'    => $payload['instruction'] ?? null,
            'notes'          => $payload['notes'] ?? null,
            'photo_data'     => $payload['photo_data'] ?? null,
            'signature_data' => $payload['signature_data'] ?? null,
            'completed_at'   => now(),
        ];

        if ($existing) {
            $existing->update($attributes);
        } else {
            TitanGoChecklistCompletion::create($attributes);
        }
    }
}
