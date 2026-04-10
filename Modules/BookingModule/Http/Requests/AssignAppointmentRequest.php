<?php

namespace Modules\BookingModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && \Modules\BookingModule\Support\AppointmentPermission::check($this->user(), 'appointments assign');
    }

    public function rules(): array
    {
        return [
            'assigned_to' => 'nullable|integer',
            'note'        => 'nullable|string|max:1000',
        ];
    }
}
