<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('security_records')) {
            return;
        }

        Schema::create('security_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('related_access_card_id')->nullable();
            $table->unsignedBigInteger('related_work_permit_id')->nullable();
            $table->unsignedBigInteger('related_package_id')->nullable();
            $table->string('record_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('severity')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_records');
    }
};
