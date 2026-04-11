<?php

use App\Models\Company;
use Modules\Aitools\Entities\AiToolsSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('ai_tools_settings')) {
            return;
        }
        Schema::create('ai_tools_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->unsigned()->nullable();
            $table->text('chatgpt_api_key')->nullable();
            // model name
            $table->string('model_name')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_tools_settings');
    }
};
