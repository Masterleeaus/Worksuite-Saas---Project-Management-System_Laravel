<?php

namespace Modules\TitanDocs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\TitanDocs\Entities\AiTemplateLanguage;

class AiTemplateLanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $languages = [
            ['id' => 1, 'language' => 'Arabic', 'code' => 'ar-AE', 'flag' => 'ae.svg', 'status' => 1],
            ['id' => 2, 'language' => 'Chinese (Mandarin)', 'code' => 'cmn-CN', 'flag' => 'cn.svg', 'status' => 1],
            ['id' => 3, 'language' => 'Croatian (Croatia)', 'code' => 'hr-HR', 'flag' => 'hr.svg', 'status' => 1],
            ['id' => 4, 'language' => 'Czech (Czech Republic)', 'code' => 'cs-CZ', 'flag' => 'cz.svg', 'status' => 1],
            ['id' => 5, 'language' => 'Danish (Denmark)', 'code' => 'da-DK', 'flag' => 'dk.svg', 'status' => 1],
            ['id' => 6, 'language' => 'Dutch (Netherlands)', 'code' => 'nl-NL', 'flag' => 'nl.svg', 'status' => 1],
            ['id' => 7, 'language' => 'English (USA)', 'code' => 'en-US', 'flag' => 'us.svg', 'status' => 1],
            ['id' => 8, 'language' => 'Estonian (Estonia)', 'code' => 'et-EE', 'flag' => 'ee.svg', 'status' => 1],
            ['id' => 9, 'language' => 'Finnish (Finland)', 'code' => 'fi-FI', 'flag' => 'fi.svg', 'status' => 1],
            ['id' => 10, 'language' => 'French (France)', 'code' => 'fr-FR', 'flag' => 'fr.svg', 'status' => 1],
            ['id' => 11, 'language' => 'German (Germany)', 'code' => 'de-DE', 'flag' => 'de.svg', 'status' => 1],
            ['id' => 12, 'language' => 'Greek (Greece)', 'code' => 'el-GR', 'flag' => 'gr.svg', 'status' => 1],
            ['id' => 13, 'language' => 'Hebrew (Israel)', 'code' => 'he-IL', 'flag' => 'il.svg', 'status' => 1],
            ['id' => 14, 'language' => 'Hindi (India)', 'code' => 'hi-IN', 'flag' => 'in.svg', 'status' => 1],
            ['id' => 15, 'language' => 'Hungarian (Hungary)', 'code' => 'hu-HU', 'flag' => 'hu.svg', 'status' => 1],
            ['id' => 16, 'language' => 'Icelandic (Iceland)', 'code' => 'is-IS', 'flag' => 'is.svg', 'status' => 1],
            ['id' => 17, 'language' => 'Indonesian (Indonesia)', 'code' => 'id-ID', 'flag' => 'id.svg', 'status' => 1],
            ['id' => 18, 'language' => 'Italian (Italy)', 'code' => 'it-IT', 'flag' => 'it.svg', 'status' => 1],
            ['id' => 19, 'language' => 'Japanese (Japan)', 'code' => 'ja-JP', 'flag' => 'jp.svg', 'status' => 1],
            ['id' => 20, 'language' => 'Korean (South Korea)', 'code' => 'ko-KR', 'flag' => 'kr.svg', 'status' => 1],
            ['id' => 21, 'language' => 'Malay (Malaysia)', 'code' => 'ms-MY', 'flag' => 'my.svg', 'status' => 1],
            ['id' => 22, 'language' => 'Norwegian (Norway)', 'code' => 'nb-NO', 'flag' => 'no.svg', 'status' => 1],
            ['id' => 23, 'language' => 'Polish (Poland)', 'code' => 'pl-PL', 'flag' => 'pl.svg', 'status' => 1],
            ['id' => 24, 'language' => 'Portuguese (Portugal)', 'code' => 'pt-PT', 'flag' => 'pt.svg', 'status' => 1],
            ['id' => 25, 'language' => 'Russian (Russia)', 'code' => 'ru-RU', 'flag' => 'ru.svg', 'status' => 1],
            ['id' => 26, 'language' => 'Spanish (Spain)', 'code' => 'es-ES', 'flag' => 'es.svg', 'status' => 1],
            ['id' => 27, 'language' => 'Swedish (Sweden)', 'code' => 'sv-SE', 'flag' => 'se.svg', 'status' => 1],
            ['id' => 28, 'language' => 'Turkish (Turkey)', 'code' => 'tr-TR', 'flag' => 'tr.svg', 'status' => 1],
            ['id' => 29, 'language' => 'Portuguese (Brazil)', 'code' => 'pt-BR', 'flag' => 'br.svg', 'status' => 1],
            ['id' => 30, 'language' => 'Romanian (Romania)', 'code' => 'ro-RO', 'flag' => 'ro.svg', 'status' => 1],
            ['id' => 31, 'language' => 'Vietnamese (Vietnam)', 'code' => 'vi-VN', 'flag' => 'vn.svg', 'status' => 1],
            ['id' => 32, 'language' => 'Swahili (Kenya)', 'code' => 'sw-KE', 'flag' => 'ke.svg', 'status' => 1],
            ['id' => 33, 'language' => 'Slovenian (Slovenia)', 'code' => 'sl-SI', 'flag' => 'si.svg', 'status' => 1],
        ];


        foreach ($languages as $language) {
            AiTemplateLanguage::updateOrCreate(['id' => $language['id']], $language);
        }
        // $this->call("OthersTableSeeder");
    }
}
