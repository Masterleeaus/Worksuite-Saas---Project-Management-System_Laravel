<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate as FilamentAuthenticateMiddleware;
class FilamentAuthenticate extends FilamentAuthenticateMiddleware
{
    protected function redirectTo($request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        return route('login');
    }
}
