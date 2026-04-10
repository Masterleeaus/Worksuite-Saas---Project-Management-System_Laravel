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
        if (!Schema::hasTable('ai_prompt_responses'))
        {
            Schema::create('ai_prompt_responses', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('template_id')->nullable();
                $table->string('history_prompt_id')->nullable();
                $table->string('used_words')->nullable();
                $table->longText('content')->nullable();
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
        Schema::dropIfExists('ai_prompt_responses');
    }
};
