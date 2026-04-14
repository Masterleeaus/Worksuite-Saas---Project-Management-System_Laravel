<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DiscountTableColModify extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('discounts')) {
            return;
        }

        $afterColumn = Schema::hasColumn('discounts', 'id') ? 'id' : null;

        Schema::table('discounts', function (Blueprint $table) use ($afterColumn) {
            if (! Schema::hasColumn('discounts', 'discount_title')) {
                $column = $table->string('discount_title', 191)->nullable();
                if ($afterColumn) {
                    $column->after($afterColumn);
                }
            }

            if (Schema::hasColumn('discounts', 'zone_id')) {
                $table->dropColumn('zone_id');
            }
        });
    }

    public function down()
    {
        if (! Schema::hasTable('discounts')) {
            return;
        }

        Schema::table('discounts', function (Blueprint $table) {
            if (! Schema::hasColumn('discounts', 'zone_id')) {
                $table->unsignedBigInteger('zone_id')->nullable();
            }

            if (Schema::hasColumn('discounts', 'discount_title')) {
                $table->dropColumn('discount_title');
            }
        });
    }
}
