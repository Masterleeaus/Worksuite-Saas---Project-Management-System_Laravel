<?php

namespace Modules\ZeroPay\Traits;

trait ResolvesCompanyId
{
    protected function resolveCompanyId(?int $fallback = null): ?int
    {
        return auth()->user()?->company_id ?? $fallback;
    }
}
