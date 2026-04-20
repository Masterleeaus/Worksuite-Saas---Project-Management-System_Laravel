<?php

namespace App\Http\Middleware;

use App\Providers\TitanPanelProvider;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTitanPanelAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                abort(401);
            }

            return redirect()->route('login');
        }

        $user = TitanPanelProvider::resolveWorksuiteUser();

        if (!$user) {
            abort(403);
        }

        if ((int) ($user->is_superadmin ?? 0) !== 1 && empty($user->company_id)) {
            abort(403);
        }

        if (!TitanPanelProvider::canAccess()) {
            abort(403);
        }

        return $next($request);
    }
}
