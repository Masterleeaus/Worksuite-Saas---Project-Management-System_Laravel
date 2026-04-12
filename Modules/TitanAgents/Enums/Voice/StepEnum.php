<?php

namespace Modules\TitanAgents\Enums\Voice;

enum StepEnum: string
{
    case configure = 'configure';
    case customize = 'customize';
    case train = 'train';
    case embed = 'embed';

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
