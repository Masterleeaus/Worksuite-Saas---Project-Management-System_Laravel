<?php

namespace App\Mcp\Tools;

use App\Mcp\Contracts\McpTool;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CreateBookingTool implements McpTool
{
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
        return 'create_booking';
    }

    public function description(): string
    {
        return 'Create a new cleaning booking (task) and optionally assign a cleaner. Returns the new booking ID.';
    }

    public function inputSchema(): array
    {
        return [
            'type'       => 'object',
            'required'   => ['client_id', 'service_type', 'due_date'],
            'properties' => [
                'client_id' => [
                    'type'        => 'integer',
                    'description' => 'The client (user) ID for this booking',
                ],
                'service_type' => [
                    'type'        => 'string',
                    'description' => 'Type of clean: regular, deep_clean, end_of_lease, carpet, window, oven',
                ],
                'due_date' => [
                    'type'        => 'string',
                    'format'      => 'date',
                    'description' => 'Scheduled date (YYYY-MM-DD)',
                ],
                'due_time' => [
                    'type'        => 'string',
                    'description' => 'Start time (HH:MM, 24-hour)',
                ],
                'service_address' => [
                    'type'        => 'string',
                    'description' => 'Full service address',
                ],
                'employee_id' => [
                    'type'        => 'integer',
                    'description' => 'Cleaner (employee) ID to assign — optional',
                ],
                'notes' => [
                    'type'        => 'string',
                    'description' => 'Additional booking notes or special instructions',
                ],
                'estimated_duration_hours' => [
                    'type'        => 'number',
                    'description' => 'Estimated job duration in hours',
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

        $validator = Validator::make($arguments, [
            'client_id'    => 'required|integer|exists:users,id',
            'service_type' => 'required|string|max:100',
            'due_date'     => 'required|date',
        ]);

        if ($validator->fails()) {
            return [
                [
                    'type' => 'text',
                    'text' => json_encode(['error' => $validator->errors()->toArray()]),
                ],
            ];
        }

        $clientQuery = DB::table('users')->where('id', $arguments['client_id']);

        if ($context['company_id'] > 0) {
            $clientQuery->where('company_id', $context['company_id']);
        }

        if (!$clientQuery->exists()) {
            return [[
                'type' => 'text',
                'text' => json_encode(['error' => 'Client is not accessible within the current tenant context.']),
            ]];
        }

        $bookingId = DB::table('tasks')->insertGetId([
            'task_type'                => 'booking',
            'booking_status'           => 'pending',
            'heading'                  => ucwords(str_replace('_', ' ', $arguments['service_type'])) . ' Booking',
            'project_id'               => $arguments['client_id'],
            'assigned_to'              => $arguments['employee_id'] ?? null,
            'service_type'             => $arguments['service_type'],
            'service_address'          => $arguments['service_address'] ?? null,
            'due_date'                 => $arguments['due_date'],
            'due_time'                 => $arguments['due_time'] ?? null,
            'description'              => $arguments['notes'] ?? null,
            'estimated_duration_hours' => $arguments['estimated_duration_hours'] ?? null,
            'company_id'               => $context['company_id'] > 0 ? $context['company_id'] : null,
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);

        return [
            [
                'type' => 'text',
                'text' => json_encode([
                    'success'    => true,
                    'booking_id' => $bookingId,
                    'message'    => "Booking #{$bookingId} created successfully with status 'pending'.",
                ]),
            ],
        ];
    }
}
