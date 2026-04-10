<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Affiliate\Enums\PaymentStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // If the table already exists, do nothing so this migration can be marked as "ran"
        if (Schema::hasTable('affiliate_payouts')) {
            return;
        }

        Schema::create('affiliate_payouts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedInteger('affiliate_id');
            $table->decimal('balance', 30, 2)->default(0);
            $table->decimal('amount_requested', 30, 2)->default(0);

            $table->string('status')
                ->nullable()
                ->default(PaymentStatus::Pending->value);

            $table->string('payment_method');
            $table->string('other_payment_method')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('memo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the correct table name
        Schema::dropIfExists('affiliate_payouts');
    }
};