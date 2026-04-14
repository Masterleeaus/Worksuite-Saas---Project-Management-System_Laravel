<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * titan_go_site_notes
 *
 * Persistent site memory: access codes, parking, equipment location,
 * hazards, customer preferences. Keyed to fsm_locations.id.
 * Read via GET /api/nexus/v1/jobs/{id}/site-memory
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('titan_go_site_notes')) {
            return;
        }
        
        Schema::create('titan_go_site_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->unsignedBigInteger('location_id');  // FK → fsm_locations.id
            $table->string('type');                     // entry|access_code|parking|equipment|preference|hazard|repeat
            $table->text('content');
            $table->timestamps();

            $table->index(['company_id', 'location_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_go_site_notes');
    }
};
