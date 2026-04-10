<?php

namespace Modules\CustomerConnect\Enums;

enum DeliveryStatus: string
{
    case Queued  = 'queued';
    case Sending = 'sending';
    case Sent    = 'sent';
    case Failed  = 'failed';
    case Skipped = 'skipped';

    /** @return string[] */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
