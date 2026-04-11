<?php

namespace Modules\TitanTheme\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class WhiteLabelService
{
    protected string $disk;
    protected string $basePath;

    public function __construct()
    {
        $this->disk     = config('titantheme.storage_disk', 'public');
        $this->basePath = config('titantheme.storage_path', 'titan-theme');
    }

    // -------------------------------------------------------------------------
    // Logo & Favicon
    // -------------------------------------------------------------------------

    /**
     * Store the primary logo and return its storage path.
     */
    public function storeLogo(UploadedFile $file): string
    {
        return $this->storeAsset($file, 'logo');
    }

    /**
     * Store the favicon and return its storage path.
     */
    public function storeFavicon(UploadedFile $file): string
    {
        return $this->storeAsset($file, 'favicon');
    }

    /**
     * Store the login-page background image and return its storage path.
     */
    public function storeLoginBackground(UploadedFile $file): string
    {
        return $this->storeAsset($file, 'login-bg');
    }

    /**
     * Store the email header image and return its storage path.
     */
    public function storeEmailHeader(UploadedFile $file): string
    {
        return $this->storeAsset($file, 'email-header');
    }

    // -------------------------------------------------------------------------
    // Settings persistence (uses WorkSuite global settings helper)
    // -------------------------------------------------------------------------

    /**
     * Persist white-label settings via WorkSuite's setting() helper.
     * Falls back to a no-op if the helper is unavailable.
     */
    public function saveSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->saveSetting('titan_theme_' . $key, $value);
        }
    }

    /**
     * Retrieve a white-label setting value.
     */
    public function getSetting(string $key, mixed $default = null): mixed
    {
        $fullKey = 'titan_theme_' . $key;

        if (function_exists('setting')) {
            return setting($fullKey, $default);
        }

        return $default;
    }

    /**
     * Return the public URL for a stored asset, or null.
     */
    public function assetUrl(string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        return Storage::disk($this->disk)->url($path);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    protected function storeAsset(UploadedFile $file, string $slot): string
    {
        $ext  = $file->getClientOriginalExtension();
        $name = $slot . '.' . $ext;

        return $file->storeAs($this->basePath, $name, ['disk' => $this->disk]);
    }

    protected function saveSetting(string $key, mixed $value): void
    {
        if (function_exists('setting')) {
            // WorkSuite stores settings via the global setting() helper or
            // the BusinessSetting model — use whichever is available.
            try {
                \App\Models\Setting::updateOrCreate(
                    ['setting_name' => $key],
                    ['setting_value' => $value]
                );
            } catch (\Throwable $e) {
                // Silently ignore if model is unavailable.
            }
        }
    }
}
