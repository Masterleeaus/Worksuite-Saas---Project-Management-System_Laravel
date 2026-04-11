<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_thread_reads')) {
            return;
        }
        Schema::create('customerconnect_thread_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('thread_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();
            $table->unique(['thread_id','user_id'], 'cc_thread_user_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_thread_reads');
    }
};
