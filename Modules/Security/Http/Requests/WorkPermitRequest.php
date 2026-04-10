<?php

namespace Modules\Security\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkPermitRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'contractor_name' => 'required|string',
            'contractor_phone' => 'nullable|string',
            'contractor_company' => 'nullable|string',
            'work_type' => 'required|string',
            'work_description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'number_of_workers' => 'nullable|integer|min:1',
            'insurance_certificate' => 'nullable|file|mimes:pdf',
            'work_permit_license' => 'nullable|file|mimes:pdf',
        ];
    }
}
