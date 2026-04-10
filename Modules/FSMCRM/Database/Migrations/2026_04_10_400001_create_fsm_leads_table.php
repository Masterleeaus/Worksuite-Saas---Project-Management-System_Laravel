<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_leads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();

            // Core CRM fields
            $table->string('name', 255);                           // Lead/Opportunity title
            $table->string('contact_name', 128)->nullable();       // Contact person
            $table->string('email', 128)->nullable();
            $table->string('phone', 64)->nullable();
            $table->unsignedBigInteger('partner_id')->nullable()->index(); // Client (user/contact)
            $table->text('notes')->nullable();                     // Description / requirements
            $table->string('stage', 32)->default('new');           // new|qualified|won|lost
            $table->decimal('expected_revenue', 14, 2)->default(0);
            $table->date('close_date')->nullable();                // Expected close / agreed start date

            // FSM-specific fields added by this module
            $table->unsignedBigInteger('fsm_location_id')->nullable()->index(); // Linked FSM location
            $table->unsignedBigInteger('service_type_id')->nullable()->index(); // FK → fsm_templates
            $table->unsignedInteger('site_count')->default(1);     // Number of sites
            $table->decimal('estimated_hours', 8, 2)->nullable();  // Quoted hours per visit
            $table->boolean('create_recurring')->default(false);   // Quote-to-recurring flag

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fsm_location_id')->references('id')->on('fsm_locations')->nullOnDelete();
            $table->foreign('service_type_id')->references('id')->on('fsm_templates')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_leads');
    }
};
