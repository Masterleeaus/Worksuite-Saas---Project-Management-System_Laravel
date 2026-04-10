<?php

namespace Modules\BookingModule\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Modules\BookingModule\Entities\AppointmentSetting;

class PublicBookingHoneypot
{
    public function handle(Request $request, Closure $next)
    {
        if (!(bool)config('bookingmodule.public.enable_honeypot', true)) {
            return $next($request);
        }

        // Honeypot field should be empty.
        if ((string)$request->input('_hp', '') !== '') {
            abort(429);
        }

        // Simple timing heuristic (min seconds between render and submit).
        $min = (int)config('bookingmodule.public.honeypot_min_seconds', 3);

// Optional override via appointment_settings (global, workspace NULL)
try {
    if (Schema::hasTable('appointment_settings')) {
        $row = AppointmentSetting::query()
            ->whereNull('workspace')
            ->where('key', 'public.honeypot_min_seconds')
            ->first();
        if ($row && is_numeric($row->value)) {
            $min = (int)$row->value;
        }
    }
} catch (\Throwable $e) {
    // ignore
}
        $ts = (int)$request->input('_ht', 0);

        if ($ts > 0) {
            $delta = time() - $ts;
            if ($delta < max(0, $min)) {
                abort(429);
            }
        }

        return $next($request);
    }
}
