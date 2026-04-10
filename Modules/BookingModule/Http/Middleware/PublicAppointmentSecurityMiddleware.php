<?php

namespace Modules\BookingModule\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PublicAppointmentSecurityMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Lightweight security headers for public pages.
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'same-origin');

        return $response;
    }
}
