<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_service_agreements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name', 64)->unique(); // e.g. SRV-2025-001
            $table->unsignedBigInteger('partner_id')->nullable()->index(); // FK → client (user/contact)
            $table->date('start_date');
            $table->date('end_date')->nullable(); // null = ongoing
            $table->enum('state', ['draft', 'active', 'expired', 'cancelled'])->default('draft')->index();
            $table->text('recurrence_rule')->nullable(); // JSON recurrence pattern
            $table->text('notes')->nullable(); // contract terms summary
            $table->decimal('value', 14, 2)->default(0.00); // total contract value
            $table->timestamps();
        });

        // Many-to-many: agreement ↔ fsm_locations
        Schema::create('fsm_agreement_location', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fsm_service_agreement_id');
            $table->unsignedBigInteger('fsm_location_id');

            $table->foreign('fsm_service_agreement_id')
                ->references('id')->on('fsm_service_agreements')->cascadeOnDelete();
            $table->foreign('fsm_location_id')
                ->references('id')->on('fsm_locations')->cascadeOnDelete();

            $table->unique(['fsm_service_agreement_id', 'fsm_location_id'], 'uniq_agreement_location');
        });

        // Many-to-many: agreement ↔ fsm_templates
        Schema::create('fsm_agreement_template', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fsm_service_agreement_id');
            $table->unsignedBigInteger('fsm_template_id');

            $table->foreign('fsm_service_agreement_id')
                ->references('id')->on('fsm_service_agreements')->cascadeOnDelete();
            $table->foreign('fsm_template_id')
                ->references('id')->on('fsm_templates')->cascadeOnDelete();

            $table->unique(['fsm_service_agreement_id', 'fsm_template_id'], 'uniq_agreement_template');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_agreement_template');
        Schema::dropIfExists('fsm_agreement_location');
        Schema::dropIfExists('fsm_service_agreements');
    }
};
