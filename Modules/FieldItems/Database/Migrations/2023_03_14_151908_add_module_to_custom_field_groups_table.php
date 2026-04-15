<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\CustomFieldGroup;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('custom_field_groups') || !Schema::hasTable('companies')) {
            return;
        }

        $companyIds = DB::table('companies')->pluck('id')->all();

        if (empty($companyIds)) {
            return;
        }

        foreach ($companyIds as $companyId) {
            CustomFieldGroup::firstOrCreate(
                [
                    'company_id' => $companyId,
                    'name' => 'Item',
                    'model' => 'Modules\FieldItems\Entities\Item',
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CustomFieldGroup::where('name', 'Item')->delete();
    }
};
