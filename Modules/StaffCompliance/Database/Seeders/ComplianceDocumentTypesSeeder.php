<?php

namespace Modules\StaffCompliance\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ComplianceDocumentTypesSeeder extends Seeder
{
    public function run()
    {
        if (!Schema::hasTable('compliance_document_types')) {
            return;
        }

        $types = [
            [
                'name'                  => 'National Police Check',
                'code'                  => 'police_check',
                'vertical'              => null,
                'is_mandatory'          => true,
                'renewal_period_months' => 36,
                'description'           => 'National criminal history check required for all field workers.',
            ],
            [
                'name'                  => 'Working with Children Check',
                'code'                  => 'wwcc',
                'vertical'              => json_encode(['childcare', 'education', 'domestic']),
                'is_mandatory'          => false,
                'renewal_period_months' => 60,
                'description'           => 'Required when working with or around children.',
            ],
            [
                'name'                  => 'First Aid Certificate',
                'code'                  => 'first_aid',
                'vertical'              => null,
                'is_mandatory'          => false,
                'renewal_period_months' => 36,
                'description'           => 'Current first aid certification.',
            ],
            [
                'name'                  => 'Chemical Handling Certificate',
                'code'                  => 'chemical_handling',
                'vertical'              => json_encode(['cleaning']),
                'is_mandatory'          => false,
                'renewal_period_months' => 24,
                'description'           => 'Required for workers who handle hazardous chemicals.',
            ],
            [
                'name'                  => 'Public Liability Insurance',
                'code'                  => 'public_liability',
                'vertical'              => null,
                'is_mandatory'          => true,
                'renewal_period_months' => 12,
                'description'           => 'Public liability insurance certificate.',
            ],
            [
                'name'                  => 'Electrical Licence',
                'code'                  => 'electrical_licence',
                'vertical'              => json_encode(['electrical']),
                'is_mandatory'          => false,
                'renewal_period_months' => 60,
                'description'           => 'Licensed electrical work permit.',
            ],
            [
                'name'                  => 'Plumbing Licence',
                'code'                  => 'plumbing_licence',
                'vertical'              => json_encode(['plumbing']),
                'is_mandatory'          => false,
                'renewal_period_months' => 60,
                'description'           => 'Licensed plumbing work permit.',
            ],
            [
                'name'                  => 'Security Industry Licence',
                'code'                  => 'security_licence',
                'vertical'              => json_encode(['security']),
                'is_mandatory'          => false,
                'renewal_period_months' => 12,
                'description'           => 'State-issued security industry licence.',
            ],
            [
                'name'                  => 'Asbestos Awareness Training',
                'code'                  => 'asbestos_awareness',
                'vertical'              => json_encode(['construction', 'renovation']),
                'is_mandatory'          => false,
                'renewal_period_months' => 12,
                'description'           => 'Awareness training for asbestos-containing materials.',
            ],
            [
                'name'                  => "Driver's Licence",
                'code'                  => 'drivers_licence',
                'vertical'              => null,
                'is_mandatory'          => false,
                'renewal_period_months' => null,
                'description'           => "Current driver's licence for field workers requiring vehicle use.",
            ],
        ];

        foreach ($types as $type) {
            DB::table('compliance_document_types')->updateOrInsert(
                ['code' => $type['code']],
                array_merge($type, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
