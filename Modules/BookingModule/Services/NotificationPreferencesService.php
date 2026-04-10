<?php

namespace Modules\BookingModule\Services;

use Modules\BookingModule\Entities\AppointmentNotificationPreference;

class NotificationPreferencesService
{
    public function getForUser(int $userId, ?int $companyId = null): AppointmentNotificationPreference
    {
        $pref = AppointmentNotificationPreference::query()
            ->where('user_id', $userId)
            ->where(function ($q) use ($companyId) {
                if ($companyId === null) {
                    $q->whereNull('company_id');
                } else {
                    $q->where('company_id', $companyId);
                }
            })
            ->first();

        return $pref ?: new AppointmentNotificationPreference([
            'company_id' => $companyId,
            'user_id' => $userId,
            'channel_email' => true,
            'channel_database' => true,
            'notify_assigned' => true,
            'notify_reassigned' => true,
            'notify_unassigned' => true,
            'notify_rescheduled' => true,
            'notify_cancelled' => true,
            'daily_digest' => false,
        ]);
    }

    public function saveForUser(int $userId, ?int $companyId, array $data): AppointmentNotificationPreference
    {
        return AppointmentNotificationPreference::query()->updateOrCreate(
            ['user_id' => $userId, 'company_id' => $companyId],
            $data
        );
    }
}
