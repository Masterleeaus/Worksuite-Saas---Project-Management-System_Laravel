<?php

namespace Modules\CustomerConnect\Enums;

enum RunStatus: string
{
    case Queued    = 'queued';
    case Running   = 'running';
    case Completed = 'completed';
    case Failed    = 'failed';
    case Cancelled = 'cancelled';

    /** @return string[] */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
