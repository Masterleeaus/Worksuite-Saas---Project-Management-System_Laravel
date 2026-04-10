<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_subscriptions', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('tenant_id');
            $table->string('stripe_subscription_id')->nullable();
            $table->string('stripe_price_id')->nullable();
            $table->string('plan_name');
            $table->decimal('monthly_amount', 10, 2)->default(0);
            $table->enum('status', ['active', 'canceled', 'past_due', 'trialing', 'paused'])->default('trialing');
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');
            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('ID')->on('tbl_tenants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_subscriptions');
    }
};
