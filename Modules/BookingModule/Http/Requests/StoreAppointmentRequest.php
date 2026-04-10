<?php

namespace Modules\BookingModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && \Modules\BookingModule\Support\AppointmentPermission::check($this->user(), 'appointments create');
    }

    public function rules(): array
    {
        return [
            'appointment_name' => 'required|string|max:191',
            'appointment_type' => 'required|string|max:50',
            'week_day'         => 'required',
            'is_enabled'       => 'required',
            'question_id'      => 'nullable|array',
            'assigned_to'      => 'nullable|integer',
        ];
    }
}
