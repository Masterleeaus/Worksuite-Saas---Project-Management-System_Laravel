<?php

namespace Modules\Security\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccessCardRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'card_number' => 'required|string|unique:tr_access_card,card_number',
            'card_type' => 'required|string',
            'status' => 'required|in:active,inactive,expired',
            'issued_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issued_date',
            'description' => 'nullable|string',
        ];
    }
}
