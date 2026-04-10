<?php

namespace Modules\BookingModule\Http\Controllers\Cleaning;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\BookingModule\Models\CleaningBooking;
use Modules\BookingModule\Services\BookingAutoInvoiceService;
use Modules\BookingModule\Services\BookingFSMService;

/**
 * CleaningBookingController
 *
 * Handles FSM status transitions and basic CRUD for cleaning-type bookings
 * (i.e., Tasks with task_type = 'booking').
 */
class CleaningBookingController extends AccountBaseController
{
    public function __construct(
        private readonly BookingFSMService         $fsmService,
        private readonly BookingAutoInvoiceService $invoiceService,
    ) {
        parent::__construct();
    }

    /**
     * Store a new cleaning booking.
     *
     * POST /cleaning-bookings
     */
    public function store(Request $request): JsonResponse
    {
        $this->addPermission = user()->permission('create_bookings');

        abort_if(! in_array($this->addPermission, ['all']), 403);

        $data = $request->validate([
            'heading'                  => ['required', 'string', 'max:255'],
            'project_id'               => ['nullable', 'integer', 'exists:projects,id'],
            'service_type'             => ['required', 'string', 'in:' . implode(',', CleaningBooking::SERVICE_TYPES)],
            'service_address'          => ['required', 'string', 'max:500'],
            'service_lat'              => ['nullable', 'numeric', 'between:-90,90'],
            'service_lng'              => ['nullable', 'numeric', 'between:-180,180'],
            'property_type'            => ['nullable', 'string', 'in:' . implode(',', CleaningBooking::PROPERTY_TYPES)],
            'bedrooms'                 => ['nullable', 'integer', 'min:0'],
            'bathrooms'                => ['nullable', 'integer', 'min:0'],
            'frequency'                => ['nullable', 'string', 'in:' . implode(',', CleaningBooking::FREQUENCIES)],
            'access_method'            => ['nullable', 'string', 'in:' . implode(',', CleaningBooking::ACCESS_METHODS)],
            'alarm_code'               => ['nullable', 'string', 'max:50'],
            'key_number'               => ['nullable', 'string', 'max:50'],
            'estimated_duration_hours' => ['nullable', 'numeric', 'min:0', 'max:24'],
            'supplies_required'        => ['nullable', 'boolean'],
            'num_cleaners_required'    => ['nullable', 'integer', 'min:1'],
            'due_date'                 => ['nullable', 'date'],
            'start_date'               => ['nullable', 'date'],
        ]);

        // Server-side GPS validation.
        $this->fsmService->validateCoordinates(
            isset($data['service_lat']) ? (float) $data['service_lat'] : null,
            isset($data['service_lng']) ? (float) $data['service_lng'] : null,
        );

        $booking = CleaningBooking::create(array_merge($data, [
            'task_type'      => 'booking',
            'booking_status' => 'pending',
            'added_by'       => user()->id,
            'created_by'     => user()->id,
            'company_id'     => user()->company_id,
        ]));

        return response()->json([
            'status'  => 'success',
            'message' => __('messages.recordSaved'),
            'data'    => $booking,
        ], 201);
    }

    /**
     * Transition a booking's FSM status.
     *
     * PATCH /cleaning-bookings/{booking}/status
     */
    public function updateStatus(Request $request, CleaningBooking $booking): JsonResponse
    {
        $editPermission = user()->permission('complete_booking');
        abort_if(! in_array($editPermission, ['all']), 403);

        $data = $request->validate([
            'status' => ['required', 'string'],
        ]);

        try {
            $booking = $this->fsmService->transition($booking, $data['status']);
        } catch (ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'errors'  => $e->errors(),
            ], 422);
        }

        // Auto-invoice on completion.
        if ($booking->booking_status === 'completed') {
            $this->invoiceService->generateForBooking($booking);
        }

        return response()->json([
            'status'  => 'success',
            'message' => __('messages.updateSuccess'),
            'data'    => $booking->fresh(),
        ]);
    }

    /**
     * Assign a cleaner to a booking.
     *
     * PATCH /cleaning-bookings/{booking}/assign
     */
    public function assignCleaner(Request $request, CleaningBooking $booking): JsonResponse
    {
        abort_if(user()->permission('assign_cleaners') !== 'all', 403);

        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        // Use the existing task_users pivot table.
        $booking->taskUsers()->firstOrCreate(['user_id' => $data['user_id']]);

        return response()->json([
            'status'  => 'success',
            'message' => __('messages.updateSuccess'),
        ]);
    }
}
