<?php

namespace Modules\BookingModule\Services;

use Carbon\Carbon;
use Modules\BookingModule\Entities\AppointmentNotificationPreference;

class NotificationGate
{
    public function canNotify(?int $userId, ?int $companyId, string $eventKey): bool
    {
        if (!$userId) {
            return false;
        }

        $pref = AppointmentNotificationPreference::query()
            ->where('user_id', $userId)
            ->where(function ($q) use ($companyId) {
                $companyId ? $q->where('company_id', $companyId) : $q->whereNull('company_id');
            })
            ->first();

        if (!$pref) {
            return true; // default allow
        }

        // Quiet hours check (local server time)
        if ($pref->quiet_hours_start && $pref->quiet_hours_end) {
            try {
                $now = Carbon::now();
                $start = Carbon::createFromTimeString($pref->quiet_hours_start);
                $end = Carbon::createFromTimeString($pref->quiet_hours_end);
                // if range wraps midnight
                $inQuiet = $start->lessThan($end)
                    ? $now->between($start, $end)
                    : ($now->greaterThanOrEqualTo($start) || $now->lessThanOrEqualTo($end));
                if ($inQuiet) {
                    return false;
                }
            } catch (\Throwable $e) {
                // ignore parsing errors
            }
        }

        return match ($eventKey) {
            'assigned' => (bool)$pref->notify_assigned,
            'reassigned' => (bool)$pref->notify_reassigned,
            'unassigned' => (bool)$pref->notify_unassigned,
            'rescheduled' => (bool)$pref->notify_rescheduled,
            'cancelled' => (bool)$pref->notify_cancelled,
            default => true,
        };
    }
}
