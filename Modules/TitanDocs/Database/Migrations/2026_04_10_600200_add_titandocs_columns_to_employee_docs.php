<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('employee_docs')) {
            return;
        }
        Schema::table('employee_docs', function (Blueprint $table) {
            if (!Schema::hasColumn('employee_docs', 'template_id')) {
                $table->unsignedBigInteger('template_id')->nullable();
            }
            if (!Schema::hasColumn('employee_docs', 'document_type')) {
                $table->string('document_type')->default('generic');
            }
            if (!Schema::hasColumn('employee_docs', 'generated_by')) {
                $table->string('generated_by')->default('manual');
            }
            if (!Schema::hasColumn('employee_docs', 'merge_fields')) {
                $table->json('merge_fields')->nullable();
            }
            if (!Schema::hasColumn('employee_docs', 'requires_signature')) {
                $table->boolean('requires_signature')->default(false);
            }
            if (!Schema::hasColumn('employee_docs', 'signing_status')) {
                $table->string('signing_status')->nullable();
            }
            if (!Schema::hasColumn('employee_docs', 'signed_at')) {
                $table->timestamp('signed_at')->nullable();
            }
            if (!Schema::hasColumn('employee_docs', 'employee_acknowledged')) {
                $table->boolean('employee_acknowledged')->default(false);
            }
            if (!Schema::hasColumn('employee_docs', 'acknowledged_at')) {
                $table->timestamp('acknowledged_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('employee_docs')) {
            return;
        }
        Schema::table('employee_docs', function (Blueprint $table) {
            $columns = ['template_id','document_type','generated_by','merge_fields','requires_signature',
                        'signing_status','signed_at','employee_acknowledged','acknowledged_at'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('employee_docs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
