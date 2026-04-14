<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Feedback Channels
        if (! Schema::hasTable('feedback_channels')) {
            Schema::create('feedback_channels', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('icon')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
                $table->index(['company_id']);
            });
        }

        // Feedback Types
        if (! Schema::hasTable('feedback_types')) {
            Schema::create('feedback_types', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->string('name');
                $table->string('slug');
                $table->text('description')->nullable();
                $table->enum('type_category', ['complaint', 'feedback', 'survey'])->default('feedback');
                $table->boolean('status')->default(true);
                $table->timestamps();
                $table->index(['company_id', 'type_category']);
            });
        }

        // Feedback Groups
        if (! Schema::hasTable('feedback_groups')) {
            Schema::create('feedback_groups', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
                $table->index(['company_id']);
            });
        }

        // Feedback Agent Groups (pivot)
        if (! Schema::hasTable('feedback_agent_groups')) {
            Schema::create('feedback_agent_groups', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->foreignId('group_id')->constrained('feedback_groups')->cascadeOnDelete();
                $table->unsignedInteger('agent_id');
                $table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade');
                $table->unsignedInteger('added_by');
                $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
                $table->timestamps();
                $table->unique(['group_id', 'agent_id']);
                $table->index(['company_id']);
            });
        }

        // Feedback Files
        if (! Schema::hasTable('feedback_files')) {
            Schema::create('feedback_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->foreignId('feedback_id')->constrained('feedback_tickets')->cascadeOnDelete();
                $table->foreignId('reply_id')->nullable()->constrained('feedback_replies')->cascadeOnDelete();
            
                $table->string('filename');
                $table->string('file_path');
                $table->integer('file_size');
                $table->string('mime_type');
            
                $table->timestamps();
                $table->index(['feedback_id']);
                $table->index(['reply_id']);
            });
        }

        // Feedback Tags
        if (! Schema::hasTable('feedback_tags_list')) {
            Schema::create('feedback_tags_list', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('color')->nullable();
                $table->timestamps();
                $table->index(['company_id']);
            });
        }

        // Feedback Tags Pivot
        if (! Schema::hasTable('feedback_tags')) {
            Schema::create('feedback_tags', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->foreignId('feedback_id')->constrained('feedback_tickets')->cascadeOnDelete();
                $table->foreignId('tag_id')->constrained('feedback_tags_list')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['feedback_id', 'tag_id']);
                $table->index(['company_id']);
            });
        }

        // Reply Templates
        if (! Schema::hasTable('feedback_reply_templates')) {
            Schema::create('feedback_reply_templates', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->longText('message');
                $table->enum('reply_type', ['auto', 'manual'])->default('manual');
                $table->boolean('status')->default(true);
                $table->timestamps();
                $table->index(['company_id']);
            });
        }

        // Custom Forms
        if (! Schema::hasTable('feedback_custom_forms')) {
            Schema::create('feedback_custom_forms', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->json('fields');
                $table->boolean('status')->default(true);
                $table->timestamps();
                $table->index(['company_id']);
            });
        }

        // Email Settings
        if (! Schema::hasTable('feedback_email_settings')) {
            Schema::create('feedback_email_settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->string('imap_host');
                $table->integer('imap_port')->default(993);
                $table->enum('imap_encryption', ['ssl', 'tls', 'none'])->default('ssl');
                $table->string('imap_username');
                $table->text('imap_password');
                $table->string('email_address');
                $table->boolean('auto_reply')->default(false);
                $table->longText('reply_message')->nullable();
                $table->timestamp('last_sync')->nullable();
                $table->timestamps();
                $table->index(['company_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_email_settings');
        Schema::dropIfExists('feedback_custom_forms');
        Schema::dropIfExists('feedback_reply_templates');
        Schema::dropIfExists('feedback_tags');
        Schema::dropIfExists('feedback_tags_list');
        Schema::dropIfExists('feedback_files');
        Schema::dropIfExists('feedback_agent_groups');
        Schema::dropIfExists('feedback_groups');
        Schema::dropIfExists('feedback_types');
        Schema::dropIfExists('feedback_channels');
    }
};
