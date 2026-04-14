<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('customerconnect_tags')) {
            Schema::create('customerconnect_tags', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('name', 60);
                $table->string('color', 20)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('customerconnect_thread_tags')) {
            Schema::create('customerconnect_thread_tags', function (Blueprint $table) {
                $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('thread_id')->index();
                $table->unsignedBigInteger('tag_id')->index();
                $table->timestamps();
                $table->unique(['thread_id','tag_id']);
            });
        }

        if (!Schema::hasTable('customerconnect_saved_filters')) {
            Schema::create('customerconnect_saved_filters', function (Blueprint $table) {
                $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('name', 100);
                $table->json('criteria');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_saved_filters');
        Schema::dropIfExists('customerconnect_thread_tags');
        Schema::dropIfExists('customerconnect_tags');
    }
};
