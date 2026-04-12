<?php

namespace Modules\TitanAgents\Http\Resources\Voice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatbotTrainResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'type'   => $this->type,
            'file'   => $this->file,
            'url'    => $this->url,
            'text'   => $this->text,
            'name'   => $this->name,
            'status' => $this->trained_at ? 'Trained' : 'Not Trained',
        ];
    }
}
