<?php

namespace Modules\CustomerModule\Http\Controllers\Client;

use App\Http\Controllers\AccountBaseController;
use App\Models\User;
use App\Scopes\ActiveScope;
use Illuminate\Http\JsonResponse;

/**
 * FSMBookingsController — renders the "Booking History" tab on the core client show page.
 *
 * Shows FSM orders and/or BookingModule bookings linked to this client.
 * Gracefully degrades when FSMCore / BookingModule is not installed.
 */
class FSMBookingsController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('customermodule::app.bookingHistory');
        $this->middleware('auth');
    }

    /**
     * Return the "Booking History" tab content (AJAX).
     */
    public function show(int $id): JsonResponse
    {
        $client = User::withoutGlobalScope(ActiveScope::class)->findOrFail($id);

        abort_403(! $client->hasRole('client'));
        abort_403(! in_array(user()->permission('view_clients'), ['all', 'added', 'both']));

        $this->client = $client;

        // --- FSM Orders linked to this client (via fsm_locations.client_id if available) ---
        $this->fsmOrders = collect();

        if (class_exists(\Modules\FSMCore\Models\FSMOrder::class)
            && class_exists(\Modules\FSMCore\Models\FSMLocation::class)) {
            try {
                $locationIds = \Modules\FSMCore\Models\FSMLocation::where('client_id', $id)
                    ->pluck('id');

                if ($locationIds->isNotEmpty()) {
                    $this->fsmOrders = \Modules\FSMCore\Models\FSMOrder::with(['stage', 'location'])
                        ->whereIn('location_id', $locationIds)
                        ->orderByDesc('scheduled_date_start')
                        ->get();
                }
            } catch (\Throwable $e) {
                // FSMCore installed but not compatible — fail silently
            }
        }

        $this->view = 'customermodule::client.bookings';

        return $this->returnAjax($this->view);
    }
}
