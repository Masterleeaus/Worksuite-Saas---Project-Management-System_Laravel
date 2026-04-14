<?php

use App\Models\Module;
use App\Models\ModuleSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Zoom\Entities\ZoomGlobalSetting;
use Modules\Zoom\Entities\ZoomSetting;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('zoom_global_settings')) {
            Schema::create('zoom_global_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('purchase_code')->nullable();
            $table->string('license_type', 20)->nullable();
            $table->timestamp('supported_until')->nullable();
            $table->timestamps();
            });
        }

        $setting = Schema::hasTable('zoom_setting') ? ZoomSetting::withoutGlobalScope(\App\Scopes\CompanyScope::class)->first() : null;

        if (Schema::hasTable('zoom_global_settings') && !ZoomGlobalSetting::query()->exists()) {
            $newZoomSetting = new ZoomGlobalSetting;

            if ($setting) {
                $newZoomSetting->purchase_code = $setting->purchase_code;
                $newZoomSetting->supported_until = $setting->supported_until;
            }

            $newZoomSetting->saveQuietly();
        }

        if (Schema::hasTable('zoom_setting')) {
            Schema::table('zoom_setting', function (Blueprint $table) {
                if (Schema::hasColumn('zoom_setting', 'purchase_code')) {
                    $table->dropColumn('purchase_code');
                }
                if (Schema::hasColumn('zoom_setting', 'supported_until')) {
                    $table->dropColumn('supported_until');
                }
            });
        }

        Module::where('module_name', 'Zoom')->update([
            'module_name' => ZoomSetting::MODULE_NAME,
        ]);

        ModuleSetting::where('module_name', 'Zoom')->update([
            'module_name' => ZoomSetting::MODULE_NAME,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zoom_global_settings');
    }
};
