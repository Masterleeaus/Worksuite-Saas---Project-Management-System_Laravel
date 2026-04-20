<?php

namespace Modules\ZeroPay\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelectPaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'method' => ['required', 'string', 'max:60'],
        ];
    }
}
