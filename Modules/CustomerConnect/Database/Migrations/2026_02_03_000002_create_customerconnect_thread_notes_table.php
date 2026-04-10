<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_thread_notes')) {
            return;
        }

        Schema::create('customerconnect_thread_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('thread_id')->index();

            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->text('body');
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->foreign('thread_id')
                ->references('id')
                ->on('customerconnect_threads')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_thread_notes');
    }
};
