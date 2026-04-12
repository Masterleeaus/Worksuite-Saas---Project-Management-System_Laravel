<?php

namespace Modules\TitanTheme\Helpers;

/**
 * LiveCustomizer — font-setting helper.
 *
 * Reads the font configuration saved by LiveCustomizerController::apply()
 * and returns arrays of font-weight variants keyed by font family name,
 * ready for Google Fonts URL construction.
 *
 * Setting key: {dash_theme}_live_customizer_fonts
 */
class LiveCustomizer
{
    /**
     * Return font-weight arrays for the body and heading fonts stored in settings.
     *
     * Example return value:
     * [
     *     'Inter'      => ['400', '500', '600'],   // body font
     *     'Montserrat' => ['500', '600', '700'],   // heading font
     * ]
     *
     * Returns an empty array when no fonts have been saved yet.
     */
    public static function getFontSetting(): array
    {
        $fonts = setting(setting('dash_theme') . '_' . 'live_customizer_fonts');

        if (empty($fonts)) {
            return [];
        }

        $fontArray = [];

        $fontBody = data_get($fonts, 'fontBody');

        if ($fontBody) {
            $fontArray[$fontBody] = ['400', '500', '600'];
        }

        $fontHeading = data_get($fonts, 'fontHeading');

        if ($fontHeading) {
            $fontArray[$fontHeading] = ['500', '600', '700'];
        }

        return $fontArray;
    }
}
