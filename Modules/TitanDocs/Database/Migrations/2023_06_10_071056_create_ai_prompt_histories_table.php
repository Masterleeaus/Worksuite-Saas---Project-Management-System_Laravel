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
        if (!Schema::hasTable('ai_prompt_histories'))
        {
            Schema::create('ai_prompt_histories', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('template_id')->nullable();
                $table->string('doc_name')->nullable();
                $table->string('model')->nullable();
                $table->string('creativity')->nullable();
                $table->string('max_tokens')->nullable();
                $table->string('max_results')->nullable();
                $table->longText('prompt')->nullable();
                $table->string('language')->nullable();
                $table->text('prompt_fields')->nullable();
                $table->integer('workspace')->nullable();
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
        Schema::dropIfExists('ai_prompt_histories');
    }
};
