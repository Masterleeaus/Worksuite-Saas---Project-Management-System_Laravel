<?php

namespace Modules\ZeroPay\Support\Enums;

enum SessionStatus: string
{
    case Draft = 'draft';
    case Sent = 'sent';
    case AwaitingConfirmation = 'awaiting_confirmation';
    case PendingGateway = 'pending_gateway';
    case Paid = 'paid';
    case Failed = 'failed';
    case Expired = 'expired';
}
