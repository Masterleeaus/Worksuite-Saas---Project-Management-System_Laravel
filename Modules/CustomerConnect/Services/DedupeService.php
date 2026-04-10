<?php

namespace Modules\CustomerConnect\Services;

use Illuminate\Support\Collection;
use Modules\CustomerConnect\Entities\AudienceMember;

/**
 * Basic dedupe by stable identifiers (email, phone).
 * NOTE: Pass-1/2 keep this simple; Pass-4 can expand to reservoir integration.
 */
class DedupeService
{
    /**
     * @param Collection<int, AudienceMember> $members
     * @return Collection<int, AudienceMember>
     */
    public function dedupe(Collection $members): Collection
    {
        return $members->unique(function (AudienceMember $m) {
            $email = strtolower(trim((string)($m->email ?? '')));
            $phone = preg_replace('/\D+/', '', (string)($m->phone ?? ''));
            return $email !== '' ? 'e:' . $email : 'p:' . $phone;
        })->values();
    }
}
