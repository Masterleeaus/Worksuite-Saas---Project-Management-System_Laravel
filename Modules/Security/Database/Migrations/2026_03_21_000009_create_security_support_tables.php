<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tr_card_items')) {
            Schema::create('tr_card_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('access_card_id')->nullable();
                $table->string('item_name');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tr_package_types')) {
            Schema::create('tr_package_types', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('name');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tr_couriers')) {
            Schema::create('tr_couriers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('name');
                $table->string('contact')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tr_package_items')) {
            Schema::create('tr_package_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('package_id')->nullable();
                $table->string('item_name');
                $table->integer('quantity')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tr_work_permit_files')) {
            Schema::create('tr_work_permit_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('work_permit_id')->nullable();
                $table->string('file_path');
                $table->string('file_name')->nullable();
                $table->string('file_type')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tr_parking_items')) {
            Schema::create('tr_parking_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('parking_id')->nullable();
                $table->string('item_name');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tr_parking_items');
        Schema::dropIfExists('tr_work_permit_files');
        Schema::dropIfExists('tr_package_items');
        Schema::dropIfExists('tr_couriers');
        Schema::dropIfExists('tr_package_types');
        Schema::dropIfExists('tr_card_items');
    }
};
