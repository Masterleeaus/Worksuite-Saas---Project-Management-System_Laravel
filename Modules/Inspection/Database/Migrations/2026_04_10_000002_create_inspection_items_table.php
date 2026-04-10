<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('inspection_items')) {
            return;
        }

        Schema::create('inspection_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inspection_id');
            $table->foreign('inspection_id')->references('id')->on('inspections')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->string('area', 191)->comment('e.g. kitchen, bathroom, bedroom');
            $table->boolean('passed')->default(false);
            $table->text('notes')->nullable();
            $table->string('photo_path', 500)->nullable();
            $table->timestamps();

            $table->index(['inspection_id']);
            $table->index(['inspection_id', 'passed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_items');
    }
};
