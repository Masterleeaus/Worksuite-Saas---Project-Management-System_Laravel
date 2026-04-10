<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Contacts
        if (Schema::hasTable('customerconnect_contacts')) {
        Schema::table('customerconnect_contacts', function (Blueprint $table) {
                    if (!Schema::hasColumn('customerconnect_contacts', 'user_id')) {
                        $table->unsignedBigInteger('user_id')->nullable()->index();
                    }
                });
        
                
    }

// Threads
        if (Schema::hasTable('customerconnect_threads')) {
        Schema::table('customerconnect_threads', function (Blueprint $table) {
                    if (!Schema::hasColumn('customerconnect_threads', 'user_id')) {
                        $table->unsignedBigInteger('user_id')->nullable()->index();
                    }
                });
        
                
    }

// Messages
        if (Schema::hasTable('customerconnect_messages')) {
        Schema::table('customerconnect_messages', function (Blueprint $table) {
                    if (!Schema::hasColumn('customerconnect_messages', 'user_id')) {
                        $table->unsignedBigInteger('user_id')->nullable()->index();
                    }
                    if (!Schema::hasColumn('customerconnect_messages', 'audit_meta')) {
                        $table->json('audit_meta')->nullable();
                    }
                });
        
                
    }

// Deliveries
        if (Schema::hasTable('customerconnect_deliveries')) {
        Schema::table('customerconnect_deliveries', function (Blueprint $table) {
                    if (!Schema::hasColumn('customerconnect_deliveries', 'user_id')) {
                        $table->unsignedBigInteger('user_id')->nullable()->index();
                    }
                    if (!Schema::hasColumn('customerconnect_deliveries', 'audit_meta')) {
                        $table->json('audit_meta')->nullable();
                    }
                });
        
                
    }

// Campaigns
        if (Schema::hasTable('customerconnect_campaigns')) {
        Schema::table('customerconnect_campaigns', function (Blueprint $table) {
                    if (!Schema::hasColumn('customerconnect_campaigns', 'user_id')) {
                        $table->unsignedBigInteger('user_id')->nullable()->index();
                    }
                });
        
                
    }

// Campaign runs
        if (Schema::hasTable('customerconnect_campaign_runs')) {
        Schema::table('customerconnect_campaign_runs', function (Blueprint $table) {
                    if (!Schema::hasColumn('customerconnect_campaign_runs', 'user_id')) {
                        $table->unsignedBigInteger('user_id')->nullable()->index();
                    }
                });
        
                
    }

// Audiences
        if (Schema::hasTable('customerconnect_audiences')) {
            Schema::table('customerconnect_audiences', function (Blueprint $table) {
                if (!Schema::hasColumn('customerconnect_audiences', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->index();
                }
            });
        }

        // Audience members
        if (Schema::hasTable('customerconnect_audience_members')) {
            Schema::table('customerconnect_audience_members', function (Blueprint $table) {
                if (!Schema::hasColumn('customerconnect_audience_members', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->index();
                }
            });
        }

        // Channel identities
        if (Schema::hasTable('customerconnect_channel_identities')) {
        Schema::table('customerconnect_channel_identities', function (Blueprint $table) {
                    if (!Schema::hasColumn('customerconnect_channel_identities', 'user_id')) {
                        $table->unsignedBigInteger('user_id')->nullable()->index();
                    }
                });
        
                
    }

// Thread reads already has user_id, but ensure company_id exists (safety)
        if (Schema::hasTable('customerconnect_thread_reads')) {
        Schema::table('customerconnect_thread_reads', function (Blueprint $table) {
                    if (!Schema::hasColumn('customerconnect_thread_reads', 'company_id')) {
                        $table->unsignedBigInteger('company_id')->nullable()->index();
                    }
                });
            
    }

}

    public function down(): void
    {
        // Safe down migrations (drop columns if present)
        $drop = function (string $tableName, array $columns) {
            if (!Schema::hasTable($tableName)) return;
            Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                foreach ($columns as $col) {
                    if (Schema::hasColumn($tableName, $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        };

        $drop('customerconnect_contacts', ['user_id']);
        $drop('customerconnect_threads', ['user_id']);
        $drop('customerconnect_messages', ['user_id', 'audit_meta']);
        $drop('customerconnect_deliveries', ['user_id', 'audit_meta']);
        $drop('customerconnect_campaigns', ['user_id']);
        $drop('customerconnect_campaign_runs', ['user_id']);
        $drop('customerconnect_audiences', ['user_id']);
        $drop('customerconnect_audience_members', ['user_id']);
        $drop('customerconnect_channel_identities', ['user_id']);
    }
};
