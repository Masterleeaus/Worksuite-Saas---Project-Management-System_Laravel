<?php

namespace Modules\TitanTalk\Observers;

use Modules\TitanTalk\Services\TitanTalkService;

/**
 * TitanTalkAutoRoomObserver
 *
 * Listens on core Worksuite model events to auto-create TitanTalk rooms.
 * Only fires when the relevant model exists in this repo and when the
 * auto_create_rooms config permits it.
 *
 * Uses the safe pattern from ChattingModule (booking-linked room concept).
 * All operations are wrapped in try/catch so a TitanTalk failure never
 * breaks the original save operation.
 *
 * Registered in TitanTalkServiceProvider::boot().
 */
class TitanTalkAutoRoomObserver
{
    public function __construct(private readonly TitanTalkService $service) {}

    // -------------------------------------------------------------------------
    // Project → project room
    // -------------------------------------------------------------------------

    public function onProjectCreated(\App\Models\Project $project): void
    {
        try {
            $companyId = $project->company_id ?? (company()?->id);
            if (!$companyId) {
                return;
            }

            $this->service->autoCreateRoom(
                type:          'project',
                name:          'Project: ' . $project->project_name,
                companyId:     $companyId,
                createdBy:     $project->added_by ?? $project->client_id ?? 0,
                referenceId:   $project->id,
                referenceType: \App\Models\Project::class,
            );
        } catch (\Throwable) {}
    }

    // -------------------------------------------------------------------------
    // Task (booking) → booking room
    // -------------------------------------------------------------------------

    public function onTaskCreated(\App\Models\Task $task): void
    {
        try {
            // Only create rooms for booking-type tasks
            if (($task->task_type ?? '') !== 'booking') {
                return;
            }

            $companyId = $task->company_id ?? (company()?->id);
            if (!$companyId) {
                return;
            }

            $this->service->autoCreateRoom(
                type:          'booking',
                name:          'Booking: ' . ($task->heading ?? "Task #{$task->id}"),
                companyId:     $companyId,
                createdBy:     $task->added_by ?? $task->created_by ?? 0,
                referenceId:   $task->id,
                referenceType: \App\Models\Task::class,
            );
        } catch (\Throwable) {}
    }

    // -------------------------------------------------------------------------
    // Ticket (issue/support) → issue room
    // -------------------------------------------------------------------------

    public function onTicketCreated(\App\Models\Ticket $ticket): void
    {
        try {
            $companyId = $ticket->company_id ?? (company()?->id);
            if (!$companyId) {
                return;
            }

            $this->service->autoCreateRoom(
                type:          'issue',
                name:          'Issue: ' . $ticket->subject,
                companyId:     $companyId,
                createdBy:     $ticket->added_by ?? $ticket->user_id ?? 0,
                referenceId:   $ticket->id,
                referenceType: \App\Models\Ticket::class,
            );
        } catch (\Throwable) {}
    }

    // -------------------------------------------------------------------------
    // FSMOrder (service job) → service_job room (if FSMCore installed)
    // -------------------------------------------------------------------------

    public function onFsmOrderCreated(object $order): void
    {
        try {
            $companyId = $order->company_id ?? (company()?->id);
            if (!$companyId) {
                return;
            }

            $this->service->autoCreateRoom(
                type:          'service_job',
                name:          'Job: ' . ($order->name ?? "Order #{$order->id}"),
                companyId:     $companyId,
                createdBy:     $order->person_id ?? 0,
                referenceId:   $order->id,
                referenceType: 'Modules\\FSMCore\\Models\\FSMOrder',
            );
        } catch (\Throwable) {}
    }

    // -------------------------------------------------------------------------
    // CleaningBooking → booking room (if BookingModule installed)
    // -------------------------------------------------------------------------

    public function onCleaningBookingCreated(object $booking): void
    {
        try {
            $companyId = $booking->company_id ?? (company()?->id);
            if (!$companyId) {
                return;
            }

            $this->service->autoCreateRoom(
                type:          'booking',
                name:          'Booking: ' . ($booking->heading ?? "Booking #{$booking->id}"),
                companyId:     $companyId,
                createdBy:     $booking->added_by ?? $booking->created_by ?? 0,
                referenceId:   $booking->id,
                referenceType: 'Modules\\BookingModule\\Models\\CleaningBooking',
            );
        } catch (\Throwable) {}
    }
}
