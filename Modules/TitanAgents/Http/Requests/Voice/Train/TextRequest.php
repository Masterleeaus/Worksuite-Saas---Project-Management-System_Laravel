<?php

namespace Modules\TitanAgents\Http\Requests\Voice\Train;

use Illuminate\Foundation\Http\FormRequest;

class TextRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id'      => 'required|exists:voice_chatbots,id',
            'title'   => ['required', 'string'],
            'content' => ['required', 'string'],
        ];
    }
}
