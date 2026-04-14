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
        if (!Schema::hasTable('ai_template_categories'))
        {
            Schema::create('ai_template_categories', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->boolean('status')->default(true)->comment('1=>active,0=>deactive'); 
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
        Schema::dropIfExists('ai_template_categories');
    }
};
