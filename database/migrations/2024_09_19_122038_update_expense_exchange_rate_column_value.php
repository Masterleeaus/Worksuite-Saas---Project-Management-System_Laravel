<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        if (Schema::hasTable('expenses') && Schema::hasColumn('expenses', 'user_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->unsignedInteger('user_id')->nullable()->change();
            });
        }

        if (
            DB::connection()->getDriverName() !== 'sqlite'
            && Schema::hasTable('expenses')
            && Schema::hasTable('currencies')
            && Schema::hasColumn('expenses', 'currency_id')
            && Schema::hasColumn('expenses', 'exchange_rate')
            && Schema::hasColumn('currencies', 'id')
            && Schema::hasColumn('currencies', 'exchange_rate')
        ) {
            DB::table('expenses')
                ->join('currencies', 'expenses.currency_id', '=', 'currencies.id')
                ->update(['expenses.exchange_rate' => DB::raw('currencies.exchange_rate')]);
        }

        if (Schema::hasTable('global_settings') && !Schema::hasColumn('global_settings', 'dedicated_subdomain')) {
            Schema::table('global_settings', function (Blueprint $table) {
                $table->string('dedicated_subdomain')->nullable()->after('currency_key_version'); // Adjust the 'after' field as needed
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable(false)->change();
        });

        DB::table('expenses')->update(['exchange_rate' => NULL]);

        Schema::table('global_settings', function (Blueprint $table) {
            $table->dropColumn('dedicated_subdomain');
        });
    }
};
