<?php

namespace Modules\BookingModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAutoAssignSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'enabled' => 'nullable|boolean',
            'strategy' => 'required|string|in:round_robin,least_busy,schedule_match',
            'require_permission' => 'nullable|boolean',
            'eligible_permission' => 'nullable|string|max:190',
        ];
    }
}
