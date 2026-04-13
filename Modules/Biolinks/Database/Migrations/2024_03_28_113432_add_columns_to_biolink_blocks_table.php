<?php

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Biolinks\Entities\BiolinksGlobalSetting;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('biolink_blocks')) {
            return;
        }
        Schema::table('biolink_blocks', function (Blueprint $table) {
            if (! Schema::hasColumn('biolink_blocks', 'placeholder')) {
                $table->string('placeholder')->nullable();
            }
            if (! Schema::hasColumn('biolink_blocks', 'name_placeholder')) {
                $table->string('name_placeholder')->nullable();
            }
            if (! Schema::hasColumn('biolink_blocks', 'button_text')) {
                $table->string('button_text')->nullable();
            }
            if (! Schema::hasColumn('biolink_blocks', 'thank_you_message')) {
                $table->string('thank_you_message')->nullable();
            }
            if (! Schema::hasColumn('biolink_blocks', 'thank_you_url')) {
                $table->string('thank_you_url')->nullable();
            }
            if (! Schema::hasColumn('biolink_blocks', 'show_agreement')) {
                $table->boolean('show_agreement')->default(false)->nullable();
            }
            if (! Schema::hasColumn('biolink_blocks', 'agreement_text')) {
                $table->string('agreement_text')->nullable();
            }
            if (! Schema::hasColumn('biolink_blocks', 'agreement_url')) {
                $table->string('agreement_url')->nullable();
            }
            if (! Schema::hasColumn('biolink_blocks', 'api_key')) {
                $table->string('api_key')->nullable();
            }
            if (! Schema::hasColumn('biolink_blocks', 'mailchimp_list')) {
                $table->string('mailchimp_list')->nullable();
            }
            if (! Schema::hasColumn('biolink_blocks', 'webhook_url')) {
                $table->string('webhook_url')->nullable();
            }
            if (! Schema::hasColumn('biolink_blocks', 'cancelled_payment_url')) {
                $table->string('cancelled_payment_url')->nullable();
            }

        });

        Company::chunk(50, function ($companies) {

            foreach ($companies as $company) {
                BiolinksGlobalSetting::addModuleSetting($company);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biolink_blocks', function (Blueprint $table) {
            $table->dropColumn('placeholder');
            $table->dropColumn('name_placeholder');
            $table->dropColumn('button_text');
            $table->dropColumn('thank_you_message');
            $table->dropColumn('thank_you_url');
            $table->dropColumn('show_agreement');
            $table->dropColumn('agreement_text');
            $table->dropColumn('agreement_url');
            $table->dropColumn('api_key');
            $table->dropColumn('mailchimp_list');
            $table->dropColumn('webhook_url');
            $table->dropColumn('cancelled_payment_url');
        });
    }

};
