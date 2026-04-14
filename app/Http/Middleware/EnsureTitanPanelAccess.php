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
        abort_unless(TitanPanelProvider::canAccess(), 403);

        return $next($request);
    }
}
