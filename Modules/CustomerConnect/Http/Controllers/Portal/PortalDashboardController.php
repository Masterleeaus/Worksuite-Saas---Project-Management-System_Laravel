<?php

namespace Modules\CustomerConnect\Http\Controllers\Portal;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * PortalDashboardController — customer self-service portal landing page.
 *
 * Shows: next booking, outstanding invoices count, last visit summary.
 * All inter-module lookups are guarded with class_exists / Schema::hasTable
 * so the portal degrades gracefully when optional modules are absent.
 */
class PortalDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // ── Next booking ──────────────────────────────────────────────────────
        $nextBooking = null;
        if (class_exists(\Modules\BookingModule\Models\CleaningBooking::class)
            && Schema::hasTable('tasks')
        ) {
            try {
                $nextBooking = \Modules\BookingModule\Models\CleaningBooking::query()
                    ->where('client_id', $user->id)
                    ->whereNotIn('booking_status', ['completed', 'cancelled'])
                    ->orderBy('start_date_time')
                    ->first();
            } catch (\Throwable) {
            }
        }

        // ── Outstanding invoices count ────────────────────────────────────────
        $outstandingInvoicesCount = 0;
        if (class_exists(\App\Models\Invoice::class)
            && Schema::hasTable('invoices')
        ) {
            try {
                $outstandingInvoicesCount = \App\Models\Invoice::query()
                    ->where('client_id', $user->id)
                    ->where('status', '!=', 'paid')
                    ->count();
            } catch (\Throwable) {
            }
        }

        // ── Last completed booking ────────────────────────────────────────────
        $lastBooking = null;
        if (class_exists(\Modules\BookingModule\Models\CleaningBooking::class)
            && Schema::hasTable('tasks')
        ) {
            try {
                $lastBooking = \Modules\BookingModule\Models\CleaningBooking::query()
                    ->where('client_id', $user->id)
                    ->where('booking_status', 'completed')
                    ->orderByDesc('start_date_time')
                    ->first();
            } catch (\Throwable) {
            }
        }

        // ── Properties count ─────────────────────────────────────────────────
        $propertiesCount = 0;
        if (class_exists(\Modules\ManagedPremises\Entities\Property::class)
            && Schema::hasTable('properties')
        ) {
            try {
                $propertiesCount = \Modules\ManagedPremises\Entities\Property::query()
                    ->where('client_id', $user->id)
                    ->count();
            } catch (\Throwable) {
            }
        }

        return view('customerconnect::portal.dashboard', compact(
            'nextBooking',
            'outstandingInvoicesCount',
            'lastBooking',
            'propertiesCount'
        ));
    }
}
