<?php

namespace Modules\BookingModule\Services\Notifications;

use Modules\BookingModule\Entities\AppointmentNotificationLog;

class InAppNotificationService
{
    public function log(array $payload): AppointmentNotificationLog
    {
        $payload['sent_at'] = $payload['sent_at'] ?? now();

        return AppointmentNotificationLog::create($payload);
    }

    public function markRead(int $id, int $userId): bool
    {
        $row = AppointmentNotificationLog::query()
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$row) {
            return false;
        }

        if (!$row->read_at) {
            $row->read_at = now();
        }

        return (bool)$row->save();
    }
}
