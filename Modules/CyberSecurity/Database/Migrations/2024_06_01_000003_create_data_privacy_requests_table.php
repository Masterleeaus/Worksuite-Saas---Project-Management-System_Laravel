<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{

    public function up(): void
    {
        if (!Schema::hasTable('data_privacy_requests')) {
            Schema::create('data_privacy_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->enum('type', ['access', 'deletion', 'rectification', 'portability'])->default('access');
                $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected'])->default('pending');
                $table->string('requester_name');
                $table->string('requester_email');
                $table->unsignedBigInteger('client_id')->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('due_date')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->unsignedBigInteger('handled_by')->nullable();
                $table->string('export_path')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('data_privacy_requests');
    }

};
