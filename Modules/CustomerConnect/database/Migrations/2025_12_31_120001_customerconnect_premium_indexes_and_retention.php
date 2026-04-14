<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Threads
        if (Schema::hasTable('customerconnect_threads')) {
            Schema::table('customerconnect_threads', function (Blueprint $table) {
                if (!Schema::hasColumn('customerconnect_threads', 'archived_at')) {
                    $table->timestamp('archived_at')->nullable()->index();
                }
                // Useful indexes (guarded by index name collisions)
                // NOTE: Laravel doesn't expose hasIndex reliably across MySQL versions; we use try/catch.
                try { $table->index(['tenant_id', 'status'], 'cc_threads_tenant_status_idx'); } catch (Throwable $e) {}
                try { $table->index(['tenant_id', 'channel'], 'cc_threads_tenant_channel_idx'); } catch (Throwable $e) {}
                try { $table->index(['tenant_id', 'last_message_at'], 'cc_threads_tenant_lastmsg_idx'); } catch (Throwable $e) {}
                try { $table->index(['contact_id', 'channel'], 'cc_threads_contact_channel_idx'); } catch (Throwable $e) {}
            });
        }

        // Messages
        if (Schema::hasTable('customerconnect_messages')) {
            Schema::table('customerconnect_messages', function (Blueprint $table) {
                if (!Schema::hasColumn('customerconnect_messages', 'archived_at')) {
                    $table->timestamp('archived_at')->nullable()->index();
                }
                try { $table->index(['thread_id', 'created_at'], 'cc_msgs_thread_created_idx'); } catch (Throwable $e) {}
                try { $table->index(['tenant_id', 'direction'], 'cc_msgs_tenant_direction_idx'); } catch (Throwable $e) {}
                try { $table->index(['provider', 'provider_message_id'], 'cc_msgs_provider_mid_idx'); } catch (Throwable $e) {}
                try { $table->index(['status', 'created_at'], 'cc_msgs_status_created_idx'); } catch (Throwable $e) {}
            });
        }

        // Deliveries (campaign engine)
        if (Schema::hasTable('customerconnect_deliveries')) {
            Schema::table('customerconnect_deliveries', function (Blueprint $table) {
                if (!Schema::hasColumn('customerconnect_deliveries', 'archived_at')) {
                    $table->timestamp('archived_at')->nullable()->index();
                }
                try { $table->index(['campaign_id', 'status'], 'cc_deliv_campaign_status_idx'); } catch (Throwable $e) {}
                try { $table->index(['tenant_id', 'status'], 'cc_deliv_tenant_status_idx'); } catch (Throwable $e) {}
                if (Schema::hasColumn('customerconnect_deliveries', 'provider') && Schema::hasColumn('customerconnect_deliveries', 'provider_message_id')) {
                    try { $table->index(['provider', 'provider_message_id'], 'cc_deliv_provider_mid_idx'); } catch (Throwable $e) {}
                }
                if (Schema::hasColumn('customerconnect_deliveries', 'scheduled_for')) {
                    try { $table->index(['status', 'scheduled_for'], 'cc_deliv_status_sched_idx'); } catch (Throwable $e) {}
                }
            });
        }
    }

    public function down(): void
    {
        // No-op down: indexes are safe to keep; dropping may fail on unknown names across environments.
        // If you want full reversibility later, we can add conditional dropIndex() with try/catch.
    }
};
