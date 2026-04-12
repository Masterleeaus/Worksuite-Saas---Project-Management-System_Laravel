<?php

namespace Modules\TitanAgents\Http\Requests\Voice;

use Illuminate\Foundation\Http\FormRequest;

class VoiceChatbotUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id'             => ['required', 'integer', 'exists:voice_chatbots,id'],
            'user_id'        => ['required', 'integer', 'exists:users,id'],
            'uuid'           => ['required', 'string'],
            'title'          => ['required', 'string'],
            'bubble_message' => ['required', 'string'],
            'welcome_message' => ['required', 'string'],
            'instructions'   => ['required', 'string'],
            'language'       => ['sometimes', 'nullable', 'string'],
            'active'         => ['sometimes', 'boolean'],
            'voice_id'       => ['required', 'string'],
            'avatar'         => ['sometimes', 'nullable', 'string'],
            'position'       => ['required', 'string'],
        ];
    }
}
