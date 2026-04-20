<?php

namespace Modules\ZeroPay\Data;

use Modules\ZeroPay\Models\ZeroPaySession;

class PaymentSessionData
{
    public function __construct(public readonly ZeroPaySession $session)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->session->id,
            'company_id' => $this->session->company_id,
            'invoice_id' => $this->session->invoice_id,
            'status' => $this->session->status,
            'amount' => (float) $this->session->amount,
            'payment_link' => $this->session->payment_link,
            'public_token' => $this->session->public_token,
        ];
    }
}
