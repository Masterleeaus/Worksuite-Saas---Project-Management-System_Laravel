<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Campaigns: type + settings
        if (Schema::hasTable('customerconnect_campaigns')) {
        Schema::table('customerconnect_campaigns', function (Blueprint $table) {
                    if (!Schema::hasColumn('customerconnect_campaigns', 'campaign_type')) {
                        $table->string('campaign_type')->default('broadcast')->index();
                    }
                    if (!Schema::hasColumn('customerconnect_campaigns', 'settings')) {
                        $table->json('settings')->nullable();
                    }
                });
        
                
    }

// Deliveries: align schema with runtime fields used by SendDelivery
        if (Schema::hasTable('customerconnect_deliveries')) {
        Schema::table('customerconnect_deliveries', function (Blueprint $table) {
                    if (!Schema::hasColumn('customerconnect_deliveries', 'campaign_id')) {
                        $table->unsignedBigInteger('campaign_id')->nullable()->index();
                    }
                    if (!Schema::hasColumn('customerconnect_deliveries', 'contact_id')) {
                        $table->unsignedBigInteger('contact_id')->nullable()->index();
                    }
                    if (!Schema::hasColumn('customerconnect_deliveries', 'email')) {
                        $table->string('email')->nullable()->index();
                    }
                    if (!Schema::hasColumn('customerconnect_deliveries', 'phone')) {
                        $table->string('phone')->nullable()->index();
                    }
                    if (!Schema::hasColumn('customerconnect_deliveries', 'telegram_user_id')) {
                        $table->string('telegram_user_id')->nullable()->index();
                    }
                    if (!Schema::hasColumn('customerconnect_deliveries', 'to_address')) {
                        $table->string('to_address')->nullable();
                    }
                    if (!Schema::hasColumn('customerconnect_deliveries', 'meta')) {
                        $table->json('meta')->nullable();
                    }
                });
            
    }

}

    public function down(): void
    {
        // Keep down minimal and safe across environments.
    }
};
