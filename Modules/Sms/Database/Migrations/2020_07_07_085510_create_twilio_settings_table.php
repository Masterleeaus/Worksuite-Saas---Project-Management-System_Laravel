<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Sms\Entities\SmsSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\Module::validateVersion(SmsSetting::MODULE_NAME);

        if (Schema::hasTable('sms_settings')) {
            return;
        }
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();

            $table->string('account_sid')->nullable();
            $table->string('auth_token')->nullable();
            $table->string('from_number')->nullable();
            $table->boolean('status')->default(false);
            $table->string('whatapp_from_number')->nullable();
            $table->boolean('whatsapp_status')->default(false);

            $table->string('nexmo_api_key')->nullable();
            $table->string('nexmo_api_secret')->nullable();
            $table->string('nexmo_from_number')->nullable();
            $table->boolean('nexmo_status')->default(false);

            $table->string('msg91_auth_key')->nullable();
            $table->string('msg91_from')->nullable();
            $table->boolean('msg91_status')->default(false);

            $table->string('purchase_code')->nullable();
            $table->timestamp('supported_until')->nullable();
            $table->timestamps();
        });

        SmsSetting::create([
            'status' => false,
            'whatsapp_status' => false,
            'nexmo_status' => false,
            'msg91_status' => false,
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_settings');
    }
};
