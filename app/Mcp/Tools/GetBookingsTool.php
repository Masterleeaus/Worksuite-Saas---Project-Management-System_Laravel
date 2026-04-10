<?php

namespace App\Mcp\Tools;

use App\Mcp\Contracts\McpTool;
use Illuminate\Support\Facades\DB;

class GetBookingsTool implements McpTool
{
    public function name(): string
    {
        return 'get_bookings';
    }

    public function description(): string
    {
        return 'Retrieve a list of bookings (cleaning jobs) with optional filters for date range, status, cleaner, and client.';
    }

    public function inputSchema(): array
    {
        return [
            'type'       => 'object',
            'properties' => [
                'status' => [
                    'type'        => 'string',
                    'description' => 'Filter by booking status: pending, confirmed, in_progress, completed, cancelled',
                ],
                'date_from' => [
                    'type'        => 'string',
                    'format'      => 'date',
                    'description' => 'Start date (YYYY-MM-DD)',
                ],
                'date_to' => [
                    'type'        => 'string',
                    'format'      => 'date',
                    'description' => 'End date (YYYY-MM-DD)',
                ],
                'employee_id' => [
                    'type'        => 'integer',
                    'description' => 'Filter by assigned cleaner (employee) ID',
                ],
                'client_id' => [
                    'type'        => 'integer',
                    'description' => 'Filter by client ID',
                ],
                'limit' => [
                    'type'        => 'integer',
                    'default'     => 20,
                    'description' => 'Maximum records to return (max 100)',
                ],
            ],
        ];
    }

    public function handle(array $arguments): array
    {
        $query = DB::table('tasks')
            ->where('task_type', 'booking')
            ->select([
                'id', 'heading as title', 'booking_status as status',
                'due_date', 'due_time', 'service_type', 'service_address',
                'assigned_to', 'project_id as client_id',
            ]);

        if (!empty($arguments['status'])) {
            $query->where('booking_status', $arguments['status']);
        }

        if (!empty($arguments['date_from'])) {
            $query->whereDate('due_date', '>=', $arguments['date_from']);
        }

        if (!empty($arguments['date_to'])) {
            $query->whereDate('due_date', '<=', $arguments['date_to']);
        }

        if (!empty($arguments['employee_id'])) {
            $query->where('assigned_to', $arguments['employee_id']);
        }

        $limit = min((int) ($arguments['limit'] ?? 20), 100);

        $bookings = $query->orderBy('due_date')->limit($limit)->get();

        return [
            [
                'type' => 'text',
                'text' => json_encode([
                    'count'    => $bookings->count(),
                    'bookings' => $bookings->toArray(),
                ], JSON_PRETTY_PRINT),
            ],
        ];
    }
}
