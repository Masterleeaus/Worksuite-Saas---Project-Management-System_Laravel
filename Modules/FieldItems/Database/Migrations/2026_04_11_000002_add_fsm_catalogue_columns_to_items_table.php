<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add FSM-specific catalogue columns to the items table.
     * Columns: sku, base_price, stock_qty, is_active, item_type, barcode,
     *          low_stock_threshold, is_hazardous, is_eco_friendly, sds_file_path
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'sku')) {
                $table->string('sku', 100)->nullable()->unique()->after('name');
            }

            if (!Schema::hasColumn('items', 'base_price')) {
                $table->decimal('base_price', 12, 2)->nullable()->after('price');
            }

            if (!Schema::hasColumn('items', 'stock_qty')) {
                $table->decimal('stock_qty', 10, 2)->default(0)->after('base_price');
            }

            if (!Schema::hasColumn('items', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('stock_qty');
            }

            if (!Schema::hasColumn('items', 'item_type')) {
                // FSM item types: chemical, equipment, consumable, ppe, tool
                $table->enum('item_type', ['chemical', 'equipment', 'consumable', 'ppe', 'tool', 'other'])
                    ->default('other')
                    ->after('is_active');
            }

            if (!Schema::hasColumn('items', 'barcode')) {
                $table->string('barcode', 100)->nullable()->after('item_type');
            }

            if (!Schema::hasColumn('items', 'low_stock_threshold')) {
                $table->decimal('low_stock_threshold', 10, 2)->nullable()->after('barcode');
            }

            if (!Schema::hasColumn('items', 'is_hazardous')) {
                $table->boolean('is_hazardous')->default(false)->after('low_stock_threshold');
            }

            if (!Schema::hasColumn('items', 'is_eco_friendly')) {
                $table->boolean('is_eco_friendly')->default(false)->after('is_hazardous');
            }

            if (!Schema::hasColumn('items', 'sds_file_path')) {
                // Safety Data Sheet file path for hazardous materials
                $table->string('sds_file_path', 500)->nullable()->after('is_eco_friendly');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $cols = [
                'sds_file_path', 'is_eco_friendly', 'is_hazardous', 'low_stock_threshold',
                'barcode', 'item_type', 'is_active', 'stock_qty', 'base_price', 'sku',
            ];

            foreach ($cols as $col) {
                if (Schema::hasColumn('items', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
