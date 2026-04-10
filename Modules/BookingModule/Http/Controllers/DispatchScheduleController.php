<?php

namespace Modules\BookingModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Http\Requests\Dispatch\DispatchUpdateScheduleRequest;
use Modules\BookingModule\Services\Dispatch\DispatchScheduleUpdateService;

class DispatchScheduleController extends Controller
{
    public function __construct(protected DispatchScheduleUpdateService $service) {}

    public function edit(Request $request, int $id)
    {
        $this->authorizeDispatch();

        if (!config('bookingmodule::dispatch.allow_quick_edit', true)) {
            abort(404);
        }

        $schedule = Schedule::findOrFail($id);

        return view('bookingmodule::dispatch.partials._edit_modal', [
            'schedule' => $schedule,
        ]);
    }

    public function update(DispatchUpdateScheduleRequest $request, int $id)
    {
        $this->authorizeDispatch();

        if (!config('bookingmodule::dispatch.allow_quick_edit', true)) {
            abort(404);
        }

        $result = $this->service->update($id, $request->validated());

        return response()->json($result, $result['ok'] ? 200 : 422);
    }

    protected function authorizeDispatch(): void
    {
        if (function_exists('permissionCheck')) {
            abort_unless(permissionCheck('appointment dispatch'), 403);
            return;
        }

        $user = Auth::user();
        if (!$user || (method_exists($user, 'can') && !$user->can('appointment dispatch'))) {
            abort(403);
        }
    }
}
