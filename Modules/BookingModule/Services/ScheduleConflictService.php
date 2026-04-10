<?php

namespace Modules\BookingModule\Services;

use Modules\BookingModule\Entities\Schedule;

class ScheduleConflictService
{
    public function hasConflict(Schedule $schedule, int $userId, bool $countPendingToo = false): bool
    {
        if (!$schedule->date || !$schedule->start_time || !$schedule->end_time) {
            return false;
        }

        $q = Schedule::query()
            ->where('created_by', $schedule->created_by)
            ->where('workspace', $schedule->workspace)
            ->whereDate('date', $schedule->date)
            ->where(function($qq) use ($userId) {
                $qq->where('assigned_to', $userId)->orWhere('user_id', $userId);
            })
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->where(function($qq) use ($schedule) {
                $qq->where('start_time', '<', $schedule->end_time)
                   ->where('end_time', '>', $schedule->start_time);
            });

        if ($schedule->id) {
            $q->where('id', '!=', $schedule->id);
        }

        if ($countPendingToo) {
            $q->whereIn('status', ['Approved', 'Pending']);
        } else {
            $q->where('status', 'Approved');
        }

        return $q->exists();
    }
}
