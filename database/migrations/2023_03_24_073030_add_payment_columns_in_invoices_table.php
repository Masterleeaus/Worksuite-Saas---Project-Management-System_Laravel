<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('invoices')) {
            return;
        }

        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'payment_status')) {
                $table->enum('payment_status', [1, 0])->default(0)->after('custom_invoice_number');
            }

            if (!Schema::hasColumn('invoices', 'offline_method_id')) {
                $table->unsignedInteger('offline_method_id')->nullable()->after('payment_status');

                if (Schema::hasTable('offline_payment_methods')) {
                    $table->foreign(['offline_method_id'])->references(['id'])->on('offline_payment_methods')->onUpdate('CASCADE')->onDelete('SET NULL');
                }
            }

            if (!Schema::hasColumn('invoices', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->unique()->after('offline_method_id');
            }

            if (!Schema::hasColumn('invoices', 'gateway')) {
                $table->string('gateway')->nullable()->after('transaction_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('invoices')) {
            return;
        }

        Schema::table('invoices', function (Blueprint $table) {
            $columns = [];

            foreach (['payment_status', 'offline_method_id', 'transaction_id', 'gateway'] as $column) {
                if (Schema::hasColumn('invoices', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                if (in_array('offline_method_id', $columns, true)) {
                    try {
                        $table->dropForeign(['offline_method_id']);
                    }
                    catch (\Throwable $e) {
                        // no-op: foreign key may not exist in drifted schemas
                    }
                }

                $table->dropColumn($columns);
            }
        });
    }

};
