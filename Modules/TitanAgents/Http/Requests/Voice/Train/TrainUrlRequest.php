<?php

namespace Modules\TitanAgents\Http\Requests\Voice\Train;

use Illuminate\Foundation\Http\FormRequest;

class TrainUrlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id'     => 'required|exists:voice_chatbots,id',
            'url'    => ['required', 'url'],
            'single' => ['required', 'in:1,0'],
        ];
    }
}
