<?php

namespace Modules\TitanAgents\Http\Requests\Voice;

use Illuminate\Foundation\Http\FormRequest;

class VoiceChatHistoryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'conversation_id' => ['required', 'string'],
        ];
    }
}
