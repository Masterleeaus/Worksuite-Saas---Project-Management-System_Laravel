<?php

namespace Modules\ZoneManagement\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TitanGoExecutionBridge
{
    public function mirrorLocationPing(object $user, array $payload): void
    {
        if (!class_exists(\Modules\TitanGo\Models\TitanGoLocationPing::class)) {
            return;
        }

        $lat = $payload['lat'] ?? $payload['latitude'] ?? null;
        $lng = $payload['lng'] ?? $payload['longitude'] ?? null;

        if ($lat === null || $lng === null) {
            return;
        }

        \Modules\TitanGo\Models\TitanGoLocationPing::create([
            'company_id' => $user->company_id ?? null,
            'worker_id' => $user->id,
            'visit_id' => $this->resolveVisitId($payload),
            'latitude' => $lat,
            'longitude' => $lng,
            'accuracy' => $payload['accuracy'] ?? null,
            'tracking_mode' => $payload['tracking_mode'] ?? 'foreground',
            'created_at' => $payload['recorded_at'] ?? now(),
            'updated_at' => $payload['recorded_at'] ?? now(),
        ]);
    }

    public function mirrorRoutePoints(object $user, string $bookingId, array $points): void
    {
        if (!Schema::hasTable('titan_go_route_trails')) {
            return;
        }

        $now = now();
        $rows = [];

        foreach ($points as $point) {
            $rows[] = [
                'company_id' => $user->company_id ?? null,
                'worker_id' => $user->id,
                'visit_id' => $this->resolveVisitId($point),
                'booking_id' => $bookingId,
                'latitude' => $point['lat'],
                'longitude' => $point['lng'],
                'accuracy' => $point['accuracy'] ?? null,
                'speed' => $point['speed'] ?? null,
                'heading' => $point['heading'] ?? null,
                'sequence' => $point['sequence'] ?? 0,
                'recorded_at' => $point['recorded_at'] ?? $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('titan_go_route_trails')->insert($rows);
    }

    public function mirrorCheckIn(object $user, array $payload): void
    {
        if (!class_exists(\Modules\TitanGo\Models\TitanGoWorkerStatus::class)) {
            return;
        }

        $visitId = $this->resolveVisitId($payload);
        if (!$visitId) {
            return;
        }

        \Modules\TitanGo\Models\TitanGoWorkerStatus::create([
            'company_id' => $user->company_id ?? null,
            'worker_id' => $user->id,
            'visit_id' => $visitId,
            'status' => 'arrived',
            'notes' => $payload['notes'] ?? null,
        ]);

        if (class_exists(\Modules\FSMCore\Models\FSMOrder::class)) {
            \Modules\FSMCore\Models\FSMOrder::where('id', $visitId)
                ->where('person_id', $user->id)
                ->whereNull('date_start')
                ->update(['date_start' => now()]);
        }
    }

    public function mirrorCheckOut(object $user, array $payload): void
    {
        if (!class_exists(\Modules\TitanGo\Models\TitanGoWorkerStatus::class)) {
            return;
        }

        $visitId = $this->resolveVisitId($payload);
        if (!$visitId) {
            return;
        }

        \Modules\TitanGo\Models\TitanGoWorkerStatus::create([
            'company_id' => $user->company_id ?? null,
            'worker_id' => $user->id,
            'visit_id' => $visitId,
            'status' => 'job_complete',
            'notes' => $payload['notes'] ?? null,
        ]);

        if (class_exists(\Modules\FSMCore\Models\FSMOrder::class)) {
            \Modules\FSMCore\Models\FSMOrder::where('id', $visitId)
                ->where('person_id', $user->id)
                ->whereNull('date_end')
                ->update(['date_end' => now()]);
        }
    }

    private function resolveVisitId(array $payload): ?int
    {
        $candidate = $payload['visit_id'] ?? $payload['fsm_order_id'] ?? $payload['booking_id'] ?? null;
        if ($candidate === null || !is_numeric($candidate)) {
            return null;
        }

        return (int)$candidate;
    }
}

