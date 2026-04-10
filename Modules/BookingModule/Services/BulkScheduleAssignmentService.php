<?php

namespace Modules\BookingModule\Services;

use Modules\BookingModule\Entities\Schedule;

class BulkScheduleAssignmentService
{
    public function __construct(
        protected ScheduleAssignmentService $assignmentService,
        protected ScheduleCapacityService $capacityService
    ) {}

    /**
     * Bulk assign/unassign schedules.
     *
     * @return array{assigned:int,skipped:int,errors:array}
     */
    public function bulkAssign(array $scheduleIds, ?int $userId, ?string $note, callable $canAssign): array
    {
        $results = ['assigned' => 0, 'skipped' => 0, 'errors' => []];

        $schedules = Schedule::query()->whereIn('id', $scheduleIds)->get();
        foreach ($schedules as $schedule) {
            if (!$canAssign($schedule)) {
                $results['skipped']++;
                continue;
            }

            [$ok, $message] = $this->capacityService->validateAssignment($schedule, $userId);
            if (!$ok) {
                $results['errors'][] = "#{$schedule->id}: {$message}";
                $results['skipped']++;
                continue;
            }

            $this->assignmentService->assign($schedule, $userId, $note);
            $results['assigned']++;
        }

        return $results;
    }
}
