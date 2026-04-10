<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('evidence_vault_submissions')) {
            Schema::create('evidence_vault_submissions', function (Blueprint $table) {
                $table->id();

                // Tenant scoping
                $table->unsignedInteger('company_id')->nullable()->index();
                $table->foreign('company_id')
                    ->references('id')->on('companies')
                    ->onDelete('set null')->onUpdate('cascade');

                // The job/task being completed.  Kept as a plain integer so the
                // module has no hard dependency on a specific Jobs module.
                $table->unsignedBigInteger('job_id')->nullable()->index();
                $table->string('job_reference')->nullable();

                // The cleaner/field worker who submitted the evidence.
                $table->unsignedInteger('submitted_by')->nullable();
                $table->foreign('submitted_by')
                    ->references('id')->on('users')
                    ->onDelete('set null')->onUpdate('cascade');

                // Digital signature stored as a base64 data-URI (PNG).
                // NULL means no signature was captured for this submission.
                $table->longText('signature_data')->nullable();

                // Whether the client signed off (as opposed to using a locked-site photo).
                $table->boolean('client_signed')->default(false);

                // Free-text note from the cleaner at completion.
                $table->text('notes')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('evidence_vault_submissions');
    }
};
