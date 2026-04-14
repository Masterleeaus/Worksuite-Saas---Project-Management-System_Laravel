<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('customerconnect_contacts')) return;
        if (Schema::hasTable('customerconnect_contact_verifications')) return;

        Schema::create('customerconnect_contact_verifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('contact_id')->index();
            $table->string('channel', 20); // sms|whatsapp|telegram|email
            $table->string('otp_code', 255);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['contact_id', 'channel']);
        });

        Schema::table('customerconnect_contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('customerconnect_contacts', 'sms_verified_at')) {
                $table->timestamp('sms_verified_at')->nullable();
            }
            if (!Schema::hasColumn('customerconnect_contacts', 'whatsapp_verified_at')) {
                $table->timestamp('whatsapp_verified_at')->nullable();
            }
            if (!Schema::hasColumn('customerconnect_contacts', 'telegram_verified_at')) {
                $table->timestamp('telegram_verified_at')->nullable();
            }
            if (!Schema::hasColumn('customerconnect_contacts', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('customerconnect_contacts')) {
            Schema::table('customerconnect_contacts', function (Blueprint $table) {
                foreach (['sms_verified_at','whatsapp_verified_at','telegram_verified_at','email_verified_at'] as $col) {
                    if (Schema::hasColumn('customerconnect_contacts', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
        Schema::dropIfExists('customerconnect_contact_verifications');
    }
};
