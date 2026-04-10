<?php

namespace Modules\BookingModule\Http\Requests\Dispatch;

use Illuminate\Foundation\Http\FormRequest;

class DispatchUpdateScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // controller enforces permission/policy
    }

    public function rules(): array
    {
        return [
            'date' => ['required','date'],
            'start_time' => ['required','date_format:H:i'],
            'end_time' => ['required','date_format:H:i','after:start_time'],
            'notes' => ['nullable','string','max:2000'],
            'user_id' => ['nullable','integer','min:1'],
        ];
    }
}
