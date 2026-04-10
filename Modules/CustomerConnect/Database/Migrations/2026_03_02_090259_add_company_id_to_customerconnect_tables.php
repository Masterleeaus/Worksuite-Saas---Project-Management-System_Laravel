<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_alerts') && !Schema::hasColumn('customerconnect_alerts', 'company_id')) {
            Schema::table('customerconnect_alerts', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_assignment_cursors') && !Schema::hasColumn('customerconnect_assignment_cursors', 'company_id')) {
            Schema::table('customerconnect_assignment_cursors', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_audience_members') && !Schema::hasColumn('customerconnect_audience_members', 'company_id')) {
            Schema::table('customerconnect_audience_members', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_audiences') && !Schema::hasColumn('customerconnect_audiences', 'company_id')) {
            Schema::table('customerconnect_audiences', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_campaign_runs') && !Schema::hasColumn('customerconnect_campaign_runs', 'company_id')) {
            Schema::table('customerconnect_campaign_runs', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_campaign_steps') && !Schema::hasColumn('customerconnect_campaign_steps', 'company_id')) {
            Schema::table('customerconnect_campaign_steps', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_campaigns') && !Schema::hasColumn('customerconnect_campaigns', 'company_id')) {
            Schema::table('customerconnect_campaigns', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_channel_identities') && !Schema::hasColumn('customerconnect_channel_identities', 'company_id')) {
            Schema::table('customerconnect_channel_identities', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_contact_verifications') && !Schema::hasColumn('customerconnect_contact_verifications', 'company_id')) {
            Schema::table('customerconnect_contact_verifications', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_contacts') && !Schema::hasColumn('customerconnect_contacts', 'company_id')) {
            Schema::table('customerconnect_contacts', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_deliveries') && !Schema::hasColumn('customerconnect_deliveries', 'company_id')) {
            Schema::table('customerconnect_deliveries', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_delivery_events') && !Schema::hasColumn('customerconnect_delivery_events', 'company_id')) {
            Schema::table('customerconnect_delivery_events', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_message_events') && !Schema::hasColumn('customerconnect_message_events', 'company_id')) {
            Schema::table('customerconnect_message_events', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_messages') && !Schema::hasColumn('customerconnect_messages', 'company_id')) {
            Schema::table('customerconnect_messages', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_saved_filters') && !Schema::hasColumn('customerconnect_saved_filters', 'company_id')) {
            Schema::table('customerconnect_saved_filters', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_suppressions') && !Schema::hasColumn('customerconnect_suppressions', 'company_id')) {
            Schema::table('customerconnect_suppressions', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_tags') && !Schema::hasColumn('customerconnect_tags', 'company_id')) {
            Schema::table('customerconnect_tags', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_thread_reads') && !Schema::hasColumn('customerconnect_thread_reads', 'company_id')) {
            Schema::table('customerconnect_thread_reads', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_thread_tags') && !Schema::hasColumn('customerconnect_thread_tags', 'company_id')) {
            Schema::table('customerconnect_thread_tags', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_threads') && !Schema::hasColumn('customerconnect_threads', 'company_id')) {
            Schema::table('customerconnect_threads', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('customerconnect_unsubscribes') && !Schema::hasColumn('customerconnect_unsubscribes', 'company_id')) {
            Schema::table('customerconnect_unsubscribes', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('newsletter_module') && !Schema::hasColumn('newsletter_module', 'company_id')) {
            Schema::table('newsletter_module', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('newsletters') && !Schema::hasColumn('newsletters', 'company_id')) {
            Schema::table('newsletters', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        // intentionally non-destructive
    }
};
