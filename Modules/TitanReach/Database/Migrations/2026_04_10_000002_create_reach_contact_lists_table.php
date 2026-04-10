<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reach_contact_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reach_contact_list_contact', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_list_id')->index();
            $table->unsignedBigInteger('contact_id')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reach_contact_list_contact');
        Schema::dropIfExists('reach_contact_lists');
    }
};
