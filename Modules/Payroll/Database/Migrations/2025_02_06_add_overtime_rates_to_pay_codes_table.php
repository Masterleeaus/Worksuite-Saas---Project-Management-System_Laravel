<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('pay_codes', 'regular_fixed_amount')) {
            if (! Schema::hasTable('pay_codes')) {
                return;
            }
            
            Schema::table('pay_codes', function (Blueprint $table) {
                // Regular rate
                if (! Schema::hasColumn('pay_codes', 'regular_fixed_amount')) {
                    $table->decimal('regular_fixed_amount', 8, 2)->default(0)->nullable();
                }
                if (! Schema::hasColumn('pay_codes', 'regular_time_rate')) {
                    $table->decimal('regular_time_rate', 8, 2)->default(1.0)->nullable();
                }

                // Weekend rate
                if (! Schema::hasColumn('pay_codes', 'weekend_fixed_amount')) {
                    $table->decimal('weekend_fixed_amount', 8, 2)->default(0)->nullable();
                }
                if (! Schema::hasColumn('pay_codes', 'weekend_time_rate')) {
                    $table->decimal('weekend_time_rate', 8, 2)->default(1.5)->nullable();
                }

                // Holiday rate
                if (! Schema::hasColumn('pay_codes', 'holiday_fixed_amount')) {
                    $table->decimal('holiday_fixed_amount', 8, 2)->default(0)->nullable();
                }
                if (! Schema::hasColumn('pay_codes', 'holiday_time_rate')) {
                    $table->decimal('holiday_time_rate', 8, 2)->default(2.0)->nullable();
                }

                // Day off rate
                if (! Schema::hasColumn('pay_codes', 'day_off_fixed_amount')) {
                    $table->decimal('day_off_fixed_amount', 8, 2)->default(0)->nullable();
                }
                if (! Schema::hasColumn('pay_codes', 'day_off_time_rate')) {
                    $table->decimal('day_off_time_rate', 8, 2)->default(1.75)->nullable();
                }
            });
        }

        if (!Schema::hasColumn('overtime_requests', 'type')) {
            if (! Schema::hasTable('overtime_requests')) {
                return;
            }
            
            Schema::table('overtime_requests', function (Blueprint $table) {
                // Type
                if (! Schema::hasColumn('overtime_requests', 'type')) {
                    $table->enum('type', ['working', 'holiday', 'dayoff'])->default('working')->nullable();
                }
            });
        }
    }

    public function down()
    {
        if (! Schema::hasTable('pay_codes')) {
            return;
        }
        
        Schema::table('pay_codes', function (Blueprint $table) {
            $table->dropColumn([
                'regular_type',
                'regular_fixed_amount',
                'regular_time_rate',
                'weekend_type',
                'weekend_fixed_amount',
                'weekend_time_rate',
                'holiday_type',
                'holiday_fixed_amount',
                'holiday_time_rate',
                'day_off_type',
                'day_off_fixed_amount',
                'day_off_time_rate'
            ]);
        });
    }
};
