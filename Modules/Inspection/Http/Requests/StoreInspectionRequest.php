<?php

namespace Modules\Inspection\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Inspection\Support\Enums\InspectionStatus;

class StoreInspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_id'   => ['nullable', 'integer'],
            'inspector_id' => ['required', 'integer', 'exists:users,id'],
            'score'        => ['nullable', 'numeric', 'min:0', 'max:10'],
            'status'       => ['required', 'in:' . implode(',', InspectionStatus::all())],
            'notes'        => ['nullable', 'string', 'max:5000'],
            'inspected_at' => ['nullable', 'date'],
            'template_id'  => ['nullable', 'integer'],
            'items'        => ['nullable', 'array'],
            'items.*.area'    => ['required_with:items', 'string', 'max:191'],
            'items.*.passed'  => ['required_with:items', 'boolean'],
            'items.*.notes'   => ['nullable', 'string', 'max:2000'],
        ];
    }
}
