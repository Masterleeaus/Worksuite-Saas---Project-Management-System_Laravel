<?php

namespace Modules\CustomerModule\Http\Controllers\Client;

use App\Http\Controllers\AccountBaseController;
use App\Models\ClientDetails;
use App\Models\User;
use App\Scopes\ActiveScope;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * CleaningInfoController — manages the FSM "Cleaning Info" tab overlay on the core client show page.
 *
 * This is a child controller that extends core functionality via the
 * @stack('client-fsm-tabs') injection in resources/views/clients/show.blade.php.
 * It does NOT duplicate core CRUD — it only manages the extra FSM fields.
 */
class CleaningInfoController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('customermodule::app.cleaningInfo');
        $this->middleware('auth');
    }

    /**
     * Return the "Cleaning Info" tab content for a client (AJAX).
     */
    public function show(int $id): JsonResponse
    {
        $this->client = User::withoutGlobalScope(ActiveScope::class)->findOrFail($id);

        abort_403(! ($this->client->hasRole('client')));
        abort_403(! in_array(user()->permission('view_clients'), ['all', 'added', 'both']));

        $clientDetail = ClientDetails::where('user_id', $id)->first();

        // Resolve preferred cleaner name
        $preferredCleaner = null;

        if ($clientDetail && $clientDetail->preferred_cleaner_id) {
            $preferredCleaner = User::withoutGlobalScope(ActiveScope::class)
                ->select('id', 'name')
                ->find($clientDetail->preferred_cleaner_id);
        }

        $this->view   = 'customermodule::client.cleaning-info';
        $this->detail = $clientDetail;
        $this->cleaner   = $preferredCleaner;

        return $this->returnAjax($this->view);
    }

    /**
     * Persist FSM cleaning fields back to client_details.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $client = User::withoutGlobalScope(ActiveScope::class)->findOrFail($id);

        abort_403(! ($client->hasRole('client')));
        abort_403(! in_array(user()->permission('edit_clients'), ['all', 'added', 'both']));

        $validated = $request->validate([
            'preferred_cleaner_id' => 'nullable|integer|exists:users,id',
            'key_holding'          => 'nullable|boolean',
            'pet_info'             => 'nullable|string|max:255',
            'alarm_code'           => 'nullable|string|max:255',
            'access_notes'         => 'nullable|string|max:500',
            'client_tag'           => 'nullable|in:residential,commercial,strata,airbnb,vip',
        ]);

        $detail = ClientDetails::where('user_id', $id)->first();

        if ($detail) {
            $detail->fill(array_filter($validated, fn ($v) => $v !== null));
            $detail->save();
        }

        return response()->json([
            'status'  => 'success',
            'message' => __('messages.updateSuccess'),
        ]);
    }
}
