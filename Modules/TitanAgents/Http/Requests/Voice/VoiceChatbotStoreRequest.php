<?php

namespace Modules\TitanAgents\Http\Requests\Voice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VoiceChatbotStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'        => ['required', 'integer', 'exists:users,id'],
            'uuid'           => ['required', 'string'],
            'title'          => ['required', 'string'],
            'bubble_message' => ['required', 'string'],
            'welcome_message' => ['required', 'string'],
            'instructions'   => ['required', 'string'],
            'language'       => ['sometimes', 'nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'uuid'    => Str::uuid()->toString(),
            'user_id' => Auth::id(),
        ]);
    }
}
