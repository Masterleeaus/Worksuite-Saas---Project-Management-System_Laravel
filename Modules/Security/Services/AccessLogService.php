<?php

namespace Modules\Security\Services;

use Modules\Security\Entities\AccessLog;
use Modules\Security\Entities\AccessCard;
use Modules\Security\Entities\InOutPermit;
use Modules\Security\Entities\WorkPermit;
use Modules\Security\Entities\Parking;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * AccessLogService
 * Records and manages real-time access events
 * Works with AuditLog for comprehensive audit trail
 */
class AccessLogService
{
    /**
     * Log badge swipe event
     */
    public function logBadgeSwipe(AccessCard $card, $location, $status = 'granted', $reason = null)
    {
        return AccessLog::create([
            'company_id' => $card->company_id,
            'unit_id' => $card->unit_id,
            'access_card_id' => $card->id,
            'event_type' => AccessLog::EVENT_BADGE_SWIPE,
            'status' => $status,
            'location' => $location,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'reason_denied' => $reason,
            'timestamp' => now(),
        ]);
    }

    /**
     * Log entry attempt (permit presentation)
     */
    public function logEntryAttempt(InOutPermit $permit, $success = true, $location = null, $reason = null)
    {
        return AccessLog::create([
            'company_id' => $permit->company_id,
            'unit_id' => $permit->unit_id,
            'inout_permit_id' => $permit->id,
            'event_type' => AccessLog::EVENT_PERMIT_PRESENTED,
            'status' => $success ? AccessLog::STATUS_GRANTED : AccessLog::STATUS_DENIED,
            'location' => $location ?? 'main_entrance',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'reason_denied' => $reason,
            'timestamp' => now(),
        ]);
    }

    /**
     * Log work permit entry
     */
    public function logWorkPermitEntry(WorkPermit $permit, $location = null, $checkedInBy = null)
    {
        return AccessLog::create([
            'company_id' => $permit->company_id,
            'unit_id' => $permit->unit_id,
            'work_permit_id' => $permit->id,
            'user_id' => $checkedInBy ?? auth()->id(),
            'event_type' => AccessLog::EVENT_ENTRY_GRANTED,
            'status' => AccessLog::STATUS_GRANTED,
            'location' => $location,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);
    }

    /**
     * Log vehicle gate entry
     */
    public function logVehicleEntry(Parking $parking, $location = null)
    {
        return AccessLog::create([
            'company_id' => $parking->company_id,
            'unit_id' => $parking->unit_id,
            'parking_id' => $parking->id,
            'event_type' => AccessLog::EVENT_VEHICLE_ENTRY,
            'status' => AccessLog::STATUS_GRANTED,
            'location' => $location ?? 'vehicle_gate',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);
    }

    /**
     * Log denied access
     */
    public function logAccessDenied($entity, $entityType, $location, $reason)
    {
        $log = new AccessLog([
            'company_id' => $entity->company_id,
            'unit_id' => $entity->unit_id,
            'event_type' => AccessLog::EVENT_ENTRY_DENIED,
            'status' => AccessLog::STATUS_DENIED,
            'location' => $location,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'reason_denied' => $reason,
            'timestamp' => now(),
        ]);

        // Set correct relationship based on entity type
        if ($entityType === 'access_card') {
            $log->access_card_id = $entity->id;
        } elseif ($entityType === 'permit') {
            $log->inout_permit_id = $entity->id;
        } elseif ($entityType === 'work_permit') {
            $log->work_permit_id = $entity->id;
        }

        return $log->save();
    }

    /**
     * Log exit event
     */
    public function logExit($entity, $entityType, $location, $startTime = null)
    {
        $duration = null;
        if ($startTime) {
            $duration = now()->diffInSeconds(Carbon::parse($startTime));
        }

        $log = new AccessLog([
            'company_id' => $entity->company_id,
            'unit_id' => $entity->unit_id,
            'event_type' => AccessLog::EVENT_EXIT,
            'status' => AccessLog::STATUS_GRANTED,
            'location' => $location,
            'duration_seconds' => $duration,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);

        if ($entityType === 'access_card') {
            $log->access_card_id = $entity->id;
        } elseif ($entityType === 'permit') {
            $log->inout_permit_id = $entity->id;
        } elseif ($entityType === 'work_permit') {
            $log->work_permit_id = $entity->id;
        }

        return $log->save();
    }

    /**
     * Get access trail for entity (complete history)
     */
    public function getAccessTrail($entity, $entityType, $limit = 50)
    {
        $query = AccessLog::query();

        if ($entityType === 'access_card') {
            $query->where('access_card_id', $entity->id);
        } elseif ($entityType === 'permit') {
            $query->where('inout_permit_id', $entity->id);
        } elseif ($entityType === 'work_permit') {
            $query->where('work_permit_id', $entity->id);
        } elseif ($entityType === 'parking') {
            $query->where('parking_id', $entity->id);
        }

        return $query->recent()->limit($limit)->get();
    }

    /**
     * Get access attempts for location
     */
    public function getLocationAccessLog($location, $from, $to, $limit = 100)
    {
        return AccessLog::where('location', $location)
            ->byDateRange($from, $to)
            ->recent()
            ->limit($limit)
            ->get();
    }

    /**
     * Get denied access attempts (security alert)
     */
    public function getDeniedAttempts($unit_id = null, $limit = 20)
    {
        $query = AccessLog::denied()->recent();

        if ($unit_id) {
            $query->where('unit_id', $unit_id);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Get activity summary for dashboard
     */
    public function getActivitySummary($company_id, $days = 7)
    {
        $fromDate = now()->subDays($days)->startOfDay();
        $toDate = now()->endOfDay();

        return [
            'total_entries' => AccessLog::where('company_id', $company_id)
                ->byDateRange($fromDate, $toDate)
                ->granted()
                ->count(),
            'total_denials' => AccessLog::where('company_id', $company_id)
                ->byDateRange($fromDate, $toDate)
                ->denied()
                ->count(),
            'badge_swipes' => AccessLog::where('company_id', $company_id)
                ->byDateRange($fromDate, $toDate)
                ->byEventType(AccessLog::EVENT_BADGE_SWIPE)
                ->count(),
            'vehicle_entries' => AccessLog::where('company_id', $company_id)
                ->byDateRange($fromDate, $toDate)
                ->byEventType(AccessLog::EVENT_VEHICLE_ENTRY)
                ->count(),
            'peak_hours' => $this->getPeakHours($company_id, $fromDate, $toDate),
            'alerts_count' => AccessLog::where('company_id', $company_id)
                ->byDateRange($fromDate, $toDate)
                ->where('status', AccessLog::STATUS_ALERT)
                ->count(),
        ];
    }

    /**
     * Get peak access hours
     */
    private function getPeakHours($company_id, $fromDate, $toDate)
    {
        return AccessLog::where('company_id', $company_id)
            ->byDateRange($fromDate, $toDate)
            ->granted()
            ->selectRaw('HOUR(timestamp) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
    }

    /**
     * Clean old access logs (retention policy)
     */
    public function cleanupOldLogs($daysToKeep = 90)
    {
        $cutoffDate = now()->subDays($daysToKeep)->startOfDay();

        $deleted = AccessLog::where('timestamp', '<', $cutoffDate)->delete();

        Log::info("Cleaned up $deleted old access logs (older than $daysToKeep days)");

        return $deleted;
    }
}
