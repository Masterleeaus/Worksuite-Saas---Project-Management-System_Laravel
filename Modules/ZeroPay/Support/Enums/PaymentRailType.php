<?php

namespace Modules\ZeroPay\Support\Enums;

enum PaymentRailType: string
{
    case ZeroFee = 'zero_fee';
    case ProcessingFee = 'processing_fee';
}
