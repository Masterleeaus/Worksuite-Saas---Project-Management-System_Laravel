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
        $companyId = DB::table('companies')->value('id');

        $data = [
            'company_id' => $companyId,
            'name' => 'Item',
            'model' => 'Modules\FieldItems\Entities\Item'
        ];

        CustomFieldGroup::firstOrCreate(
            [
                'name' => $data['name'],
                'model' => $data['model'],
            ],
            $data
        );
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
