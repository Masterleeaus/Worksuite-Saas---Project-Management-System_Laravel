<?php

namespace Modules\BookingModule\Services;

use Illuminate\Support\Facades\Auth;
use Modules\BookingModule\Entities\Appointment;
use Modules\BookingModule\Entities\AppointmentAssignment;
use Modules\BookingModule\Notifications\AppointmentAssignedNotification;
use Modules\BookingModule\Notifications\AppointmentReassignedNotification;

class AppointmentAssignmentService
{
    public function assign(Appointment $appointment, ?int $toUserId, ?string $note = null): Appointment
    {
        $fromUserId = $appointment->assigned_to;

        $appointment->assigned_to = $toUserId;
        $appointment->assigned_by = Auth::id();
        $appointment->assigned_at = now();
        $appointment->assignment_status = $toUserId ? 'assigned' : 'unassigned';
        $appointment->save();

        AppointmentAssignment::create([
            'appointment_id' => $appointment->id,
            'from_user_id'   => $fromUserId,
            'to_user_id'     => $toUserId,
            'action'         => $fromUserId && $toUserId && $fromUserId !== $toUserId ? 'reassign' : ($toUserId ? 'assign' : 'unassign'),
            'note'           => $note,
            'created_by'     => function_exists('creatorId') ? creatorId() : Auth::id(),
            'workspace'      => function_exists('getActiveWorkSpace') ? getActiveWorkSpace() : null,
        ]);

        try {
            if ($toUserId && $appointment->assignee) {
                if ($fromUserId && $fromUserId !== $toUserId) {
                    $appointment->assignee->notify(new AppointmentReassignedNotification($appointment));
                } else {
                    $appointment->assignee->notify(new AppointmentAssignedNotification($appointment));
                }
            }
        } catch (\Throwable $e) {
            // Do not block assignment on notification failures.
        }

        return $appointment;
    }
}
