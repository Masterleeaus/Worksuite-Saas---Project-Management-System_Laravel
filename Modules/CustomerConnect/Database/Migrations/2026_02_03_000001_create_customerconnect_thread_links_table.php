<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_thread_links')) {
            return;
        }

        Schema::create('customerconnect_thread_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('thread_id')->index();

            // Generic record reference (keeps this module decoupled from Worksuite modules)
            $table->string('record_type', 50); // job|invoice|ticket|appointment|project|other
            $table->unsignedBigInteger('record_id')->nullable();

            // Optional display helpers
            $table->string('label', 191)->nullable();
            $table->string('url', 500)->nullable();
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->foreign('thread_id')
                ->references('id')
                ->on('customerconnect_threads')
                ->onDelete('cascade');

            $table->unique(['thread_id', 'record_type', 'record_id'], 'cc_thread_links_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_thread_links');
    }
};
