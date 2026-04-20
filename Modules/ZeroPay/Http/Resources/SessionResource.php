<?php

namespace Modules\ZeroPay\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'invoice_id' => $this->invoice_id,
            'public_token' => $this->public_token,
            'status' => $this->status,
            'selected_method' => $this->selected_method,
            'amount' => (float) $this->amount,
            'payment_link' => $this->payment_link,
            'expires_at' => optional($this->expires_at)?->toIso8601String(),
            'paid_at' => optional($this->paid_at)?->toIso8601String(),
            'attempts' => $this->whenLoaded('attempts'),
            'download_tokens' => $this->whenLoaded('downloadTokens'),
            'created_at' => optional($this->created_at)?->toIso8601String(),
        ];
    }
}
