<?php

namespace Modules\BookingModule\Services;

use Illuminate\Support\Facades\DB;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Entities\ScheduleAssignment;

class LegacyWoAppointmentsImportService
{
    /**
     * Import legacy rows (best-effort). Safe: will not run unless user executes the command.
     *
     * @return array{int,int} [imported, skipped]
     */
    public function run(bool $dryRun = true, ?int $limit = null, ?callable $log = null): array
    {
        $q = DB::table('wo_service_appointments')->orderBy('id');
        if ($limit) $q->limit($limit);
        $rows = $q->get();

        $imported = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            // Skip if we already have a schedule with same unique_id marker.
            $unique = 'legacy-wo-'.$row->id;
            if (Schedule::query()->where('unique_id', $unique)->exists()) {
                $skipped++;
                $log && $log("Skip wo_service_appointments#{$row->id} (already imported)");
                continue;
            }

            $payload = [
                'unique_id' => $unique,
                'assigned_to' => $row->technician_id ?? null,
                'assignment_status' => ($row->technician_id ?? null) ? 'assigned' : 'unassigned',
                'starts_at' => $row->starts_at ?? null,
                'ends_at' => $row->ends_at ?? null,
                'location' => $row->location ?? null,
                'status' => $row->status ?? 'Pending',
                'workspace' => null,
                'created_by' => 1,
            ];

            if ($row->starts_at) {
                $payload['date'] = date('Y-m-d', strtotime($row->starts_at));
                $payload['start_time'] = date('H:i:s', strtotime($row->starts_at));
            }
            if ($row->ends_at) {
                $payload['end_time'] = date('H:i:s', strtotime($row->ends_at));
            }

            if ($dryRun) {
                $imported++;
                $log && $log("Dry-run import wo_service_appointments#{$row->id}");
                continue;
            }

            $schedule = Schedule::create($payload);

            // Write history event
            ScheduleAssignment::create([
                'schedule_id' => $schedule->id,
                'from_user_id' => null,
                'to_user_id' => $row->technician_id ?? null,
                'action' => ($row->technician_id ?? null) ? 'assign' : 'import',
                'note' => 'Imported from legacy wo_service_appointments',
                'created_by' => 1,
                'workspace' => null,
            ]);

            $imported++;
        }

        return [$imported, $skipped];
    }
}
