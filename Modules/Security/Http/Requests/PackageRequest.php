<?php

namespace Modules\Security\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'type_id' => 'required|exists:tr_package_type,id',
            'courier_id' => 'required|exists:tr_package_courier,id',
            'tracking_number' => 'required|string',
            'recipient_name' => 'required|string',
            'sender_name' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'received_date' => 'required|date',
            'received_time' => 'required|date_format:H:i',
            'received_by' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }
}
