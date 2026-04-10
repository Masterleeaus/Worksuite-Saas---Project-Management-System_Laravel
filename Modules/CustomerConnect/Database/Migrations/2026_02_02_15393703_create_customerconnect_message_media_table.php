<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customerconnect_message_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('message_id')->index();
            $table->enum('kind', ['audio','image','file'])->index();
            $table->string('storage_disk', 64)->default('public');
            $table->string('storage_path', 1024);
            $table->string('mime', 191)->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->longText('transcript')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['company_id','user_id','message_id'], 'cc_media_tenant_msg_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_message_media');
    }
};
