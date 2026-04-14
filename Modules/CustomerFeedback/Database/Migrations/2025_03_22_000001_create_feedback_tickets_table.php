<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('feedback_tickets')) {
            return;
        }
        Schema::create('feedback_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('agent_id')->nullable();
            $table->foreign('agent_id')->references('id')->on('users')->nullOnDelete();
            
            $table->string('title');
            $table->longText('description');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed', 'pending'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('feedback_type', ['complaint', 'feedback', 'survey_response'])->default('feedback');
            
            $table->foreignId('channel_id')->nullable()->constrained('feedback_channels')->nullOnDelete();
            $table->foreignId('group_id')->nullable()->constrained('feedback_groups')->nullOnDelete();
            $table->foreignId('type_id')->nullable()->constrained('feedback_types')->nullOnDelete();
            
            $table->integer('nps_score')->nullable();
            $table->integer('csat_score')->nullable();
            
            $table->json('custom_meta')->nullable();
            $table->json('ai_metadata')->nullable();
            $table->string('email_thread_id')->nullable();
            
            $table->boolean('read')->default(false);
            $table->timestamp('resolved_at')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['company_id', 'feedback_type']);
            $table->index(['company_id', 'status']);
            $table->index(['agent_id', 'status']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_tickets');
    }
};
