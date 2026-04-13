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
        if (Schema::hasTable('salary_payment_methods')) {
            return;
        }
        Schema::create('salary_payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payment_method');
            $table->boolean('default');
            $table->timestamps();
        });

        Schema::table('salary_slips', function (Blueprint $table) {
            if (! Schema::hasColumn('salary_slips', 'salary_json')) {
                $table->text('salary_json')->nullable();
            }
            if (! Schema::hasColumn('salary_slips', 'extra_json')) {
                $table->text('extra_json')->nullable();
            }
        if (! Schema::hasTable('salary_slips')) {
            return;
        }
            if (! Schema::hasColumn('salary_slips', 'expense_claims')) {
                $table->string('expense_claims')->default('0')->nullable();
            }
            if (! Schema::hasColumn('salary_slips', 'pay_days')) {
                $table->integer('pay_days')->nullable();
            }

            if (! Schema::hasColumn('salary_slips', 'salary_payment_method_id')) {
                $table->unsignedBigInteger('salary_payment_method_id')->nullable();
            }
            $table->foreign('salary_payment_method_id')->references('id')->on('salary_payment_methods')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_slips', function (Blueprint $table) {
            $table->dropColumn(['salary_json']);
            $table->dropColumn(['extra_json']);
            $table->dropColumn(['expense_claims']);
            $table->dropColumn(['pay_days']);

            $table->dropForeign(['salary_payment_method_id']);
            $table->dropColumn(['salary_payment_method_id']);
        });

        Schema::dropIfExists('salary_payment_methods');
    }
};
