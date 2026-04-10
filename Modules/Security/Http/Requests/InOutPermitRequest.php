<?php

namespace Modules\Security\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InOutPermitRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'visitor_name' => 'required|string',
            'visitor_phone' => 'nullable|string',
            'visitor_id_number' => 'nullable|string',
            'purpose' => 'required|string',
            'check_in_date' => 'required|date',
            'check_in_time' => 'required|date_format:H:i',
            'check_out_date' => 'required|date|after_or_equal:check_in_date',
            'check_out_time' => 'required|date_format:H:i',
            'vehicle_plate' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }
}
