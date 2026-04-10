<?php

namespace Modules\CustomerModule\Http\Controllers\Client;

use App\Http\Controllers\AccountBaseController;
use App\Models\User;
use App\Scopes\ActiveScope;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\CustomerModule\Models\ClientAddress;

/**
 * ClientAddressController — manages the "Properties" tab on the core client show page.
 *
 * Multiple properties / service addresses per client.
 * Linked to core Worksuite User (client role) via client_id.
 */
class ClientAddressController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('customermodule::app.properties');
        $this->middleware('auth');
    }

    /**
     * Return the "Properties" tab content (AJAX).
     */
    public function index(int $clientId): JsonResponse
    {
        $client = User::withoutGlobalScope(ActiveScope::class)->findOrFail($clientId);

        abort_403(! $client->hasRole('client'));
        abort_403(! in_array(user()->permission('view_clients'), ['all', 'added', 'both']));

        $this->client    = $client;
        $this->addresses = ClientAddress::where('client_id', $clientId)->orderByDesc('is_primary')->get();
        $this->view      = 'customermodule::client.properties';

        return $this->returnAjax($this->view);
    }

    /**
     * Store a new client address.
     */
    public function store(Request $request, int $clientId): JsonResponse
    {
        $client = User::withoutGlobalScope(ActiveScope::class)->findOrFail($clientId);

        abort_403(! $client->hasRole('client'));
        abort_403(! in_array(user()->permission('edit_clients'), ['all', 'added', 'both']));

        $validated = $request->validate([
            'label'                => 'nullable|string|max:100',
            'address_line_1'       => 'required|string|max:255',
            'address_line_2'       => 'nullable|string|max:255',
            'suburb'               => 'nullable|string|max:100',
            'city'                 => 'nullable|string|max:100',
            'state'                => 'nullable|string|max:100',
            'postal_code'          => 'nullable|string|max:20',
            'country'              => 'nullable|string|max:100',
            'property_type'        => 'nullable|string|max:50',
            'special_instructions' => 'nullable|string',
            'pet_info'             => 'nullable|string|max:255',
            'is_primary'           => 'nullable|boolean',
            'key_holding'          => 'nullable|boolean',
            'alarm_code'           => 'nullable|string|max:255',
            'access_notes'         => 'nullable|string|max:500',
        ]);

        if (! empty($validated['is_primary'])) {
            ClientAddress::where('client_id', $clientId)->update(['is_primary' => false]);
        }

        ClientAddress::create(array_merge($validated, [
            'client_id'  => $clientId,
            'company_id' => user()->company_id ?? null,
        ]));

        return response()->json([
            'status'  => 'success',
            'message' => __('messages.createSuccess'),
        ]);
    }

    /**
     * Update an existing client address.
     */
    public function update(Request $request, int $clientId, int $addressId): JsonResponse
    {
        $address = ClientAddress::where('client_id', $clientId)->findOrFail($addressId);

        abort_403(! in_array(user()->permission('edit_clients'), ['all', 'added', 'both']));

        $validated = $request->validate([
            'label'                => 'nullable|string|max:100',
            'address_line_1'       => 'required|string|max:255',
            'address_line_2'       => 'nullable|string|max:255',
            'suburb'               => 'nullable|string|max:100',
            'city'                 => 'nullable|string|max:100',
            'state'                => 'nullable|string|max:100',
            'postal_code'          => 'nullable|string|max:20',
            'country'              => 'nullable|string|max:100',
            'property_type'        => 'nullable|string|max:50',
            'special_instructions' => 'nullable|string',
            'pet_info'             => 'nullable|string|max:255',
            'is_primary'           => 'nullable|boolean',
            'key_holding'          => 'nullable|boolean',
            'alarm_code'           => 'nullable|string|max:255',
            'access_notes'         => 'nullable|string|max:500',
        ]);

        if (! empty($validated['is_primary'])) {
            ClientAddress::where('client_id', $clientId)
                ->where('id', '!=', $addressId)
                ->update(['is_primary' => false]);
        }

        $address->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => __('messages.updateSuccess'),
        ]);
    }

    /**
     * Soft-delete a client address.
     */
    public function destroy(int $clientId, int $addressId): JsonResponse
    {
        $address = ClientAddress::where('client_id', $clientId)->findOrFail($addressId);

        abort_403(! in_array(user()->permission('delete_clients'), ['all', 'added', 'both']));

        $address->delete();

        return response()->json([
            'status'  => 'success',
            'message' => __('messages.deleteSuccess'),
        ]);
    }
}
