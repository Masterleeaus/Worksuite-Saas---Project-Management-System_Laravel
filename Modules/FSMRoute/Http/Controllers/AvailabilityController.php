<?php

namespace Modules\FSMRoute\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMRoute\Models\FSMWorkerAvailability;
use App\Models\User;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $weekStart = $request->date
            ? Carbon::parse($request->date)->startOfWeek(Carbon::MONDAY)
            : Carbon::today()->startOfWeek(Carbon::MONDAY);

        $weekDays = collect(range(0, 6))->map(fn($i) => $weekStart->copy()->addDays($i));

        $users = User::orderBy('name')->get();

        $rawAvailability = FSMWorkerAvailability::whereBetween('date', [
            $weekStart->toDateString(),
            $weekStart->copy()->endOfWeek()->toDateString(),
        ])->get();

        $availability = [];
        foreach ($rawAvailability as $record) {
            $availability[$record->person_id][$record->date->toDateString()] = $record;
        }

        return view('fsmroute::availability.index', compact('weekStart', 'weekDays', 'users', 'availability'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'person_id' => 'required|integer',
            'date'      => 'required|date',
            'available' => 'nullable|boolean',
            'reason'    => 'nullable|string|max:256',
        ]);

        FSMWorkerAvailability::updateOrCreate(
            ['person_id' => $data['person_id'], 'date' => $data['date']],
            [
                'available' => $request->boolean('available', true),
                'reason'    => $data['reason'] ?? null,
            ]
        );

        return redirect()->back()->with('success', 'Availability updated.');
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'person_id' => 'required|integer',
            'date'      => 'required|date',
        ]);

        FSMWorkerAvailability::where('person_id', $data['person_id'])
            ->where('date', $data['date'])
            ->delete();

        return redirect()->back()->with('success', 'Availability removed.');
    }
}
