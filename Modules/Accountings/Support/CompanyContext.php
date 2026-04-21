<?php

namespace Modules\Accountings\Support;

class CompanyContext
{
    public static function resolveCompanyId(?int $companyId = null): int
    {
        $resolved = auth()->user()?->company_id;

        if ($resolved && $companyId && (int) $companyId !== (int) $resolved) {
            abort(403, 'Cross-tenant access denied.');
        }

        if ($resolved) {
            return (int) $resolved;
        }

        if (function_exists('company') && company()?->id) {
            return (int) company()->id;
        }

        abort(403, 'Company context missing.');
    }
}
