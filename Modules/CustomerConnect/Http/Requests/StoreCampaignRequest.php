<?php

namespace Modules\CustomerConnect\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:191'],
            'description' => ['nullable','string'],
            'status' => ['nullable','in:draft,active,paused,archived'],
            'stop_on_reply' => ['sometimes','boolean'],
        ];
    }
}
