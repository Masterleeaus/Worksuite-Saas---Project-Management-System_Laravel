<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dispatch_workers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 64)->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('team_id')->nullable()->index();
            $table->unsignedBigInteger('location_id')->nullable()->index();
            $table->json('skills')->nullable();
            $table->json('business_hour')->nullable();
            $table->json('flex_form_data')->nullable();
            $table->unsignedBigInteger('worksuite_user_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('dispatch_teams')->nullOnDelete();
            $table->foreign('location_id')->references('id')->on('dispatch_locations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatch_workers');
    }
};
