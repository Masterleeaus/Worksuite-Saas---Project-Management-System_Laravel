<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// fieldservice_stage_server_action: automated actions triggered on stage transitions
return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('fsm_stage_actions')) {
            Schema::create('fsm_stage_actions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('name');
                $table->unsignedBigInteger('stage_id');

                // What action to perform when an order enters this stage
                $table->enum('action_type', [
                    'send_email',       // fire notification template
                    'send_sms',         // fire SMS
                    'set_field',        // update a field on the order
                    'webhook',          // POST to external URL
                    'assign_worker',    // auto-assign based on territory
                    'create_invoice',   // trigger invoice creation
                ])->default('send_email');

                // Payload differs by action_type
                $table->string('email_template')->nullable();
                $table->string('sms_template')->nullable();
                $table->string('set_field_name')->nullable();
                $table->string('set_field_value')->nullable();
                $table->string('webhook_url')->nullable();
                $table->json('webhook_headers')->nullable();

                $table->boolean('active')->default(true);
                $table->integer('sequence')->default(1);
                $table->timestamps();

                if (Schema::hasTable('fsm_stages')) {
                    $table->foreign('stage_id')->references('id')
                          ->on('fsm_stages')->cascadeOnDelete();
                }
            });
        }

        // Log table to track which actions ran on which orders
        if (! Schema::hasTable('fsm_stage_action_logs')) {
            Schema::create('fsm_stage_action_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stage_action_id');
                $table->unsignedBigInteger('fsm_order_id');
                $table->enum('status', ['success', 'failed', 'skipped'])->default('success');
                $table->text('message')->nullable();
                $table->timestamp('ran_at')->useCurrent();

                $table->foreign('stage_action_id')->references('id')
                      ->on('fsm_stage_actions')->cascadeOnDelete();
                if (Schema::hasTable('fsm_orders')) {
                    $table->foreign('fsm_order_id')->references('id')
                          ->on('fsm_orders')->cascadeOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_stage_action_logs');
        Schema::dropIfExists('fsm_stage_actions');
    }
};
