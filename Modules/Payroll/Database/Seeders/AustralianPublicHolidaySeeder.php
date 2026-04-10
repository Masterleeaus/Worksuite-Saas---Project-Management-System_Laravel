<?php

namespace Modules\Payroll\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AustralianPublicHolidaySeeder extends Seeder
{
    /**
     * Seed national and state-specific Australian public holidays for the current
     * and next calendar year.
     *
     * These are approximate dates; Easter moves each year so we only seed a
     * few years. Admins can adjust via the UI.
     */
    public function run(): void
    {
        $companyId = DB::table('companies')->value('id');

        if (!$companyId) {
            return;
        }

        $years = [date('Y'), date('Y') + 1];

        foreach ($years as $year) {
            $this->seedYear($year, $companyId);
        }
    }

    protected function seedYear(int $year, int $companyId): void
    {
        // National holidays
        $national = [
            "{$year}-01-01" => "New Year's Day",
            "{$year}-01-27" => "Australia Day (observed)",
            "{$year}-04-25" => "Anzac Day",
            "{$year}-12-25" => "Christmas Day",
            "{$year}-12-26" => "Boxing Day",
        ];

        foreach ($national as $date => $name) {
            DB::table('public_holidays')->updateOrInsert(
                ['company_id' => $companyId, 'holiday_date' => $date, 'state' => null],
                ['name' => $name, 'is_national' => true, 'is_manual' => false, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // Queensland
        $qld = [
            "{$year}-05-05" => "Labour Day (QLD)",
            "{$year}-08-14" => "Royal Queensland Show (Brisbane)",
        ];
        foreach ($qld as $date => $name) {
            DB::table('public_holidays')->updateOrInsert(
                ['company_id' => $companyId, 'holiday_date' => $date, 'state' => 'QLD'],
                ['name' => $name, 'is_national' => false, 'is_manual' => false, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // Victoria
        $vic = [
            "{$year}-03-10" => "Labour Day (VIC)",
            "{$year}-11-04" => "Melbourne Cup Day",
        ];
        foreach ($vic as $date => $name) {
            DB::table('public_holidays')->updateOrInsert(
                ['company_id' => $companyId, 'holiday_date' => $date, 'state' => 'VIC'],
                ['name' => $name, 'is_national' => false, 'is_manual' => false, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // New South Wales
        $nsw = [
            "{$year}-06-09" => "King's Birthday (NSW)",
            "{$year}-08-04" => "Bank Holiday (NSW)",
            "{$year}-10-06" => "Labour Day (NSW)",
        ];
        foreach ($nsw as $date => $name) {
            DB::table('public_holidays')->updateOrInsert(
                ['company_id' => $companyId, 'holiday_date' => $date, 'state' => 'NSW'],
                ['name' => $name, 'is_national' => false, 'is_manual' => false, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
