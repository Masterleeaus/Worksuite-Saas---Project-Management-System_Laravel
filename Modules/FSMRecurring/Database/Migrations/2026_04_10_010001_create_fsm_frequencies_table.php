<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Frequency Rules
        Schema::create('fsm_frequencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name');
            $table->boolean('active')->default(true);
            // Repeat every N units
            $table->unsignedInteger('interval')->default(1);
            // daily / weekly / monthly / yearly
            $table->string('interval_type', 20);
            // Exclusive rules exclude matching days
            $table->boolean('is_exclusive')->default(false);
            // By month-day
            $table->boolean('use_bymonthday')->default(false);
            $table->unsignedTinyInteger('month_day')->nullable();
            // By week day
            $table->boolean('use_byweekday')->default(false);
            $table->boolean('weekday_mo')->default(false);
            $table->boolean('weekday_tu')->default(false);
            $table->boolean('weekday_we')->default(false);
            $table->boolean('weekday_th')->default(false);
            $table->boolean('weekday_fr')->default(false);
            $table->boolean('weekday_sa')->default(false);
            $table->boolean('weekday_su')->default(false);
            // By month
            $table->boolean('use_bymonth')->default(false);
            $table->boolean('month_jan')->default(false);
            $table->boolean('month_feb')->default(false);
            $table->boolean('month_mar')->default(false);
            $table->boolean('month_apr')->default(false);
            $table->boolean('month_may')->default(false);
            $table->boolean('month_jun')->default(false);
            $table->boolean('month_jul')->default(false);
            $table->boolean('month_aug')->default(false);
            $table->boolean('month_sep')->default(false);
            $table->boolean('month_oct')->default(false);
            $table->boolean('month_nov')->default(false);
            $table->boolean('month_dec')->default(false);
            // By set position
            $table->boolean('use_setpos')->default(false);
            $table->integer('set_pos')->nullable();
            $table->timestamps();
        });

        // Frequency Sets (collections of frequency rules)
        Schema::create('fsm_frequency_sets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->unsignedInteger('schedule_days')->default(30)->comment('Days ahead to schedule orders');
            $table->unsignedInteger('buffer_early')->default(0)->comment('Days before scheduled date allowed');
            $table->unsignedInteger('buffer_late')->default(0)->comment('Days after scheduled date allowed');
            $table->timestamps();
        });

        // Pivot: frequency_set ↔ frequencies
        Schema::create('fsm_frequency_set_rule', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('frequency_set_id');
            $table->unsignedBigInteger('frequency_id');

            $table->foreign('frequency_set_id')->references('id')->on('fsm_frequency_sets')->cascadeOnDelete();
            $table->foreign('frequency_id')->references('id')->on('fsm_frequencies')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_frequency_set_rule');
        Schema::dropIfExists('fsm_frequency_sets');
        Schema::dropIfExists('fsm_frequencies');
    }
};
