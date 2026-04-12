<?php

namespace Modules\TitanAgents\Enums\Voice;

enum PositionEnum: string
{
    case left = 'left';
    case right = 'right';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
