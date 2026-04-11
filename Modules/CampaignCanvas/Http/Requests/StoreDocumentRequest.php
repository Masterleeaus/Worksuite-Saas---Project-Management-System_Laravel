<?php
namespace Modules\CampaignCanvas\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'    => ['sometimes', 'string', 'max:255'],
            'payload' => ['nullable', 'array'],
            // preview is a base64-encoded data URI thumbnail; limit to 512KB
            'preview' => ['nullable', 'string', 'max:524288'],
        ];
    }
}
