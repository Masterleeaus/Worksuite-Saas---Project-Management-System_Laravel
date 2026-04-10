<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cleaner_rate_configs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->nullable()->comment('null = global default');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('contract_ref')->nullable()->comment('Optional per-contract override key');
            $table->decimal('base_rate', 10, 4)->default(0)->comment('Standard weekday hourly rate');
            $table->decimal('night_rate_multiplier', 8, 4)->default(1.25);
            $table->time('night_rate_cutoff')->default('22:00:00')->comment('Jobs starting at/after this time use night rate');
            $table->decimal('saturday_multiplier', 8, 4)->default(1.25);
            $table->decimal('sunday_multiplier', 8, 4)->default(1.50);
            $table->decimal('public_holiday_multiplier', 8, 4)->default(2.25);
            $table->decimal('public_holiday_fixed_rate', 10, 4)->nullable()->comment('If set, overrides multiplier for public holidays');
            $table->decimal('commission_per_room', 10, 4)->default(0)->comment('Flat $ per cleaned room/unit');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cleaner_rate_configs');
    }
};
