<?php

namespace Modules\CustomerConnect\Enums;

enum CampaignStatus: string
{
    case Draft    = 'draft';
    case Active   = 'active';
    case Paused   = 'paused';
    case Archived = 'archived';

    /** @return string[] */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
