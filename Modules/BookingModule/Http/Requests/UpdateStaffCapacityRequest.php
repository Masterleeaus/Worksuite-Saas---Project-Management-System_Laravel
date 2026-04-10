<?php

namespace Modules\BookingModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffCapacityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && \Modules\BookingModule\Support\AppointmentPermission::check(auth()->user(), 'schedule manage');
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'max_per_day' => 'nullable|integer|min:0|max:1000',
            'max_per_slot' => 'nullable|integer|min:0|max:1000',
            'enforce_conflicts' => 'nullable|boolean',
            'count_pending_too' => 'nullable|boolean',
        ];
    }
}
