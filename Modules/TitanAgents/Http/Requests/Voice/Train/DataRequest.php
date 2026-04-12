<?php

namespace Modules\TitanAgents\Http\Requests\Voice\Train;

use Illuminate\Foundation\Http\FormRequest;
use Modules\TitanAgents\Enums\Voice\TrainTypeEnum;

class DataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id'   => 'required|exists:voice_chatbots,id',
            'type' => ['sometimes', 'nullable', 'in:' . implode(',', TrainTypeEnum::toArray())],
        ];
    }
}
