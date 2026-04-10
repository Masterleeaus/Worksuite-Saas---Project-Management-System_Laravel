<?php

namespace Modules\FSMAvailability\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Modules\FSMAvailability\Models\FSMAvailabilityException;
use App\Models\User;

class PublicHolidayController extends Controller
{
    /**
     * Show the public holiday import form.
     */
    public function index(Request $request)
    {
        $states   = config('fsmavailability.au_states', []);
        $year     = $request->integer('year') ?: now()->year;
        $state    = $request->get('state', config('fsmavailability.default_au_state', 'VIC'));
        $holidays = [];

        if ($request->isMethod('get') && ($request->filled('year') || $request->filled('state'))) {
            $holidays = $this->fetchHolidays($year, $state);
        }

        return view('fsmavailability::holidays.index', compact('states', 'year', 'state', 'holidays'));
    }

    /**
     * Import selected public holidays as approved exceptions for all workers
     * (or a specific worker).
     */
    public function import(Request $request)
    {
        $data = $request->validate([
            'year'       => 'required|integer|min:2000|max:2100',
            'state'      => 'required|string|max:10',
            'dates'      => 'required|array|min:1',
            'dates.*'    => 'date',
            'person_ids' => 'nullable|array',
            'person_ids.*' => 'integer|exists:users,id',
        ]);

        $holidays = $this->fetchHolidays($data['year'], $data['state']);
        $holidayMap = collect($holidays)->keyBy('date');

        $personIds = !empty($data['person_ids'])
            ? $data['person_ids']
            : User::pluck('id')->all();

        $companyId = auth()->user()?->company_id ?? null;
        $created   = 0;

        foreach ($data['dates'] as $dateStr) {
            $holiday = $holidayMap->get($dateStr);
            if (!$holiday) {
                continue;
            }

            foreach ($personIds as $pid) {
                $exists = FSMAvailabilityException::where('person_id', $pid)
                    ->where('reason', 'public_holiday')
                    ->whereDate('date_start', $dateStr)
                    ->exists();

                if (!$exists) {
                    FSMAvailabilityException::create([
                        'company_id'  => $companyId,
                        'person_id'   => $pid,
                        'date_start'  => $dateStr . ' 00:00:00',
                        'date_end'    => $dateStr . ' 23:59:59',
                        'reason'      => 'public_holiday',
                        'notes'       => $holiday['name'] ?? 'Public Holiday',
                        'approved_by' => auth()->id(),
                        'state'       => 'approved',
                    ]);
                    $created++;
                }
            }
        }

        return redirect()->route('fsmavailability.holidays.index', [
                'year'  => $data['year'],
                'state' => $data['state'],
            ])
            ->with('success', "Imported {$created} public holiday exception(s).");
    }

    /**
     * Fetch Australian public holidays from the Nager.Date API.
     *
     * Returns an array of ['date' => 'YYYY-MM-DD', 'name' => '...', 'counties' => [...]]
     */
    private function fetchHolidays(int $year, string $state): array
    {
        $url = str_replace('{year}', $year, config('fsmavailability.holiday_api_url'));

        try {
            $response = Http::timeout(10)->get($url);

            if (!$response->ok()) {
                return [];
            }

            $all = $response->json() ?? [];

            // Filter to national holidays + state-specific ones.
            return array_values(array_filter($all, function ($h) use ($state) {
                $counties = $h['counties'] ?? null;
                // Null counties means national holiday.
                if ($counties === null) {
                    return true;
                }
                // counties is an array of state abbreviation strings like "AU-VIC".
                foreach ($counties as $county) {
                    if (str_ends_with($county, $state)) {
                        return true;
                    }
                }
                return false;
            }));
        } catch (\Throwable) {
            return [];
        }
    }
}
