<?php

namespace Modules\TitanAgents\Http\Requests\Voice\Train;

use Illuminate\Foundation\Http\FormRequest;

class TrainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id'     => 'required|exists:voice_chatbots,id',
            'data'   => 'required|array',
            'data.*' => 'required|exists:voice_chatbot_trains,id',
        ];
    }
}
