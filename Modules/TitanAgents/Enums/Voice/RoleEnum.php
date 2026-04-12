<?php

namespace Modules\TitanAgents\Enums\Voice;

enum RoleEnum: string
{
    case user = 'user';
    case agent = 'agent';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
