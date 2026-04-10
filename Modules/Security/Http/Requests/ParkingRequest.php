<?php

namespace Modules\Security\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParkingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'vehicle_plate' => 'required|string|unique:tenan_parkir,vehicle_plate',
            'vehicle_type' => 'required|in:motorcycle,car,suv,truck',
            'vehicle_owner' => 'required|string',
            'parking_slot' => 'required|string',
            'registration_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:registration_date',
            'notes' => 'nullable|string',
        ];
    }
}
