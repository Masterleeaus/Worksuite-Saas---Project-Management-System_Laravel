<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\CustomFieldGroup;
use App\Models\Company;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companyId = Company::query()->whereKey(1)->exists() ? 1 : null;

        $data = [
            'company_id' => $companyId,
            'name' => 'Item',
            'model' => 'Modules\FieldItems\Entities\Item'
        ];

        CustomFieldGroup::firstOrCreate($data);
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
