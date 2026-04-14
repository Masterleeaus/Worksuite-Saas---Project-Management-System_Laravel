<?php

namespace App\Mcp\Tools;

use App\Mcp\Contracts\McpTool;
use Illuminate\Support\Facades\DB;

class UpdateBookingStatusTool implements McpTool
{
    private const VALID_TRANSITIONS = [
        'pending'     => ['confirmed', 'cancelled'],
        'confirmed'   => ['en_route', 'cancelled'],
        'en_route'    => ['in_progress'],
        'in_progress' => ['completed', 'reclean'],
        'reclean'     => ['in_progress', 'completed'],
    ];

    private function tenantContext(): array
    {
        $authUser = auth()->user();
        $worksuiteUser = $authUser && isset($authUser->company_id)
            ? $authUser
            : ($authUser->user ?? null);

        return [
            'company_id' => (int) ($worksuiteUser->company_id ?? 0),
            'is_superadmin' => (int) ($worksuiteUser->is_superadmin ?? 0) === 1,
        ];
    }

    public function name(): string
    {
        return 'update_booking_status';
    }

    public function description(): string
    {
        return 'Update the status of a booking. Enforces valid state transitions: pending → confirmed → en_route → in_progress → completed.';
    }

    public function inputSchema(): array
    {
        return [
            'type'       => 'object',
            'required'   => ['booking_id', 'status'],
            'properties' => [
                'booking_id' => [
                    'type'        => 'integer',
                    'description' => 'The booking (task) ID to update',
                ],
                'status' => [
                    'type'        => 'string',
                    'enum'        => ['confirmed', 'en_route', 'in_progress', 'completed', 'cancelled', 'reclean'],
                    'description' => 'New status for the booking',
                ],
                'notes' => [
                    'type'        => 'string',
                    'description' => 'Optional note to attach to the status change',
                ],
            ],
        ];
    }

    public function handle(array $arguments): array
    {
        $context = $this->tenantContext();

        if ($context['company_id'] < 1 && !$context['is_superadmin']) {
            return [[
                'type' => 'text',
                'text' => json_encode(['error' => 'Unable to resolve tenant company context for this request.']),
            ]];
        }

        $bookingQuery = DB::table('tasks')
            ->where('id', $arguments['booking_id'])
            ->where('task_type', 'booking');

        if ($context['company_id'] > 0) {
            $bookingQuery->where('company_id', $context['company_id']);
        }

        $booking = $bookingQuery->first();

        if (!$booking) {
            return [[
                'type' => 'text',
                'text' => json_encode(['error' => "Booking #{$arguments['booking_id']} not found."]),
            ]];
        }

        $currentStatus = $booking->booking_status ?? 'pending';
        $newStatus     = $arguments['status'];

        $allowed = self::VALID_TRANSITIONS[$currentStatus] ?? [];

        if (!in_array($newStatus, $allowed, true)) {
            return [[
                'type' => 'text',
                'text' => json_encode([
                    'error'           => "Invalid transition from '{$currentStatus}' to '{$newStatus}'.",
                    'allowed_next'    => $allowed,
                ]),
            ]];
        }

        $updateData = [
            'booking_status' => $newStatus,
            'updated_at'     => now(),
        ];

        if ($newStatus === 'completed') {
            $updateData['completed_on'] = now()->toDateString();
        }

        $updateQuery = DB::table('tasks')->where('id', $arguments['booking_id'])->where('task_type', 'booking');

        if ($context['company_id'] > 0) {
            $updateQuery->where('company_id', $context['company_id']);
        }

        $updateQuery->update($updateData);

        return [[
            'type' => 'text',
            'text' => json_encode([
                'success'         => true,
                'booking_id'      => $arguments['booking_id'],
                'previous_status' => $currentStatus,
                'new_status'      => $newStatus,
            ]),
        ]];
    }
}
