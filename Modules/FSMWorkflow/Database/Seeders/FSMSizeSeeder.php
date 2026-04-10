<?php

namespace Modules\FSMWorkflow\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\FSMWorkflow\Models\FSMSize;

class FSMSizeSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['code' => 'XS', 'name' => 'Extra Small', 'description' => 'Under 1 hour – Small apartment touch-up',        'sequence' => 10],
            ['code' => 'S',  'name' => 'Small',       'description' => '1–2 hours – Standard apartment clean',            'sequence' => 20],
            ['code' => 'M',  'name' => 'Medium',      'description' => '2–4 hours – House clean / small office',          'sequence' => 30],
            ['code' => 'L',  'name' => 'Large',       'description' => '4–8 hours – Large house / medium office',         'sequence' => 40],
            ['code' => 'XL', 'name' => 'Extra Large', 'description' => '8+ hours – Commercial / multi-floor',             'sequence' => 50],
        ];

        foreach ($defaults as $row) {
            FSMSize::firstOrCreate(
                ['code' => $row['code'], 'company_id' => null],
                array_merge($row, ['active' => true])
            );
        }
    }
}
