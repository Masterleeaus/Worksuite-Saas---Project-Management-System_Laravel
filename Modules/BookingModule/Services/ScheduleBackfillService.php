<?php

namespace Modules\BookingModule\Services;

use Modules\BookingModule\Entities\Schedule;

class ScheduleBackfillService
{
    /**
     * @return array{int,int} [updated, skipped]
     */
    public function backfill(bool $dryRun = true, ?int $limit = null): array
    {
        $q = Schedule::query()->whereNull('starts_at')->orWhereNull('ends_at')->orderBy('id');
        if ($limit) $q->limit($limit);
        $rows = $q->get();

        $updated = 0;
        $skipped = 0;

        foreach ($rows as $s) {
            $starts = $s->starts_at ?: ($s->date && $s->start_time ? ($s->date.' '.$s->start_time) : null);
            $ends = $s->ends_at ?: ($s->date && $s->end_time ? ($s->date.' '.$s->end_time) : null);

            if (!$starts && !$ends) {
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $updated++;
                continue;
            }

            if ($starts) $s->starts_at = $starts;
            if ($ends) $s->ends_at = $ends;
            $s->save();
            $updated++;
        }

        return [$updated, $skipped];
    }
}
