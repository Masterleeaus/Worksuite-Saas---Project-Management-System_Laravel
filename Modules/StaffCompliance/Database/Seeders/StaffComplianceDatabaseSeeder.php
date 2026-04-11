<?php

namespace Modules\StaffCompliance\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class StaffComplianceDatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        $this->call(ComplianceDocumentTypesSeeder::class);
    }
}
