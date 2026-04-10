<?php

namespace Modules\BookingModule\Http\Requests\Dispatch;

use Illuminate\Foundation\Http\FormRequest;

class DispatchMoveRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Permission checked in controller as well.
        return true;
    }

    public function rules(): array
    {
        return [
            'schedule_id' => 'required|integer|min:1',
            'to_user_id' => 'nullable|integer|min:1',
            'date' => 'required|date',
            'start_time' => 'required|string|max:20',
            'end_time' => 'required|string|max:20',
            'note' => 'nullable|string|max:255',
        ];
    }
}
