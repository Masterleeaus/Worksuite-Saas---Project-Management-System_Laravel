<?php

namespace Modules\TitanAgents\Http\Requests\Voice;

use Illuminate\Foundation\Http\FormRequest;

class VoiceAvatarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'avatar' => ['required', 'image', 'max:4096'],
        ];
    }
}
