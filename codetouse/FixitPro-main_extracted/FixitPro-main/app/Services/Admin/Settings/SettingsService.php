<?php
namespace App\Services\Admin\Settings;

use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public function getAllSettings(): Collection
    {
        return Setting::orderBy('group')->orderBy('key')->get();
    }

    public function getSettingsByGroup(string $group): Collection
    {
        return Setting::where('group', $group)->get();
    }

    public function getGroupedSettings(): Collection
    {
        return Setting::all()->groupBy('group');
    }

    public function updateSettings(array $settings): void
    {
        $changes = [];

        foreach ($settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if ($setting) {
                $oldValue = $setting->value;

                if ($setting->type === 'boolean') {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
                }

                $setting->update(['value' => $value]);
                Cache::forget("setting.{$key}");

                if ($oldValue !== $value) {
                    $changes[$key] = ['old' => $oldValue, 'new' => $value];
                }
            }
        }

        if (! empty($changes)) {
            AuditLog::log(
                'updated',
                'Settings',
                null,
                'Platform Settings',
                ['changes' => array_keys($changes)],
                $changes,
                'Updated platform settings: ' . implode(', ', array_keys($changes))
            );
        }
    }

    public function initializeDefaultSettings(): void
    {
        $defaults = [
            // General
            ['key' => 'site_name', 'value' => 'FixitPro', 'type' => 'text', 'group' => 'general', 'label' => 'Site Name'],
            ['key' => 'site_tagline', 'value' => 'Field Service Management', 'type' => 'text', 'group' => 'general', 'label' => 'Tagline'],
            ['key' => 'support_email', 'value' => 'support@fixitpro.com', 'type' => 'text', 'group' => 'general', 'label' => 'Support Email'],
            ['key' => 'support_phone', 'value' => '+1 (555) 123-4567', 'type' => 'text', 'group' => 'general', 'label' => 'Support Phone'],

            // Branding
            ['key' => 'logo', 'value' => '', 'type' => 'image', 'group' => 'branding', 'label' => 'Logo'],
            ['key' => 'favicon', 'value' => '', 'type' => 'image', 'group' => 'branding', 'label' => 'Favicon'],
            ['key' => 'primary_color', 'value' => '#3b82f6', 'type' => 'text', 'group' => 'branding', 'label' => 'Primary Color'],

            // Social
            ['key' => 'facebook_url', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Facebook URL'],
            ['key' => 'twitter_url', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Twitter URL'],
            ['key' => 'linkedin_url', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'LinkedIn URL'],
            ['key' => 'instagram_url', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Instagram URL'],

            // Features
            ['key' => 'enable_registration', 'value' => '1', 'type' => 'boolean', 'group' => 'features', 'label' => 'Enable Registration'],
            ['key' => 'enable_trial', 'value' => '1', 'type' => 'boolean', 'group' => 'features', 'label' => 'Enable Free Trial'],
            ['key' => 'trial_days', 'value' => '14', 'type' => 'integer', 'group' => 'features', 'label' => 'Trial Period (Days)'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'features', 'label' => 'Maintenance Mode'],
        ];

        foreach ($defaults as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
