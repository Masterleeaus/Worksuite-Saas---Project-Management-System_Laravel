<?php

namespace App\Http\Middleware;

use App\Models\Company;
use App\Providers\TitanPanelProvider;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ApplyTitanTenantScope
 *
 * Enforces company_id boundary on all Filament Titan panel requests.
 * Applied as persistent tenant middleware in TitanPanelProvider so that
 * every resource query is scoped to the authenticated user's company.
 *
 * Guests are handled by FilamentAuthenticate before this middleware.
 * For authenticated users, tenant context is required unless the user
 * is a superadmin.
 */
class ApplyTitanTenantScope
{
    public function handle(Request $request, Closure $next): Response
    {
        // The existing CompanyScope (app/Scopes/CompanyScope.php) already
        // applies company_id filtering for every model that uses HasCompany.
        // This middleware serves as a documented checkpoint and ensures
        // Filament-specific tenant context is verified before any panel
        // resource is served.

        if (auth()->hasUser()) {
            $user = TitanPanelProvider::resolveWorksuiteUser();

            if (!$user) {
                abort(403, 'Unable to resolve account context for Titan panel access.');
            }

            if ((int) ($user->is_superadmin ?? 0) === 1) {
                return $next($request);
            }

            $companyId = (int) ($user->company_id ?? 0);

            if ($companyId < 1) {
                abort(403, 'No tenant company associated with this account.');
            }

            $company = $user->company ?? null;

            if ($company === null) {
                // Fallback for runtime contexts where company_id is present but the
                // relation is not hydrated on the resolved Worksuite user object.
                $company = Company::withoutGlobalScopes()->find($companyId);
            }

            if ($company === null) {
                // User has no associated company – deny panel access.
                abort(403, 'No tenant company associated with this account.');
            }

            // Store the resolved company in the request so Filament resources
            // and widgets can read it without another DB round-trip.
            $request->attributes->set('titan_company_id', $companyId);
        }

        return $next($request);
    }
}
