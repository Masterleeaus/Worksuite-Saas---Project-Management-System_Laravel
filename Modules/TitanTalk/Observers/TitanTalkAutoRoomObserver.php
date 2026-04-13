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
 * Active source models (verified in this repo):
 *  - App\Models\Project        (app/Models/Project.php)    fields: project_name, added_by, company_id
 *  - App\Models\Task           (app/Models/Task.php)       fields: heading, added_by, company_id, task_type
 *  - App\Models\Ticket         (app/Models/Ticket.php)     fields: subject, added_by, user_id, company_id
 *  - Modules\FSMCore\Models\FSMOrder    (if FSMCore installed)  fields: name, person_id, company_id
 *  - Modules\BookingModule\Models\CleaningBooking (if BookingModule installed) fields: heading, created_by, added_by, company_id
 *
 * All operations are wrapped in try/catch so a TitanTalk failure never
 * breaks the original model save.
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
                name:          'Project: ' . ($project->project_name ?? "Project #{$project->id}"),
                companyId:     (int) $companyId,
                createdBy:     (int) ($project->added_by ?? 0),
                referenceId:   $project->id,
                referenceType: \App\Models\Project::class,
            );
        } catch (\Throwable) {}
    }

    // -------------------------------------------------------------------------
    // Task → booking room (only for task_type = 'booking')
    // task_type field confirmed present via app/Mcp/Tools/CreateBookingTool.php
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
                companyId:     (int) $companyId,
                createdBy:     (int) ($task->added_by ?? 0),
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
                name:          'Issue: ' . ($ticket->subject ?? "Ticket #{$ticket->id}"),
                companyId:     (int) $companyId,
                createdBy:     (int) ($ticket->added_by ?? $ticket->user_id ?? 0),
                referenceId:   $ticket->id,
                referenceType: \App\Models\Ticket::class,
            );
        } catch (\Throwable) {}
    }

    // -------------------------------------------------------------------------
    // FSMOrder (service job) → service_job room (if FSMCore installed)
    // FSMOrder fields verified: name, person_id, company_id
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
                companyId:     (int) $companyId,
                createdBy:     (int) ($order->person_id ?? 0),
                referenceId:   $order->id,
                referenceType: 'Modules\\FSMCore\\Models\\FSMOrder',
            );
        } catch (\Throwable) {}
    }

    // -------------------------------------------------------------------------
    // CleaningBooking → booking room (if BookingModule installed)
    // CleaningBooking fields verified: heading, created_by, added_by, company_id
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
                companyId:     (int) $companyId,
                createdBy:     (int) ($booking->created_by ?? $booking->added_by ?? 0),
                referenceId:   $booking->id,
                referenceType: 'Modules\\BookingModule\\Models\\CleaningBooking',
            );
        } catch (\Throwable) {}
    }
}
