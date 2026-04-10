<?php

namespace Modules\TitanZero\Services\Coach;

use Modules\TitanZero\Entities\TitanZeroCoach;

class CoachRegistry
{
    public function all(): array
    {
        return TitanZeroCoach::query()
            ->where('is_enabled', true)
            ->orderBy('id')
            ->get()
            ->keyBy('key')
            ->toArray();
    }

    public function get(string $key): ?array
    {
        $row = TitanZeroCoach::query()->where('key', $key)->first();
        return $row ? $row->toArray() : null;
    }
}
