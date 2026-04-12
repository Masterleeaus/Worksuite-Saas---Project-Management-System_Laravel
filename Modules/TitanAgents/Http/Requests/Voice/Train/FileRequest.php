<?php

namespace Modules\TitanAgents\Http\Requests\Voice\Train;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id'   => 'required|exists:voice_chatbots,id',
            'file' => 'required|file',
        ];
    }
}
