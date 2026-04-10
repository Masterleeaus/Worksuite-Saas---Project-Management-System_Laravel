<?php

namespace Modules\FSMAvailability\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMAvailability\Models\FSMAvailabilityException;
use App\Models\User;

class AvailabilityExceptionController extends Controller
{
    public function index(Request $request)
    {
        $q = FSMAvailabilityException::with('person')
            ->orderByDesc('date_start');

        if ($request->filled('person_id')) {
            $q->where('person_id', (int) $request->get('person_id'));
        }

        if ($request->filled('state')) {
            $q->where('state', $request->string('state')->toString());
        }

        $exceptions = $q->paginate(50)->withQueryString();
        $workers    = User::orderBy('name')->get();
        $states     = FSMAvailabilityException::$states;
        $filter     = $request->only(['person_id', 'state']);

        return view('fsmavailability::exceptions.index', compact(
            'exceptions', 'workers', 'states', 'filter'
        ));
    }

    public function create()
    {
        $workers = User::orderBy('name')->get();
        $reasons = FSMAvailabilityException::$reasons;

        return view('fsmavailability::exceptions.create', compact('workers', 'reasons'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'person_id'  => 'required|integer|exists:users,id',
            'date_start' => 'required|date',
            'date_end'   => 'required|date|after_or_equal:date_start',
            'reason'     => 'required|in:leave,sick,public_holiday,training,other',
            'notes'      => 'nullable|string|max:65535',
        ]);

        $data['company_id'] = auth()->user()?->company_id ?? null;
        $data['state']      = 'pending';

        FSMAvailabilityException::create($data);

        return redirect()->route('fsmavailability.exceptions.index')
            ->with('success', 'Unavailability exception submitted for approval.');
    }

    public function show(int $id)
    {
        $exception = FSMAvailabilityException::with(['person', 'approvedBy'])->findOrFail($id);
        $reasons   = FSMAvailabilityException::$reasons;
        $states    = FSMAvailabilityException::$states;

        return view('fsmavailability::exceptions.show', compact('exception', 'reasons', 'states'));
    }

    public function edit(int $id)
    {
        $exception = FSMAvailabilityException::findOrFail($id);
        $workers   = User::orderBy('name')->get();
        $reasons   = FSMAvailabilityException::$reasons;

        return view('fsmavailability::exceptions.edit', compact('exception', 'workers', 'reasons'));
    }

    public function update(Request $request, int $id)
    {
        $exception = FSMAvailabilityException::findOrFail($id);

        $data = $request->validate([
            'person_id'  => 'required|integer|exists:users,id',
            'date_start' => 'required|date',
            'date_end'   => 'required|date|after_or_equal:date_start',
            'reason'     => 'required|in:leave,sick,public_holiday,training,other',
            'notes'      => 'nullable|string|max:65535',
        ]);

        $exception->update($data);

        return redirect()->route('fsmavailability.exceptions.show', $id)
            ->with('success', 'Exception updated.');
    }

    public function destroy(int $id)
    {
        FSMAvailabilityException::findOrFail($id)->delete();

        return redirect()->route('fsmavailability.exceptions.index')
            ->with('success', 'Exception deleted.');
    }

    /** Approve a pending exception. */
    public function approve(int $id)
    {
        $exception = FSMAvailabilityException::findOrFail($id);
        $exception->update([
            'state'       => 'approved',
            'approved_by' => auth()->id(),
        ]);

        // Flag any Day Routes assigned to this worker that overlap the exception.
        $this->flagDayRoutes($exception);

        return redirect()->route('fsmavailability.exceptions.show', $id)
            ->with('success', 'Exception approved.');
    }

    /** Reject a pending exception. */
    public function reject(int $id)
    {
        $exception = FSMAvailabilityException::findOrFail($id);
        $exception->update(['state' => 'rejected']);

        return redirect()->route('fsmavailability.exceptions.show', $id)
            ->with('success', 'Exception rejected.');
    }

    /**
     * When an exception is approved, flag any FSMRoute DayRoutes assigned to
     * this worker that fall within the exception window (FSMRoute optional integration).
     */
    private function flagDayRoutes(FSMAvailabilityException $exception): void
    {
        if (!class_exists(\Modules\FSMRoute\Models\FSMDayRoute::class)) {
            return;
        }

        if (!\Illuminate\Support\Facades\Schema::hasTable('fsm_day_routes')) {
            return;
        }

        \Modules\FSMRoute\Models\FSMDayRoute::where('person_id', $exception->person_id)
            ->whereBetween('date', [
                $exception->date_start->toDateString(),
                $exception->date_end->toDateString(),
            ])
            ->update(['availability_flagged' => true]);
    }
}
