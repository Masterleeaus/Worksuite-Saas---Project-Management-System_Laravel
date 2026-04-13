<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * titan_go_checklist_completions
 *
 * Records per-step completion for visit checklists.
 * Keyed by (visit_id, step_index) so a step can be re-completed
 * if needed (last completion wins).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titan_go_checklist_completions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->unsignedBigInteger('visit_id');      // FK → fsm_orders.id
            $table->unsignedInteger('worker_id');        // FK → users.id
            $table->unsignedSmallInteger('step_index');  // 0-based position in template.checklist
            $table->string('instruction')->nullable();   // snapshot of the step text at completion
            $table->text('photo_data')->nullable();      // base64 image (null if not required)
            $table->text('signature_data')->nullable();  // base64 PNG (null if not required)
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->useCurrent();
            $table->timestamps();

            $table->index(['company_id', 'visit_id']);
            $table->index(['visit_id', 'step_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_go_checklist_completions');
    }
};
