<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('client_docs')) {
            return;
        }
        Schema::table('client_docs', function (Blueprint $table) {
            if (!Schema::hasColumn('client_docs', 'template_id')) {
                $table->unsignedBigInteger('template_id')->nullable();
            }
            if (!Schema::hasColumn('client_docs', 'document_type')) {
                $table->string('document_type')->default('generic');
            }
            if (!Schema::hasColumn('client_docs', 'generated_by')) {
                $table->string('generated_by')->default('manual');
            }
            if (!Schema::hasColumn('client_docs', 'merge_fields')) {
                $table->json('merge_fields')->nullable();
            }
            if (!Schema::hasColumn('client_docs', 'signing_status')) {
                $table->string('signing_status')->nullable();
            }
            if (!Schema::hasColumn('client_docs', 'signing_provider')) {
                $table->string('signing_provider')->nullable();
            }
            if (!Schema::hasColumn('client_docs', 'external_doc_id')) {
                $table->string('external_doc_id')->nullable();
            }
            if (!Schema::hasColumn('client_docs', 'signed_at')) {
                $table->timestamp('signed_at')->nullable();
            }
            if (!Schema::hasColumn('client_docs', 'signed_by')) {
                $table->string('signed_by')->nullable();
            }
            if (!Schema::hasColumn('client_docs', 'is_versioned')) {
                $table->boolean('is_versioned')->default(false);
            }
            if (!Schema::hasColumn('client_docs', 'version_number')) {
                $table->integer('version_number')->default(1);
            }
            if (!Schema::hasColumn('client_docs', 'parent_doc_id')) {
                $table->unsignedBigInteger('parent_doc_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('client_docs')) {
            return;
        }
        Schema::table('client_docs', function (Blueprint $table) {
            $columns = ['template_id','document_type','generated_by','merge_fields','signing_status',
                        'signing_provider','external_doc_id','signed_at','signed_by','is_versioned',
                        'version_number','parent_doc_id'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('client_docs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
