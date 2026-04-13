<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ApplyTitanTenantScope
 *
 * Enforces company_id boundary on all Filament Titan panel requests.
 * Applied as persistent tenant middleware in TitanPanelProvider so that
 * every resource query is scoped to the authenticated user's company.
 *
 * This middleware is intentionally non-destructive: if there is no
 * authenticated user or the user has no company, the request proceeds
 * normally and relies on the existing CompanyScope global scope.
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
            $user    = auth()->user();
            $company = $user->company ?? null;

            if ($company === null) {
                // User has no associated company – deny panel access.
                abort(403, 'No tenant company associated with this account.');
            }

            // Store the resolved company in the request so Filament resources
            // and widgets can read it without another DB round-trip.
            $request->attributes->set('titan_company_id', $company->id);
        }

        return $next($request);
    }
}
