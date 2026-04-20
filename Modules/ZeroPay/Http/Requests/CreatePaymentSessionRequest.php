<?php

namespace Modules\ZeroPay\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_id' => ['required', 'integer', 'exists:invoices,id'],
            'amount' => ['nullable', 'numeric', 'min:0.01'],
            'source_type' => ['nullable', 'string', 'max:50'],
            'source_reference' => ['nullable', 'string', 'max:191'],
            'booking_id' => ['nullable', 'integer'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
