<?php

namespace Modules\BookingModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePublicSpamSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && \Modules\BookingModule\Support\AppointmentPermission::check(auth()->user(), 'appointment settings manage');
    }

    public function rules(): array
    {
        return [
            'enable_honeypot' => ['nullable', 'boolean'],
            'honeypot_min_seconds' => ['required', 'integer', 'min:0', 'max:60'],
            'rate_limit_per_minute' => ['required', 'integer', 'min:1', 'max:600'],
        ];
    }
}
