<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('webhooks_logs') || Schema::hasColumn('webhooks_logs', 'company_id')) {
            return;
        }

        $afterColumn = Schema::hasColumn('webhooks_logs', 'id') ? 'id' : null;

        Schema::table('webhooks_logs', function (Blueprint $table) use ($afterColumn) {
            $column = $table->unsignedInteger('company_id')->nullable();
            if ($afterColumn) {
                $column->after($afterColumn);
            }
        });

        if (Schema::hasTable('companies')) {
            try {
                Schema::table('webhooks_logs', function (Blueprint $table) {
                    $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete()->cascadeOnUpdate();
                });
            } catch (\Throwable $exception) {
                logger($exception->getMessage());
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('webhooks_logs') || ! Schema::hasColumn('webhooks_logs', 'company_id')) {
            return;
        }

        try {
            Schema::table('webhooks_logs', function (Blueprint $table) {
                $table->dropForeign(['company_id']);
            });
        } catch (\Throwable $exception) {
            logger($exception->getMessage());
        }

        Schema::table('webhooks_logs', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
};
