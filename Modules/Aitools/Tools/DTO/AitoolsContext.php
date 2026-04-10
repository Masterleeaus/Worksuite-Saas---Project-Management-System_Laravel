<?php

namespace Modules\Aitools\Tools\DTO;

class AitoolsContext
{
    public function __construct(
        public readonly int $companyId,
        public readonly int $userId,
        public readonly string $timezone = 'UTC',
        public readonly array $meta = []
    ) {}
}
