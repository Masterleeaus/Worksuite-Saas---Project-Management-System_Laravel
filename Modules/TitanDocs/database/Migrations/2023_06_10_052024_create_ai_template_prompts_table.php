<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('ai_template_prompts'))
        {
            Schema::create('ai_template_prompts', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('template_id')->nullable();
                $table->string('key')->nullable();
                $table->longText('value')->nullable();
                $table->string('created_by')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_template_prompts');
    }
};
