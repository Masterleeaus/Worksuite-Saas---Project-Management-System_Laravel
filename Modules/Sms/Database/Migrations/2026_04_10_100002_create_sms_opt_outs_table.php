<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_opt_outs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('phone_number', 30)->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamp('opted_out_at')->useCurrent();
            $table->timestamps();

            $table->unique(['company_id', 'phone_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_opt_outs');
    }
};
