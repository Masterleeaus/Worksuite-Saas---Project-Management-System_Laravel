<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_tenants', function (Blueprint $table) {
            $table->id('ID');
            $table->string('business_name');
            $table->string('owner_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->default('USA');
            $table->enum('plan_type', ['starter', 'professional', 'enterprise'])->default('starter');
            $table->enum('status', ['active', 'inactive', 'trial', 'suspended'])->default('trial');
            $table->timestamp('trial_ends_at')->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_tenants');
    }
};
