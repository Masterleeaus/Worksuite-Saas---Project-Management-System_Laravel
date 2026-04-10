<?php

namespace Modules\BookingModule\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\BookingModule\Entities\AppointmentSetting;
use Modules\BookingModule\Entities\AppointmentStaffCapacity;
use Modules\BookingModule\Entities\Schedule;

class ScheduleCapacityService
{
    public function canAssignUserToSchedule(Schedule $schedule, int $userId): array
    {
        $settings = AppointmentSetting::where('created_by', $schedule->created_by)
            ->where('workspace', $schedule->workspace)->first();

        $staff = AppointmentStaffCapacity::where('created_by', $schedule->created_by)
            ->where('workspace', $schedule->workspace)
            ->where('user_id', $userId)->first();

        $maxPerDay = $staff?->max_per_day ?? $settings?->default_max_per_day;
        $maxPerSlot = $staff?->max_per_slot ?? $settings?->default_max_per_slot;

        $countPendingToo = (bool)($staff?->count_pending_too ?? $settings?->count_pending_too ?? false);

        // Count schedules on the same date for that user
        if ($maxPerDay !== null) {
            $q = Schedule::query()
                ->where('created_by', $schedule->created_by)
                ->where('workspace', $schedule->workspace)
                ->whereDate('date', $schedule->date)
                ->where(function($qq) use ($userId) {
                    $qq->where('assigned_to', $userId)->orWhere('user_id', $userId);
                });

            if (!$countPendingToo) {
                $q->where('status', 'Approved');
            } else {
                $q->whereIn('status', ['Approved', 'Pending']);
            }

            $count = (int)$q->count();
            if ($count >= (int)$maxPerDay) {
                return [false, __('bookingmodule::capacity.errors.max_per_day')];
            }
        }

        // Count schedules in the same slot (overlap) for that user
        if ($maxPerSlot !== null && $schedule->start_time && $schedule->end_time) {
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

            if (!$countPendingToo) {
                $q->where('status', 'Approved');
            } else {
                $q->whereIn('status', ['Approved', 'Pending']);
            }

            $count = (int)$q->count();
            if ($count >= (int)$maxPerSlot) {
                return [false, __('bookingmodule::capacity.errors.max_per_slot')];
            }
        }

        return [true, null];
    }

    public function getEffectiveCapacity(int $createdBy, int $workspace, int $userId): array
    {
        $settings = AppointmentSetting::where('created_by', $createdBy)
            ->where('workspace', $workspace)->first();

        $staff = AppointmentStaffCapacity::where('created_by', $createdBy)
            ->where('workspace', $workspace)
            ->where('user_id', $userId)->first();

        return [
            'max_per_day' => $staff?->max_per_day ?? $settings?->default_max_per_day,
            'max_per_slot' => $staff?->max_per_slot ?? $settings?->default_max_per_slot,
            'enforce_conflicts' => (bool)($staff?->enforce_conflicts ?? $settings?->enforce_conflicts ?? true),
            'count_pending_too' => (bool)($staff?->count_pending_too ?? $settings?->count_pending_too ?? false),
        ];
    }
}
