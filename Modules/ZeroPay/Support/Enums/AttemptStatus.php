<?php

namespace Modules\ZeroPay\Support\Enums;

enum AttemptStatus: string
{
    case Initiated = 'initiated';
    case AwaitingConfirmation = 'awaiting_confirmation';
    case PendingGateway = 'pending_gateway';
    case Confirmed = 'confirmed';
    case Failed = 'failed';
}
