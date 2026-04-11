<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// fieldservice_size: sizes for locations and orders (e.g. "3-bed house", "pool 10m²")
return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('fsm_sizes')) {
            Schema::create('fsm_sizes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('name');
                $table->string('unit_of_measure')->nullable()
                      ->comment('e.g. m², sqft, rooms');
                $table->unsignedBigInteger('type_id')->nullable()
                      ->comment('fsm_order_type');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->boolean('is_order_size')->default(false)
                      ->comment('appears as selectable size on order');
                $table->boolean('active')->default(true);
                $table->timestamps();

                $table->foreign('parent_id')->references('id')
                      ->on('fsm_sizes')->nullOnDelete();
            });
        }

        // Location-size join table (location can have multiple sizes)
        if (! Schema::hasTable('fsm_location_sizes')) {
            Schema::create('fsm_location_sizes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('location_id');
                $table->unsignedBigInteger('size_id');
                $table->decimal('quantity', 10, 2)->default(1);
                $table->timestamps();

                if (Schema::hasTable('fsm_locations')) {
                    $table->foreign('location_id')->references('id')
                          ->on('fsm_locations')->cascadeOnDelete();
                }
                $table->foreign('size_id')->references('id')
                      ->on('fsm_sizes')->cascadeOnDelete();
            });
        }

        // Add size fields to fsm_orders
        if (Schema::hasTable('fsm_orders')) {
            Schema::table('fsm_orders', function (Blueprint $table) {
                if (! Schema::hasColumn('fsm_orders', 'size_id')) {
                    $table->unsignedBigInteger('size_id')->nullable()->after('type_id');
                }
                if (! Schema::hasColumn('fsm_orders', 'size_value')) {
                    $table->decimal('size_value', 10, 2)->nullable()->after('size_id');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_location_sizes');
        Schema::dropIfExists('fsm_sizes');

        if (Schema::hasTable('fsm_orders')) {
            Schema::table('fsm_orders', function (Blueprint $table) {
                if (Schema::hasColumn('fsm_orders', 'size_id')) $table->dropColumn('size_id');
                if (Schema::hasColumn('fsm_orders', 'size_value')) $table->dropColumn('size_value');
            });
        }
    }
};
