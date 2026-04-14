<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('projectroadmap_items')) {
            Schema::create('projectroadmap_items', function (Blueprint $table) {
                $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('name')->nullable();
                $table->text('description')->nullable();
                $table->integer('position')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('projectroadmap_items');
    }
};