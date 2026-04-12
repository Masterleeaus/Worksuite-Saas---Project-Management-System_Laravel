<?php

namespace Modules\TitanAgents\Enums\Voice;

enum TrainTypeEnum: string
{
    case url = 'url';
    case file = 'file';
    case text = 'text';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function isValid(string $step): bool
    {
        return in_array($step, self::toArray(), true);
    }

    public static function isInValid(string $step): bool
    {
        return ! self::isValid($step);
    }
}
