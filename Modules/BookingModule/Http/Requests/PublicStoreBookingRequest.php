<?php

namespace Modules\BookingModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicStoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191'],
            'phone' => ['nullable', 'string', 'max:50'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'string', 'max:20'],
            'end_time' => ['required', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:4000'],
            // Honeypot fields (validated by middleware; allow)
            '_hp' => ['nullable','string','max:255'],
            '_ht' => ['nullable'],
        ];

        if (config('bookingmodule.public.captcha.enabled', false)) {
            $rules['captcha_token'] = ['required', 'string', 'max:2048'];
        }

        return $rules;
    }
}
