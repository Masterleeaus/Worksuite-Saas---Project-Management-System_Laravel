<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('performance_meeting_agenda')) {
            return;
        }
        Schema::table('performance_meeting_agenda', function (Blueprint $table) {
            if (! Schema::hasColumn('performance_meeting_agenda', 'keep_private')) {
                $table->enum('keep_private', ['yes', 'no'])->default('no')->after('is_discussed')->nullable();
            }
        });

        if (! Schema::hasTable('key_results')) {
            return;
        }
        
        Schema::table('key_results', function (Blueprint $table) {
            if (! Schema::hasColumn('key_results', 'next_check_in')) {
                $table->date('next_check_in')->nullable()->after('last_check_in');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('performance_meeting_agenda')) {
            return;
        }
        
        Schema::table('performance_meeting_agenda', function (Blueprint $table) {
            $table->dropColumn('keep_private');
        });

        if (! Schema::hasTable('key_results')) {
            return;
        }
        
        Schema::table('key_results', function (Blueprint $table) {
            if (! Schema::hasColumn('key_results', 'next_check_in')) {
                $table->date('next_check_in')->nullable()->after('last_check_in');
            }
        });
    }

};
