<?php

namespace Modules\BookingModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationPreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'channel_email' => ['nullable','boolean'],
            'channel_database' => ['nullable','boolean'],

            'notify_assigned' => ['nullable','boolean'],
            'notify_reassigned' => ['nullable','boolean'],
            'notify_unassigned' => ['nullable','boolean'],
            'notify_rescheduled' => ['nullable','boolean'],
            'notify_cancelled' => ['nullable','boolean'],

            'daily_digest' => ['nullable','boolean'],
            'quiet_hours_start' => ['nullable','date_format:H:i'],
            'quiet_hours_end' => ['nullable','date_format:H:i'],
        ];
    }
}
