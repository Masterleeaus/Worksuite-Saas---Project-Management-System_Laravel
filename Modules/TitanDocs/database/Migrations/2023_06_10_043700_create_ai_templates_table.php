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
        if (!Schema::hasTable('ai_templates'))
        {
            Schema::create('ai_templates', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('icon')->nullable();
                $table->longText('description')->nullable(); 
                $table->string('template_code');  
                $table->boolean('status')->default(true)->comment('1=>active,0=>deactive'); 
                $table->boolean('professional')->default(false)->comment('1=>yes,0=>no'); 
                $table->string('slug');
                $table->string('category_id'); 
                $table->string('type')->default('1')->comment('1=>original,0=>custom'); 
                $table->string('form_fields',5000)->nullable();
                $table->boolean('is_tone')->default(true)->comment('1=>active,0=>deactive'); 
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
        Schema::dropIfExists('ai_templates');
    }
};
