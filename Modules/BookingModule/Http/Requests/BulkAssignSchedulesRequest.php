<?php

namespace Modules\BookingModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkAssignSchedulesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (\Modules\BookingModule\Support\AppointmentPermission::check(auth()->user(), 'schedule manage') || \Modules\BookingModule\Support\AppointmentPermission::check(auth()->user(), 'schedule assign'));
    }

    public function rules(): array
    {
        return [
            'schedule_ids' => 'required|array|min:1',
            'schedule_ids.*' => 'integer',
            'assigned_to' => 'nullable|integer',
            'note' => 'nullable|string|max:2000',
        ];
    }
}
