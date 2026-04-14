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
        if (!Schema::hasTable('ai_template_languages'))
        {
            Schema::create('ai_template_languages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('language');
                $table->string('code')->unique();
                $table->string('flag')->nullable();
                $table->boolean('status')->default(true);
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
        Schema::dropIfExists('ai_template_languages');
    }
};
