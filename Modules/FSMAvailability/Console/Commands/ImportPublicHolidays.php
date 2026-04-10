<?php

namespace Modules\FSMAvailability\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Modules\FSMAvailability\Models\FSMAvailabilityException;
use App\Models\User;

class ImportPublicHolidays extends Command
{
    protected $signature = 'fsm:availability:import-holidays
                            {--year= : The year to import (defaults to current year)}
                            {--state= : The Australian state/territory code (e.g. VIC, NSW)}
                            {--all-workers : Import for all workers (default: all)}';

    protected $description = 'Import Australian public holidays as approved availability exceptions for all workers.';

    public function handle(): int
    {
        $year  = (int) ($this->option('year')  ?: now()->year);
        $state = strtoupper((string) ($this->option('state') ?: config('fsmavailability.default_au_state', 'VIC')));

        $this->info("Fetching {$year} public holidays for state: {$state}");

        $url      = str_replace('{year}', $year, config('fsmavailability.holiday_api_url'));
        $holidays = [];

        try {
            $response = Http::timeout(10)->get($url);
            if (!$response->ok()) {
                $this->error('Failed to fetch holidays from API. Status: ' . $response->status());
                return self::FAILURE;
            }

            $all = $response->json() ?? [];
            $holidays = array_values(array_filter($all, function ($h) use ($state) {
                $counties = $h['counties'] ?? null;
                if ($counties === null) {
                    return true;
                }
                foreach ($counties as $county) {
                    if (str_ends_with($county, $state)) {
                        return true;
                    }
                }
                return false;
            }));
        } catch (\Throwable $e) {
            $this->error('API request failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        if (empty($holidays)) {
            $this->warn('No holidays found for the given state/year combination.');
            return self::SUCCESS;
        }

        $this->info('Found ' . count($holidays) . ' holiday(s).');

        $personIds = User::pluck('id')->all();
        $created   = 0;

        foreach ($holidays as $holiday) {
            $dateStr = $holiday['date'] ?? null;
            $name    = $holiday['localName'] ?? $holiday['name'] ?? 'Public Holiday';

            if (!$dateStr) {
                continue;
            }

            foreach ($personIds as $pid) {
                $exists = FSMAvailabilityException::where('person_id', $pid)
                    ->where('reason', 'public_holiday')
                    ->whereDate('date_start', $dateStr)
                    ->exists();

                if (!$exists) {
                    FSMAvailabilityException::create([
                        'company_id' => null,
                        'person_id'  => $pid,
                        'date_start' => $dateStr . ' 00:00:00',
                        'date_end'   => $dateStr . ' 23:59:59',
                        'reason'     => 'public_holiday',
                        'notes'      => $name,
                        'state'      => 'approved',
                    ]);
                    $created++;
                }
            }

            $this->line("  {$dateStr}: {$name}");
        }

        $this->info("Created {$created} exception record(s).");
        return self::SUCCESS;
    }
}
