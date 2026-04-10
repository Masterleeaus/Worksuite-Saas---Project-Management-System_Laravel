<?php

namespace Modules\TitanDocs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\TitanDocs\Entities\AiTemplatePrompt;
class AiTemplatePropmtTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $prompts= [
            ['id' => 1, 'template_id' => 1, 'key' => "en-US", 'value' => "Write a complete article on this topic:\n\n ##title## \n\nUse following keywords in the article:\n ##keywords## \n\nTone of voice of the article must be:\n ##tone_language## \n\n"],

            ['id' => 2, 'template_id' => 1, 'key' => "ar-AE", 'value' => 'اكتب مقالة حول هذا الموضوع:\n\n"##title## "\n\nاستخدم الكلمات الأساسية التالية في المقالة:\n"##keywords## "\n\nيجب أن تكون نغمة صوت المقالة:\n"##tone_language## "\n\n'],

            ['id' => 3, 'template_id' => 1, 'key' => "cmn-CN", 'value' => '写一篇关于这个主题的文章：\n\n" ##title## "\n\n在文章中使用以下关键字：\n" ##keywords## "\n\n文章的语气必须是：\n" ##tone_language## "\n\n'],

            ['id' => 4, 'template_id' => 1, 'key' => "hr-HR", 'value' => 'Napišite članak na ovu temu:\n\n" ##title## "\n\nKoristite sljedeće ključne riječi u članku:\n" ##keywords## "\n\nTon glasa u članku mora biti:\n" ##tone_language## "\n\n'],

            ['id' => 5, 'template_id' => 1, 'key' => "cs-CZ", 'value' => 'Napište článek na toto téma:\n\n" ##title## "\n\nV článku použijte následující klíčová slova:\n" ##keywords## "\n\nTón hlasu článku musí být:\n" ##tone_language## "\n\n'],

            ['id' => 6, 'template_id' => 1, 'key' => "da-DK", 'value' => 'Skriv en artikel om dette emne:\n\n" ##title## "\n\nBrug følgende søgeord i artiklen:\n" ##keywords## "\n\nTone i artiklen skal være:\n" ##tone_language## "\n\n'],

            ['id' => 7, 'template_id' => 1, 'key' => "nl-NL", 'value' => 'Schrijf een artikel over dit onderwerp:\n\n" ##title## "\n\nGebruik de volgende trefwoorden in het artikel:\n" ##keywords## "\n\nDe toon van het artikel moet zijn:\n" ##tone_language## "\n\n'],

            ['id' => 8, 'template_id' => 1, 'key' => "et-EE", 'value' => 'Kirjutage sellel teemal artikkel:\n\n" ##title## "\n\nKasutage artiklis järgmisi märksõnu:\n" ##keywords## "\n\nArtikli hääletoon peab olema:\n" ##tone_language## "\n\n'],

            ['id' => 9, 'template_id' => 1, 'key' => "fi-FI", 'value' => 'Kirjoita artikkeli tästä aiheesta:\n\n" ##title## "\n\nKäytä artikkelissa seuraavia avainsanoja:\n" ##keywords## "\n\nArtikkelin äänensävyn on oltava:\n" ##tone_language## "\n\n'],

            ['id' => 10, 'template_id' => 1, 'key' => "fr-FR", 'value' => 'Ecrire un article sur ce sujet :\n\n" ##title## "\n\nUtilisez les mots clés suivants dans l article :\n" ##keywords## "\n\nLe ton de la voix de l article doit être :\n" ##tone_language## "\n\n'],

            ['id' => 11, 'template_id' => 1, 'key' => "de-DE", 'value' => 'Schreiben Sie einen Artikel zu diesem Thema:\n\n" ##title## "\n\nVerwenden Sie folgende Schlüsselwörter im Artikel:\n" ##keywords## "\n\nTonfall des Artikels muss sein:\n" ##tone_language## "\n\n'],

            ['id' => 12, 'template_id' => 1, 'key' => "el-GR", 'value' => 'Γράψτε ένα άρθρο για αυτό το θέμα:\n\n" ##title## "\n\nΧρησιμοποιήστε τις ακόλουθες λέξεις-κλειδιά στο άρθρο:\n" ##keywords## "\n\nΟ τόνος της φωνής του άρθρου πρέπει να είναι:\n" ##tone_language## "\n\n'],

            ['id' => 13, 'template_id' => 1, 'key' => "he-IL", 'value' => 'כתוב מאמר בנושא זה:\n\n" ##title## "\n\nהשתמש במילות המפתח הבאות במאמר:\n" ##keywords## "\n\nטון הדיבור של המאמר חייב להיות:\n" ##tone_language## "\n\n'],

            ['id' => 14, 'template_id' => 1, 'key' => "hi-IN", 'value' => 'इस विषय पर एक लेख लिखें:\n\n" ##title## "\n\nलेख में निम्नलिखित कीवर्ड का प्रयोग करें:\n" ##keywords## "\n\nलेख का स्वर इस प्रकार होना चाहिए:\n" ##tone_language##"\n\n'],

            ['id' => 15, 'template_id' => 1, 'key' => "hu-HU", 'value' => 'Írjon cikket erről a témáról:\n\n" ##title## "\n\nHasználja a következő kulcsszavakat a cikkben:\n" ##keywords## "\n\nA cikk hangnemének a következőnek kell lennie:\n" ##tone_language## "\n\n'],

            ['id' => 16, 'template_id' => 1, 'key' => "is-IS", 'value' => 'Skrifaðu grein um þetta efni:\n\n" ##title## "\n\nNotaðu eftirfarandi leitarorð í greininni:\n" ##keywords## "\n\nTónn í greininni verður að vera:\n" ##tone_language## "\n\n'],

            ['id' => 17, 'template_id' => 1, 'key' => "id-ID", 'value' => 'Tulis artikel tentang topik ini:\n\n" ##title## "\n\nGunakan kata kunci berikut dalam artikel:\n" ##keywords## "\n\nNada suara artikel harus:\n" ##tone_language## "\n\n'],

            ['id' => 18, 'template_id' => 1, 'key' => "it-IT", 'value' => 'Scrivi un articolo su questo argomento:\n\n" ##title## "\n\nUsa le seguenti parole chiave nell articolo:\n" ##keywords## "\n\nIl tono di voce dell articolo deve essere:\n" ##tone_language## "\n\n'],

            ['id' => 19, 'template_id' => 1, 'key' => "ja-JP", 'value' => 'このトピックに関する記事を書いてください:\n\n" ##title## "\n\n記事では次のキーワードを使用してください:\n" ##keywords## "\n\n記事の口調は次のようにする必要があります:\n" ##tone_language## "\n\n'],

            ['id' => 20, 'template_id' => 1, 'key' => "ko-KR", 'value' => '이 주제에 대한 기사 쓰기:\n\n" ##title## "\n\n문서에서 다음 키워드를 사용하십시오:\n" ##keywords## "\n\n기사의 어조는 다음과 같아야 합니다:\n" ##tone_language## "\n\n'],

            ['id' => 21, 'template_id' => 1, 'key' => "ms-MY", 'value' => 'Tulis artikel tentang topik ini:\n\n" ##title## "\n\nGunakan kata kunci berikut dalam artikel:\n" ##keywords## "\n\nNada suara artikel mestilah:\n" ##tone_language## "\n\n'],

            ['id' => 22, 'template_id' => 1, 'key' => "nb-NO", 'value' => 'Skriv en artikkel om dette emnet:\n\n" ##title## "\n\nBruk følgende nøkkelord i artikkelen:\n" ##keywords## "\n\nTone i artikkelen må være:\n" ##tone_language## "\n\n'],

            ['id' => 23, 'template_id' => 1, 'key' => "pl-PL", 'value' => 'Napisz artykuł na ten temat:\n\n" ##title## "\n\nUżyj w artykule następujących słów kluczowych:\n" ##keywords## "\n\nTon wypowiedzi artykułu musi być:\n" ##tone_language## "\n\n'],

            ['id' => 24, 'template_id' => 1, 'key' => "pt-PT", 'value' => 'Escreva um artigo sobre este tópico:\n\n" ##title## "\n\nUse as seguintes palavras-chave no artigo:\n" ##keywords## "\n\nTom de voz do artigo deve ser:\n" ##tone_language## "\n\n'],

            ['id' => 25, 'template_id' => 1, 'key' => "ru-RU", 'value' => 'Напишите статью на эту тему:\n\n" ##title## "\n\nИспользуйте в статье следующие ключевые слова:\n" ##keywords## "\n\nТон озвучивания статьи должен быть:\n" ##tone_language## "\n\n'],

            ['id' => 26, 'template_id' => 1, 'key' => "es-ES", 'value' => 'Escribe un artículo sobre este tema:\n\n" ##title## "\n\nUtilice las siguientes palabras clave en el artículo:\n" ##keywords## "\n\nEl tono de voz del artículo debe ser:\n" ##tone_language## "\n\n'],

            ['id' => 27, 'template_id' => 1, 'key' => "sv-SE", 'value' => 'Skriv en artikel om detta ämne:\n\n" ##title## "\n\nAnvänd följande nyckelord i artikeln:\n" ##keywords## "\n\nTonfallet för artikeln måste vara:\n" ##tone_language## "\n\n" ##tone_language## "\n\n'],

            ['id' => 28, 'template_id' => 1, 'key' => "tr-TR", 'value' => 'Bu konuda bir makale yaz:\n\n" ##title## "\n\nMakalede şu anahtar kelimeleri kullanın:\n" ##keywords## "\n\nYazının ses tonu şöyle olmalıdır:\n" ##tone_language## "\n\n'],

            ['id' => 29, 'template_id' => 1, 'key' => "pt-BR", 'value' => 'Escreva um artigo sobre este tópico:\n\n" ##title## "\n\nUse as seguintes palavras-chave no artigo:\n" ##keywords## "\n\nTom de voz do artigo deve ser:\n" ##tone_language## "\n\n'],

            ['id' => 30, 'template_id' => 1, 'key' => "ro-RO", 'value' => 'Scrieți un articol complet pe acest subiect:\n\n" ##title## "\n\nFolosiți următoarele cuvinte cheie în articol:\n" ##keywords## "\n\nTonul vocii al articolului trebuie să fie:\n" ##tone_language## "\n\n'],

            ['id' => 31, 'template_id' => 1, 'key' => "vi-VN", 'value' => 'Viết một bài hoàn chỉnh về chủ đề này:\n\n" ##title## "\n\nSử dụng các từ khóa sau trong bài viết:\n" ##keywords## "\n\nGiọng điệu của bài viết phải là:\n" ##tone_language## "\n\n'],

            ['id' => 32, 'template_id' => 1, 'key' => "sw-KE", 'value' => 'Andika makala kamili kuhusu mada hii:\n\n" ##title## "\n\nTumia manenomsingi yafuatayo katika makala:\n" ##keywords## "\n\nToni ya sauti ya makala lazima iwe:\n" ##tone_language## "\n\n'],

            ['id' => 33, 'template_id' => 1, 'key' => "sl-SI", 'value' => 'Napišite celoten članek o tej temi:\n\n" ##title## "\n\n članku uporabite naslednje ključne besede:\n" ##keywords## "\n\nTon glasu članka mora biti:\n" ##tone_language## "\n\n'],

            ['id' => 34, 'template_id' => 2, 'key' => "en-US", 'value' => 'Improve and rewrite the text in a creative and smart way:\n\n" ##title## "\n\n Tone of voice of the result must be:\n" ##tone_language## "\n\n'],

            ['id' => 35, 'template_id' => 2, 'key' => "ar-AE", 'value' => 'اتحسين وإعادة كتابة النص بطريقة إبداعية وذكية:\n\n"##title## "\n\nيجب أن تكون نبرة صوت النتيجة:\n"##tone_language## "\n\n'],

            ['id' => 36, 'template_id' => 2, 'key' => "cmn-CN", 'value' => '以创造性和聪明的方式改进和重写文本：\n\n"##title## "\n\n 结果的语气必须是：\n" ##tone_language## "\n\n'],

            ['id' => 37, 'template_id' => 2, 'key' => "hr-HR", 'value' => 'Poboljšajte i prepišite tekst na kreativan i pametan način:\n\n" ##title## "\n\n Ton glasa rezultata mora biti:\n" ##tone_language## "\n\n'],

            ['id' => 38, 'template_id' => 2, 'key' => "cs-CZ", 'value' => 'Vylepšete a přepište text kreativním a chytrým způsobem:\n\n" ##title## "\n\n Tón hlasu výsledku musí být:\n" ##tone_language## "\n\n'],

            ['id' => 39, 'template_id' => 2, 'key' => "da-DK", 'value' => 'Forbedre og omskriv teksten på en kreativ og smart måde:\n\n" ##title## "\n\n Tonen i resultatet skal være:\n" ##tone_language## "\n\n'],

            ['id' => 40, 'template_id' => 2, 'key' => "nl-NL", 'value' => 'Verbeter en herschrijf de tekst op een creatieve en slimme manier:\n\n" ##title## "\n\n Tone of voice van het resultaat moet zijn:\n" ##tone_language## "\n\n'],

            ['id' => 41, 'template_id' => 2, 'key' => "et-EE", 'value' => 'Täiustage ja kirjutage teksti loominguliselt ja nutikalt ümber:\n\n" ##title## "\n\n Tulemuse hääletoon peab olema:\n" ##tone_language## "\n\n'],

            ['id' => 42, 'template_id' => 2, 'key' => "fi-FI", 'value' => 'Paranna ja kirjoita tekstiä uudelleen luovalla ja älykkäällä tavalla:\n\n" ##title## "\n\n Tuloksen äänensävyn on oltava:\n" ##tone_language## "\n\n'],

            ['id' => 43, 'template_id' => 2, 'key' => "fr-FR", 'value' => 'Améliorez et réécrivez le texte de manière créative et intelligente :\n\n" ##title## "\n\n Le ton de la voix du résultat doit être :\n" ##tone_language## "\n\n'],

            ['id' => 44, 'template_id' => 2, 'key' => "de-DE", 'value' => 'Verbessern und überarbeiten Sie den Text auf kreative und intelligente Weise:\n\n" ##title## "\n\n Tonfall des Ergebnisses muss sein:\n" ##tone_language## "\n\n'],

            ['id' => 45, 'template_id' => 2, 'key' => "el-GR", 'value' => 'Βελτιώστε και ξαναγράψτε το κείμενο με δημιουργικό και έξυπνο τρόπο:\n\n" ##title## "\n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n" ##tone_language## "\n\n'],

            ['id' => 46, 'template_id' => 2, 'key' => "he-IL", 'value' => 'שפר ושכתב את הטקסט בצורה יצירתית וחכמה:\n\n" ##title## "\n\n גוון הקול של התוצאה חייב להיות:\n" ##tone_language## "\n\n'],

            ['id' => 47, 'template_id' => 2, 'key' => "hi-IN", 'value' => 'रचनात्मक और स्मार्ट तरीके से टेक्स्ट को सुधारें और फिर से लिखें:\n\n" ##title## "\n\n परिणाम की आवाज़ का स्वर होना चाहिए:\n" ##tone_language## "\n\n'],

            ['id' => 48, 'template_id' => 2, 'key' => "hu-HU", 'value' => 'Javítsa és írja át a szöveget kreatív és okos módon:\n\n" ##title## "\n\n Az eredmény hangszínének a következőnek kell lennie:\n" ##tone_language## "\n\n'],

            ['id' => 49, 'template_id' => 2, 'key' => "is-IS", 'value' => 'Bættu og endurskrifaðu textann á skapandi og snjallan hátt:\n\n" ##title## "\n\n Röddtónn niðurstöðunnar verður að vera:\n" ##tone_language## "\n\n'],

            ['id' => 50, 'template_id' => 2, 'key' => "id-ID", 'value' => 'Tingkatkan dan tulis ulang teks dengan cara yang kreatif dan cerdas:\n\n" ##title## "\n\n Nada suara hasil harus:\n" ##tone_language## "\n\n'],

            ['id' => 51, 'template_id' => 2, 'key' => "it-IT", 'value' => 'Migliora e riscrivi il testo in modo creativo e intelligente:\n\n" ##title## "\n\n Il tono di voce del risultato deve essere:\n" ##tone_language## "\n\n'],

            ['id' => 52, 'template_id' => 2, 'key' => "ja-JP", 'value' => '創造的かつスマートな方法でテキストを改善および書き直します:\n\n" ##title## "\n\n 結果の声の調子:\n" ##tone_language## "\n\n'],

            ['id' => 53, 'template_id' => 2, 'key' => "ko-KR", 'value' => '창의적이고 스마트한 방식으로 텍스트를 개선하고 다시 작성:\n\n" ##title## "\n\n 결과의 음성 톤은 다음과 같아야 합니다.\n" ##tone_language## "\n\n'],

            ['id' => 54, 'template_id' => 2, 'key' => "ms-MY", 'value' => 'Tingkatkan dan tulis semula teks dengan cara yang kreatif dan pintar:\n\n" ##title## "\n\n Nada suara hasil carian mestilah:\n" ##tone_language## "\n\n'],

            ['id' => 55, 'template_id' => 2, 'key' => "nb-NO", 'value' => 'Forbedre og omskriv teksten på en kreativ og smart måte:\n\n" ##title## "\n\n Tonen til resultatet må være:\n" ##tone_language## "\n\n'],

            ['id' => 56, 'template_id' => 2, 'key' => "pl-PL", 'value' => 'Popraw i przepisz tekst w kreatywny i inteligentny sposób:\n\n" ##title## "\n\n Ton głosu wyniku musi być:\n" ##tone_language## "\n\n'],

            ['id' => 57, 'template_id' => 2, 'key' => "pt-PT", 'value' => 'Melhorar e reescrever o texto de forma criativa e inteligente:\n\n" ##title## "\n\n Tom de voz do resultado deve ser:\n" ##tone_language## "\n\n'],

            ['id' => 58, 'template_id' => 2, 'key' => "ru-RU", 'value' => 'Улучшите и перепишите текст творчески и по-умному:\n\n" ##title## "\n\n Тон голоса результата должен быть:\n" ##tone_language## "\n\n'],

            ['id' => 59, 'template_id' => 2, 'key' => "es-ES", 'value' => 'Mejora y reescribe el texto de forma creativa e inteligente:\n\n" ##title## "\n\n El tono de voz del resultado debe ser:\n" ##tone_language## "\n\n'],

            ['id' => 60, 'template_id' => 2, 'key' => "sv-SE", 'value' => 'Förbättra och skriv om texten på ett kreativt och smart sätt:\n\n" ##title## "\n\n Tonen i resultatet måste vara:\n" ##tone_language## "\n\n'],

            ['id' => 61, 'template_id' => 2, 'key' => "tr-TR", 'value' => 'Metni yaratıcı ve akıllı bir şekilde iyileştirin ve yeniden yazın:\n\n" ##title## "\n\n Sonucun ses tonu şöyle olmalıdır:\n" ##tone_language## "\n\n'],

            ['id' => 62, 'template_id' => 2, 'key' => "pt-BR", 'value' => 'Melhorar e reescrever o texto de forma criativa e inteligente:\n\n" ##title## "\n\n Tom de voz do resultado deve ser:\n" ##tone_language## "\n\n'],

            ['id' => 63, 'template_id' => 2, 'key' => "ro-RO", 'value' => 'Îmbunătățiți și rescrieți textul într-un mod creativ și inteligent:\n\n" ##title## "\n\n Tonul vocii rezultatului trebuie să fie:\n" ##tone_language## "\n\n'],

            ['id' => 64, 'template_id' => 2, 'key' => "vi-VN", 'value' => 'Cải thiện và viết lại văn bản một cách sáng tạo và thông minh:\n\n" ##title## "\n\n Giọng điệu của kết quả phải là:\n" ##tone_language## "\n\n'],

            ['id' => 65, 'template_id' => 2, 'key' => "sw-KE", 'value' => 'Boresha na uandike upya maandishi kwa njia ya kibunifu na ya busara:\n\n" ##title## "\n\n Toni ya sauti ya matokeo lazima iwe:\n" ##tone_language## "\n\n'],

            ['id' => 66, 'template_id' => 2, 'key' => "sl-SI", 'value' => 'Izboljšajte in prepišite besedilo na kreativen in pameten način:\n\n" ##title## "\n\n Ton glasu rezultata mora biti:\n" ##tone_language## "\n\n'],

            ['id' => 67, 'template_id' => 3, 'key' => "en-US", 'value' => 'Write a large and meaningful paragraph  on this topic:\n\n ##title## \n\nUse following keywords in the paragraph:\n ##keywords## \n\nTone of voice of the paragraph must be:\n ##tone_language## \n\n'],

            ['id' => 68, 'template_id' => 3, 'key' => "ar-AE", 'value' => 'اكتب فقرة كبيرة وذات مغزى حول هذا الموضوع:\n\n ##title##\n\nاستخدم الكلمات الأساسية التالية في الفقرة:\n ##keywords## \n\nيجب أن تكون نغمة الصوت في الفقرة:\n ##tone_language## \n\n'],

            ['id' => 69, 'template_id' => 3, 'key' => "cmn-CN", 'value' => '就此主题写一段有意义的长篇大论：\n\n ##title## \n\n在段落中使用以下关键字：\n ##keywords## \n\n段落的语气必须是：\n ##tone_language## \n\n'],

            ['id' => 70, 'template_id' => 3, 'key' => "hr-HR", 'value' => 'Napišite veliki i smisleni odlomak o ovoj temi:\n\n ##title## \n\nKoristite sljedeće ključne riječi u odlomku:\n ##keywords## \n\nTon glasa odlomka mora biti:\n ##tone_language## \n\n'],

            ['id' => 71, 'template_id' => 3, 'key' => "cs-CZ", 'value' => 'Napište velký a smysluplný odstavec na toto téma:\n\n ##title## \n\nV odstavci použijte následující klíčová slova:\n ##keywords## \n\nTón hlasu odstavce musí být:\n ##tone_language## \n\n'],

            ['id' => 72, 'template_id' => 3, 'key' => "da-DK", 'value' => 'Skriv et stort og meningsfuldt afsnit om dette emne:\n\n ##title## \n\nBrug følgende nøgleord i afsnittet:\n ##keywords## \n\nTone i afsnittet skal være:\n ##tone_language## \n\n'],

            ['id' => 73, 'template_id' => 3, 'key' => "nl-NL", 'value' => 'Schrijf een grote en zinvolle paragraaf over dit onderwerp:\n\n ##title## \n\nGebruik de volgende trefwoorden in de alinea:\n ##keywords## \n\nDe toon van de alinea moet zijn:\n ##tone_language## \n\n'],

            ['id' => 74, 'template_id' => 3, 'key' => "et-EE", 'value' => 'Kirjutage sellel teemal suur ja sisukas lõik:\n\n ##title## \n\nKasutage lõigus järgmisi märksõnu:\n ##keywords## \n\nLõigu hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 75, 'template_id' => 3, 'key' => "fi-FI", 'value' => 'Kirjoita tästä aiheesta suuri ja merkityksellinen kappale:\n\n ##title## \n\nKäytä kappaleessa seuraavia avainsanoja:\n ##keywords## \n\nKappaleen äänensävyn on oltava:\n ##tone_language## \n\n'],

            ['id' => 76, 'template_id' => 3, 'key' => "fr-FR", 'value' => 'Écrivez un paragraphe long et significatif sur ce sujet :\n\n ##title## \n\nUtilisez les mots clés suivants dans le paragraphe :\n ##keywords## \n\nLe ton de la voix du paragraphe doit être :\n ##tone_language## \n\n'],

            ['id' => 77, 'template_id' => 3, 'key' => "de-DE", 'value' => 'Schreiben Sie einen großen und aussagekräftigen Absatz zu diesem Thema:\n\n ##title## \n\nVerwenden Sie folgende Schlüsselwörter im Absatz:\n ##keywords## \n\nTonlage des Absatzes muss sein:\n ##tone_language## \n\n'],

            ['id' => 78, 'template_id' => 3, 'key' => "el-GR", 'value' => 'Γράψτε μια μεγάλη και ουσιαστική παράγραφο για αυτό το θέμα:\n\n ##title## \n\nΧρησιμοποιήστε τις ακόλουθες λέξεις-κλειδιά στην παράγραφο:\n ##keywords## \n\nΟ τόνος της φωνής της παραγράφου πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 79, 'template_id' => 3, 'key' => "he-IL", 'value' => 'כתוב פסקה גדולה ומשמעותית בנושא זה:\n\n ##title## \n\nהשתמש במילות המפתח הבאות בפסקה:\n ##keywords## \n\nטון הדיבור של הפסקה חייב להיות:\n ##tone_language##\n\n'],

            ['id' => 80, 'template_id' => 3, 'key' => "hi-IN", 'value' => 'इस विषय पर एक बड़ा और सार्थक पैराग्राफ लिखें:\n\n ##title## \n\nपैराग्राफ में निम्नलिखित कीवर्ड का प्रयोग करें:\n ##keywords## \n\nपैराग्राफ की आवाज़ का स्वर होना चाहिए:\n ##tone_language##\n\n'],

            ['id' => 81, 'template_id' => 3, 'key' => "hu-HU", 'value' => 'Írj egy nagy és értelmes bekezdést erről a témáról:\n\n ##title## \n\nHasználja a következő kulcsszavakat a bekezdésben:\n ##keywords## \n\nA bekezdés hangszínének a következőnek kell lennie:\n ##tone_language## \n\n'],

            ['id' => 82, 'template_id' => 3, 'key' => "is-IS", 'value' => 'Skrifaðu stóra og þýðingarmikla málsgrein um þetta efni:\n\n ##title## \n\nNotaðu eftirfarandi leitarorð í málsgreininni:\n ##keywords## \n\nTónn málsgreinarinnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 83, 'template_id' => 3, 'key' => "id-ID", 'value' => 'Tulis paragraf yang besar dan bermakna tentang topik ini:\n\n ##title## \n\nGunakan kata kunci berikut dalam paragraf:\n ##keywords## \n\nNada suara paragraf harus:\n ##tone_language## \n\n'],

            ['id' => 84, 'template_id' => 3, 'key' => "it-IT", 'value' => 'Scrivi un paragrafo ampio e significativo su questo argomento:\n\n ##title## \n\nUsare le seguenti parole chiave nel paragrafo:\n ##keywords## \n\nIl tono di voce del paragrafo deve essere:\n ##tone_language## \n\n'],

            ['id' => 85, 'template_id' => 3, 'key' => "ja-JP", 'value' => 'このトピックについて大きくて意味のある段落を書いてください:\n\n ##title## \n\n段落内で次のキーワードを使用してください:\n ##keywords## \n\n段落の口調は次のようにする必要があります:\n ##tone_language## \n\n'],

            ['id' => 86, 'template_id' => 3, 'key' => "ko-KR", 'value' => '이 주제에 대해 크고 의미 있는 단락 작성:\n\n ##title## \n\n단락에서 다음 키워드를 사용하십시오:\n ##keywords## \n\n문단의 어조는 다음과 같아야 합니다.\n ##tone_language## \n\n'],

            ['id' => 87, 'template_id' => 3, 'key' => "ms-MY", 'value' => 'Tulis perenggan yang besar dan bermakna tentang topik ini:\n\n ##title## \n\nGunakan kata kunci berikut dalam perenggan:\n ##keywords## \n\nNada suara perenggan mestilah:\n ##tone_language## \n\n'],

            ['id' => 88, 'template_id' => 3, 'key' => "nb-NO", 'value' => 'Skriv et stort og meningsfullt avsnitt om dette emnet:\n\n ##title## \n\nBruk følgende nøkkelord i avsnittet:\n ##keywords## \n\nTone i avsnittet må være:\n ##tone_language## \n\n'],

            ['id' => 89, 'template_id' => 3 , 'key' => "pl-PL", 'value' => 'Napisz duży i znaczący akapit na ten temat:\n\n ##title## \n\nUżyj następujących słów kluczowych w akapicie:\n ##keywords## \n\nTon głosu akapitu musi być:\n ##tone_language## \n\n'],

            ['id' => 90, 'template_id' => 3, 'key' => "pt-PT", 'value' => 'Escreva um parágrafo grande e significativo sobre este tópico:\n\n ##title## \n\nUse as seguintes palavras-chave no parágrafo:\n ##keywords## \n\nTom de voz do parágrafo deve ser:\n ##tone_language## \n\n'],

            ['id' => 91, 'template_id' => 3, 'key' => "ru-RU", 'value' => 'Напишите большой и осмысленный абзац на эту тему:\n\n ##title## \n\nИспользуйте следующие ключевые слова в абзаце:\n ##keywords## \n\nТон абзаца должен быть:\n ##tone_language## \n\n'],

            ['id' => 92, 'template_id' => 3, 'key' => "es-ES", 'value' => 'Escribe un párrafo extenso y significativo sobre este tema:\n\n ##title## \n\nUtilice las siguientes palabras clave en el párrafo:\n ##keywords## \n\nEl tono de voz del párrafo debe ser:\n ##tone_language## \n\n'],

            ['id' => 93, 'template_id' => 3, 'key' => "sv-SE", 'value' => 'Skriv ett stort och meningsfullt stycke om detta ämne:\n\n ##title## \n\nAnvänd följande nyckelord i stycket:\n ##keywords## \n\nTonfallet i stycket måste vara:\n ##tone_language## \n\n'],

            ['id' => 94, 'template_id' => 3, 'key' => "tr-TR", 'value' => 'Bu konu hakkında geniş ve anlamlı bir paragraf yaz:\n\n ##title## \n\nParagrafta şu anahtar sözcükleri kullanın:\n ##keywords## \n\nParagrafın ses tonu şöyle olmalıdır:\n ##tone_language## \n\n'],

            ['id' => 95, 'template_id' => 3, 'key' => "pt-BR", 'value' => 'Escreva um parágrafo grande e significativo sobre este tópico:\n\n ##title## \n\nUse as seguintes palavras-chave no parágrafo:\n ##keywords## \n\nTom de voz do parágrafo deve ser:\n ##tone_language## \n\n'],

            ['id' => 96, 'template_id' => 3, 'key' => "ro-RO", 'value' => 'Scrieți un paragraf mare și semnificativ pe acest subiect:\n\n ##title## \n\nFolosiți următoarele cuvinte cheie în paragraf:\n ##keywords## \n\nTonul vocii al paragrafului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 97, 'template_id' => 3, 'key' => "vi-VN", 'value' => 'Viết một đoạn văn lớn và có ý nghĩa về chủ đề này:\n\n ##title## \n\nSử dụng các từ khóa sau trong đoạn văn:\n ##keywords## \n\nGiọng điệu của đoạn phải là:\n ##tone_language## \n\n'],

            ['id' => 98, 'template_id' => 3, 'key' => "sw-KE", 'value' => 'Andika aya kubwa na yenye maana juu ya mada hii:\n\n ##title## \n\nTumia manenomsingi yafuatayo katika aya:\n ##keywords## \n\nToni ya sauti ya aya lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 99, 'template_id' => 3, 'key' => "sl-SI", 'value' => 'Napišite velik in smiseln odstavek o tej temi:\n\n ##title## \n\nV odstavku uporabite naslednje ključne besede:\n ##keywords## \n\nTon glasu odstavka mora biti:\n ##tone_language## \n\n'],

            ['id' => 100, 'template_id' => 3, 'key' => "th-TH", 'value' => 'เขียนย่อหน้าใหญ่และมีความหมายในหัวข้อนี้:\n\n ##title## \n\nใช้คำหลักต่อไปนี้ในย่อหน้า:\n ##keywords## \n\nน้ำเสียงของย่อหน้าต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 101, 'template_id' => 3, 'key' => "uk-UA", 'value' => 'Напишіть великий і змістовний абзац на цю тему:\n\n ##title## \n\nВикористовуйте такі ключові слова в абзаці:\n ##keywords## \n\nТон абзацу має бути:\n ##tone_language## \n\n'],

            ['id' => 102, 'template_id' => 3, 'key' => "lt-LT", 'value' => 'Parašykite didelę ir prasmingą pastraipą šia tema:\n\n ##title## \n\nPastraipoje naudokite šiuos raktinius žodžius:\n ##keywords## \n\nPastraipos balso tonas turi būti:\n ##tone_language## \n\n'],

            ['id' => 103, 'template_id' => 3, 'key' => "bg-BG", 'value' => 'Напишете голям и смислен параграф по тази тема:\n\n ##title## \n\nИзползвайте следните ключови думи в параграфа:\n ##keywords## \n\nТонът на гласа на абзаца трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 104, 'template_id' => 4, 'key' => "en-US", 'value' => 'Write short, simple and informative talking points for:\n\n ##title## \n\nAnd also similar talking points for subheadings:\n ##keywords## \n\nTone of voice of the paragraph must be:\n ##tone_language## \n\n'],

            ['id' => 105, 'template_id' => 4, 'key' => "ar-AE", 'value' => 'اكتب نقاط حديث قصيرة وبسيطة وغنية بالمعلومات من أجل:\n\n ##title## \n\nونقاط الحديث المشابهة للعناوين الفرعية:\n ##keywords## \n\nيجب أن تكون نغمة الصوت في الفقرة:\n ##tone_language## \n\n'],

            ['id' => 106, 'template_id' => 4, 'key' => "cmn-CN", 'value' => '为以下内容编写简短、简单且信息丰富的谈话要点：\n\n ##title## \n\n以及副标题的类似谈话要点：\n ##keywords## \n\n段落的语气必须是 :\n ##tone_language## \n\n'],

            ['id' => 107, 'template_id' => 4, 'key' => "hr-HR", 'value' => 'Napišite kratke, jednostavne i informativne teme za:\n\n ##title## \n\nI također slične teme za podnaslove:\n ##keywords## \n\nTon glasa odlomka mora biti:\n ##tone_language## \n\n'],

            ['id' => 108, 'template_id' => 4, 'key' => "cs-CZ", 'value' => 'Napište krátké, jednoduché a informativní body pro:\n\n ##title## \n\nA také podobná témata pro podnadpisy:\n ##keywords## \n\nTón hlasu odstavce musí být:\n ##tone_language## \n\n'],

            ['id' => 109, 'template_id' => 4, 'key' => "da-DK", 'value' => 'Skriv korte, enkle og informative talepunkter til:\n\n ##title## \n\nOg også lignende talepunkter for underoverskrifter:\n ##keywords## \n\nTonefaldet i afsnittet skal være:\n ##tone_language## \n\n'],

            ['id' => 110, 'template_id' => 4, 'key' => "nl-NL", 'value' => 'Schrijf korte, eenvoudige en informatieve gespreksonderwerpen voor:\n\n ##title## \n\nEn ook gelijkaardige gespreksonderwerpen voor tussenkopjes:\n ##keywords## \n\nDe toon van de alinea moet zijn:\n ##tone_language## \n\n'],

            ['id' => 111, 'template_id' => 4, 'key' => "et-EE", 'value' => 'Kirjutage lühikesed, lihtsad ja informatiivsed jutupunktid:\n\n ##title## \n\nJa ka sarnased jutupunktid alapealkirjade jaoks:\n ##keywords## \n\nLõigu hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 112, 'template_id' => 4, 'key' => "fi-FI", 'value' => 'Kirjoita lyhyitä, yksinkertaisia ja informatiivisia puheenaiheita:\n\n ##title## \n\nJa myös samanlaisia puheenaiheita alaotsikoille:\n ##keywords## \n\nKappaleen äänensävyn on oltava:\n ##tone_language## \n\n'],

            ['id' => 113, 'template_id' => 4, 'key' => "fr-FR", 'value' => 'Rédigez des points de discussion courts, simples et informatifs pour :\n\n ##title## \n\nEt également des points de discussion similaires pour les sous-titres :\n ##keywords## \n\nLe ton de la voix du paragraphe doit être :\n ##tone_language## \n\n'],

            ['id' => 114, 'template_id' => 4, 'key' => "de-DE", 'value' => 'Schreiben Sie kurze, einfache und informative Gesprächsthemen für:\n\n ##title## \n\nUnd auch ähnliche Gesprächsthemen für Unterüberschriften:\n ##keywords## \n\nTonlage des Absatzes muss sein:\n ##tone_language## \n\n'],

            ['id' => 115, 'template_id' => 4, 'key' => "el-GR", 'value' => 'Γράψτε σύντομα, απλά και κατατοπιστικά σημεία ομιλίας για:\n\n ##title## \n\nΚαι επίσης παρόμοια σημεία συζήτησης για υποτίτλους:\n ##keywords## \n\nΟ τόνος της φωνής της παραγράφου πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 116, 'template_id' => 4, 'key' => "he-IL", 'value' => 'כתוב נקודות דיבור קצרות, פשוטות ואינפורמטיביות עבור:\n\n ##title## \n\nוגם נקודות דיבור דומות עבור כותרות משנה:\n ##keywords## \n\nטון הדיבור של הפסקה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 117, 'template_id' => 4, 'key' => "hi-IN", 'value' => 'के लिए संक्षिप्त, सरल और जानकारीपूर्ण चर्चा बिंदु लिखें:\n\n ##title## \n\nऔर उपशीर्षक के लिए समान चर्चा बिंदु:\n ##keywords## \n\nपैराग्राफ की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n'],

            ['id' => 118, 'template_id' => 4, 'key' => "hu-HU", 'value' => 'Írjon rövid, egyszerű és informatív beszédpontokat:\n\n ##title## \n\nÉs hasonló beszédpontok az alcímekhez:\n ##keywords## \n\nA bekezdés hangszínének a következőnek kell lennie:\n ##tone_language## \n\n'],

            ['id' => 119, 'template_id' => 4, 'key' => "is-IS", 'value' => 'Skrifaðu stutta, einfalda og upplýsandi umræðupunkta fyrir:\n\n ##title## \n\nOg líka svipaðar umræður fyrir undirfyrirsagnir:\n ##keywords## \n\nTónn málsgreinarinnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 120, 'template_id' => 4, 'key' => "id-ID", 'value' => 'Tulis poin pembicaraan singkat, sederhana dan informatif untuk:\n\n ##title## \n\nDan juga poin pembicaraan serupa untuk subjudul:\n ##keywords## \n\nNada suara paragraf harus:\n ##tone_language## \n\n'],

            ['id' => 121, 'template_id' => 4, 'key' => "it-IT", 'value' => 'Scrivi punti di discussione brevi, semplici e informativi per:\n\n ##title## \n\nE anche punti di discussione simili per i sottotitoli:\n ##keywords## \n\nIl tono di voce del paragrafo deve essere:\n ##tone_language## \n\n'],

            ['id' => 122, 'template_id' => 4, 'key' => "ja-JP", 'value' => '短く、シンプルで有益な論点を書いてください:\n\n ##title## \n\n小見出しにも同様の要点があります:\n ##keywords## \n\n段落の口調は次のようにする必要があります:\n ##tone_language## \n\n'],

            ['id' => 123, 'template_id' => 4, 'key' => "ko-KR", 'value' => '다음에 대한 짧고 간단하며 유익한 요점을 작성하십시오:\n\n ##title## \n\n또한 부제목에 대한 유사한 논점:\n ##keywords## \n\n문단의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n'],

            ['id' => 124, 'template_id' => 4, 'key' => "ms-MY", 'value' => 'Tulis perkara perbualan yang pendek, ringkas dan bermaklumat untuk:\n\n ##title## \n\nDan juga perkara yang serupa untuk tajuk kecil:\n ##keywords## \n\nNada suara perenggan mestilah:\n ##tone_language## \n\n'],

            ['id' => 125, 'template_id' => 4, 'key' => "nb-NO", 'value' => 'Skriv korte, enkle og informative samtalepunkter for:\n\n ##title## \n\nOg også lignende samtalepunkter for underoverskrifter:\n ##keywords## \n\nTone i avsnittet må være:\n ##tone_language## \n\n'],

            ['id' => 126, 'template_id' => 4 , 'key' => "pl-PL", 'value' => 'Napisz krótkie, proste i pouczające przemówienia dla:\n\n ##title## \n\nA także podobne uwagi do podtytułów:\n ##keywords## \n\nTon głosu akapitu musi być:\n ##tone_language## \n\n'],

            ['id' => 127, 'template_id' => 4, 'key' => "pt-PT", 'value' => 'Escreva pontos de conversa curtos, simples e informativos para:\n\n ##title## \n\nE também pontos de discussão semelhantes para subtítulos:\n ##keywords## \n\nTom de voz do parágrafo deve ser:\n ##tone_language## \n\n'],

            ['id' => 128, 'template_id' => 4, 'key' => "ru-RU", 'value' => 'Напишите короткие, простые и информативные тезисы для:\n\n ##title## \n\nА также аналогичные темы для подзаголовков:\n ##keywords## \n\nТон абзаца должен быть:\n ##tone_language## \n\n'],

            ['id' => 129, 'template_id' => 4, 'key' => "es-ES", 'value' => 'Escribe puntos de conversación breves, sencillos e informativos para:\n\n ##title## \n\nY también puntos de conversación similares para los subtítulos:\n ##keywords## \n\nEl tono de voz del párrafo debe ser:\n ##tone_language## \n\n'],

            ['id' => 130, 'template_id' => 4, 'key' => "sv-SE", 'value' => 'Skriv korta, enkla och informativa samtalspunkter för:\n\n ##title## \n\nOch även liknande diskussionspunkter för underrubriker:\n ##keywords## \n\nTonfallet i stycket måste vara:\n ##tone_language## \n\n'],

            ['id' => 131, 'template_id' => 4, 'key' => "tr-TR", 'value' => 'Skriv korta, enkla och informativa samtalspunkter för:\n\n ##title## \n\nOch även liknande diskussionspunkter för underrubriker:\n ##keywords## \n\nTonfallet i stycket måste vara:\n ##tone_language## \n\n'],

            ['id' => 132, 'template_id' => 4, 'key' => "pt-BR", 'value' => 'Escreva pontos de conversa curtos, simples e informativos para:\n\n ##title## \n\nE também pontos de discussão semelhantes para subtítulos:\n ##keywords## \n\nTom de voz do parágrafo deve ser:\n ##tone_language## \n\n'],

            ['id' => 133, 'template_id' => 4, 'key' => "ro-RO", 'value' => 'Scrieți puncte de discuție scurte, simple și informative pentru:\n\n ##title## \n\nȘi, de asemenea, puncte de discuție similare pentru subtitluri:\n ##keywords## \n\nTonul vocii al paragrafului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 134, 'template_id' => 4, 'key' => "vi-VN", 'value' => 'Viết luận điểm ngắn gọn, đơn giản và nhiều thông tin cho:\n\n ##title## \n\nVà cả những luận điểm tương tự cho các tiêu đề phụ:\n ##keywords## \n\nGiọng điệu của đoạn phải là:\n ##tone_language## \n\n'],

            ['id' => 135, 'template_id' => 4, 'key' => "sw-KE", 'value' => 'Andika vidokezo vifupi, rahisi na vya kuelimisha vya:\n\n ##title## \n\nNa pia hoja sawa za mazungumzo kwa vichwa vidogo:\n ##keywords## \n\nToni ya sauti ya aya lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 136, 'template_id' => 4, 'key' => "sl-SI", 'value' => 'Napišite kratke, preproste in informativne teme za:\n\n ##title## \n\nIn tudi podobne teme za podnaslove:\n ##keywords## \n\nTon glasu odstavka mora biti:\n ##tone_language## \n\n'],

            ['id' => 137, 'template_id' => 4, 'key' => "th-TH", 'value' => 'เขียนประเด็นการพูดคุยที่สั้น เรียบง่าย และให้ข้อมูลสำหรับ:\n\n ##title## \n\nและประเด็นการพูดคุยที่คล้ายกันสำหรับหัวข้อย่อย:\n ##keywords## \n\nน้ำเสียงของย่อหน้าต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 138, 'template_id' => 4, 'key' => "uk-UA", 'value' => 'Напишіть короткі, прості та інформативні теми для:\n\n ##title## \n\nА також подібні теми для підзаголовків:\n ##keywords## \n\nТон абзацу має бути:\n ##tone_language## \n\n'],

            ['id' => 139, 'template_id' => 4, 'key' => "lt-LT", 'value' => 'Parašykite trumpus, paprastus ir informatyvius pokalbio taškus:\n\n ##title## \n\nIr taip pat panašių pokalbių temų paantraštėms:\n ##keywords## \n\nPastraipos balso tonas turi būti:\n ##tone_language## \n\n'],

            ['id' => 140, 'template_id' => 4, 'key' => "bg-BG", 'value' => 'Напишете кратки, прости и информативни точки за разговор за:\n\n ##title## \n\nИ също подобни теми за подзаглавия:\n ##keywords## \n\nТонът на гласа на абзаца трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 141, 'template_id' => 5, 'key' => "en-US", 'value' => 'Write pros and cons of these products:\n\n ##title## \n\nUse following product description:\n ##keywords## \n\nTone of voice of the pros and cons must be:\n ##tone_language## \n\n'],

            ['id' => 142, 'template_id' => 5, 'key' => "ar-AE", 'value' => 'اكتب إيجابيات وسلبيات هذه المنتجات:\n\n ##title## \n\nاستخدم وصف المنتج التالي:\n ##keywords## \n\nيجب أن تكون نغمة الإيجابيات والسلبيات:\n ##tone_language## \n\n'],

            ['id' => 143, 'template_id' => 5, 'key' => "cmn-CN", 'value' => '写下这些产品的优缺点：\n\n ##title## \n\n使用以下产品描述：\n ##keywords## \n\n正反的语气必须是：\n ##tone_language## \n\n'],

            ['id' => 144, 'template_id' => 5, 'key' => "hr-HR", 'value' => 'Napišite prednosti i nedostatke ovih proizvoda:\n\n ##title## \n\nKoristite sljedeći opis proizvoda:\n ##keywords## \n\nTon glasa za i protiv mora biti:\n ##tone_language## \n\n'],

            ['id' => 145, 'template_id' => 5, 'key' => "cs-CZ", 'value' => 'Napište výhody a nevýhody těchto produktů:\n\n ##title## \n\nPoužijte následující popis produktu:\n ##keywords## \n\nTón hlasu pro a proti musí být:\n ##tone_language## \n\n'],

            ['id' => 146, 'template_id' => 5, 'key' => "da-DK", 'value' => 'Skriv fordele og ulemper ved disse produkter:\n\n ##title## \n\nBrug følgende produktbeskrivelse:\n ##keywords## \n\nTone af fordele og ulemper skal være:\n ##tone_language## \n\n'],

            ['id' => 147, 'template_id' => 5, 'key' => "nl-NL", 'value' => 'Schrijf de voor- en nadelen van deze producten op:\n\n ##title## \n\nGebruik de volgende productbeschrijving:\n ##keywords## \n\nDe toon van de voor- en nadelen moet zijn:\n ##tone_language## \n\n'],

            ['id' => 148, 'template_id' => 5, 'key' => "et-EE", 'value' => 'Kirjutage nende toodete plussid ja miinused:\n\n ##title## \n\nKasutage järgmist tootekirjeldust:\n ##keywords## \n\nPusside ja miinuste hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 149, 'template_id' => 5, 'key' => "fi-FI", 'value' => 'Kirjoita näiden tuotteiden hyvät ja huonot puolet:\n\n ##title## \n\nKäytä seuraavaa tuotekuvausta:\n ##keywords## \n\nPussien ja haittojen äänensävyn on oltava:\n ##tone_language## \n\n'],

            ['id' => 150, 'template_id' => 5, 'key' => "fr-FR", 'value' => 'Écrivez les avantages et les inconvénients de ces produits :\n\n ##title## \n\nUtilisez la description de produit suivante :\n ##keywords## \n\nLe ton de la voix des pour et des contre doit être :\n ##tone_language## \n\n'],

            ['id' => 151, 'template_id' => 5, 'key' => "de-DE", 'value' => 'Schreiben Sie Vor- und Nachteile dieser Produkte auf:\n\n ##title## \n\nFolgende Produktbeschreibung verwenden:\n ##keywords## \n\nTonfall der Vor- und Nachteile muss sein:\n ##tone_language## \n\n'],

            ['id' => 152, 'template_id' => 5, 'key' => "el-GR", 'value' => 'Γράψτε τα πλεονεκτήματα και τα μειονεκτήματα αυτών των προϊόντων:\n\n ##title## \n\nΧρησιμοποιήστε την ακόλουθη περιγραφή προϊόντος:\n ##keywords## \n\nΟ τόνος της φωνής των πλεονεκτημάτων και των μειονεκτημάτων πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 153, 'template_id' => 5, 'key' => "he-IL", 'value' => 'כתוב יתרונות וחסרונות של המוצרים האלה:\n\n ##title## \n\nהשתמש בתיאור המוצר הבא:\n ##keywords## \n\nטון הדיבור של היתרונות והחסרונות חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 154, 'template_id' => 5, 'key' => "hi-IN", 'value' => 'इन उत्पादों के फायदे और नुकसान लिखें:\n\n ##title## \n\nनिम्न उत्पाद विवरण का उपयोग करें:\n ##keywords## \n\n पक्ष और विपक्ष की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n'],

            ['id' => 155, 'template_id' => 5, 'key' => "hu-HU", 'value' => 'Írja le ezeknek a termékeknek előnyeit és hátrányait:\n\n ##title## \n\nHasználja a következő termékleírást:\n ##keywords## \n\nAz előnyök és hátrányok hangnemének a következőnek kell lennie:\n ##tone_language## \n\n'],

            ['id' => 156, 'template_id' => 5, 'key' => "is-IS", 'value' => 'Skrifaðu kosti og galla þessara vara:\n\n ##title## \n\nNotaðu eftirfarandi vörulýsingu:\n ##keywords## \n\nTónn fyrir kosti og galla verður að vera:\n ##tone_language## \n\n'],

            ['id' => 157, 'template_id' => 5, 'key' => "id-ID", 'value' => 'Tulis pro dan kontra dari produk ini:\n\n ##title## \n\nGunakan deskripsi produk berikut:\n ##keywords## \n\nNada suara pro dan kontra harus:\n ##tone_language## \n\n'],

            ['id' => 158, 'template_id' => 5, 'key' => "it-IT", 'value' => 'Scrivi pro e contro di questi prodotti:\n\n ##title## \n\nUsa la seguente descrizione del prodotto:\n ##keywords## \n\nIl tono di voce dei pro e dei contro deve essere:\n ##tone_language## \n\n'],

            ['id' => 159, 'template_id' => 5, 'key' => "ja-JP", 'value' => 'これらの製品の長所と短所を書いてください:\n\n ##title## \n\n次の製品説明を使用してください:\n ##keywords## \n\n賛成派と反対派の口調は次のとおりでなければなりません:\n ##tone_language## \n\n'],

            ['id' => 160, 'template_id' => 5, 'key' => "ko-KR", 'value' => '이 제품의 장단점을 작성하십시오:\n\n ##title## \n\n다음 제품 설명을 사용하십시오:\n ##keywords## \n\n장단점의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n'],

            ['id' => 161, 'template_id' => 5, 'key' => "ms-MY", 'value' => 'Tulis kebaikan dan keburukan produk ini:\n\n ##title## \n\nGunakan penerangan produk berikut:\n ##keywords## \n\nNada suara kebaikan dan keburukan mestilah:\n ##tone_language## \n\n'],

            ['id' => 162, 'template_id' => 5, 'key' => "nb-NO", 'value' => 'Skriv fordeler og ulemper med disse produktene:\n\n ##title## \n\nBruk følgende produktbeskrivelse:\n ##keywords## \n\nTone for fordeler og ulemper må være:\n ##tone_language## \n\n'],

            ['id' => 163, 'template_id' => 5 , 'key' => "pl-PL", 'value' => 'Napisz wady i zalety tych produktów:\n\n ##title## \n\nUżyj następującego opisu produktu:\n ##keywords## \n\nTon głosu za i przeciw musi być następujący:\n ##tone_language## \n\n'],

            ['id' => 164, 'template_id' => 5, 'key' => "pt-PT", 'value' => 'Escreva prós e contras destes produtos:\n\n ##title## \n\nUse a seguinte descrição do produto:\n ##keywords## \n\nTom de voz dos prós e contras deve ser:\n ##tone_language## \n\n'],

            ['id' => 165, 'template_id' => 5, 'key' => "ru-RU", 'value' => 'Напишите плюсы и минусы этих продуктов:\n\n ##title## \n\nИспользуйте следующее описание продукта:\n ##keywords## \n\nТон озвучивания плюсов и минусов должен быть:\n ##tone_language## \n\n'],

            ['id' => 166, 'template_id' => 5, 'key' => "es-ES", 'value' => 'Escriba pros y contras de estos productos:\n\n ##title## \n\nUtilice la siguiente descripción del producto:\n ##keywords## \n\nEl tono de voz de los pros y los contras debe ser:\n ##tone_language## \n\n'],

            ['id' => 167, 'template_id' => 5, 'key' => "sv-SE", 'value' => 'Skriv för- och nackdelar med dessa produkter:\n\n ##title## \n\nAnvänd följande produktbeskrivning:\n ##keywords## \n\nTonfall för för- och nackdelar måste vara:\n ##tone_language## \n\n'],

            ['id' => 168, 'template_id' => 5, 'key' => "tr-TR", 'value' => 'Bu ürünlerin artılarını ve eksilerini yazın:\n\n ##title## \n\nAşağıdaki ürün açıklamasını kullanın:\n ##keywords## \n\nSes tonu artıları ve eksileri şöyle olmalıdır:\n ##tone_language## \n\n'],

            ['id' => 169, 'template_id' => 5, 'key' => "pt-BR", 'value' => 'Escreva prós e contras destes produtos:\n\n ##title## \n\nUse a seguinte descrição do produto:\n ##keywords## \n\nTom de voz dos prós e contras deve ser:\n ##tone_language## \n\n'],

            ['id' => 170, 'template_id' => 5, 'key' => "ro-RO", 'value' => 'Scrieți argumentele pro și contra acestor produse:\n\n ##title## \n\nUtilizați următoarea descriere a produsului:\n ##keywords## \n\nTonul vocii argumentelor pro și contra trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 171, 'template_id' => 5, 'key' => "vi-VN", 'value' => 'Viết ưu và nhược điểm của những sản phẩm này:\n\n ##title## \n\nSử dụng mô tả sản phẩm sau:\n ##keywords## \n\nGiọng điệu của những ưu và nhược điểm phải là:\n ##tone_language## \n\n'],

            ['id' => 172, 'template_id' => 5, 'key' => "sw-KE", 'value' => 'Andika faida na hasara za bidhaa hizi:\n\n ##title## \n\nTumia maelezo ya bidhaa yafuatayo:\n ##keywords## \n\nToni ya sauti ya faida na hasara lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 173, 'template_id' => 5, 'key' => "sl-SI", 'value' => 'Napišite prednosti in slabosti teh izdelkov:\n\n ##title## \n\nUporabi naslednji opis izdelka:\n ##keywords## \n\nTon glasu prednosti in slabosti mora biti:\n ##tone_language## \n\n'],

            ['id' => 174, 'template_id' => 5, 'key' => "th-TH", 'value' => 'เขียนข้อดีข้อเสียของผลิตภัณฑ์เหล่านี้:\n\n ##title## \n\nใช้คำอธิบายผลิตภัณฑ์ต่อไปนี้:\n ##keywords## \n\nเสียงของข้อดีและข้อเสียต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 175, 'template_id' => 5, 'key' => "uk-UA", 'value' => 'Напишіть плюси та мінуси цих продуктів:\n\n ##title## \n\nВикористовуйте такий опис продукту:\n ##keywords## \n\nТон голосу плюсів і мінусів має бути:\n ##tone_language## \n\n'],

            ['id' => 176, 'template_id' => 5, 'key' => "lt-LT", 'value' => 'Parašykite šių produktų privalumus ir trūkumus:\n\n ##title## \n\nNaudokite šį produkto aprašymą:\n ##keywords## \n\nPrivalumai ir trūkumai turi būti tokie:\n ##tone_language## \n\n'],

            ['id' => 177, 'template_id' => 5, 'key' => "bg-BG", 'value' => 'Напишете плюсовете и минусите на тези продукти:\n\n ##title## \n\nИзползвайте следното описание на продукта:\n ##keywords## \n\nТонът на гласа на плюсовете и минусите трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 178, 'template_id' => 6, 'key' => "en-US", 'value' => 'Generate 10 catchy blog titles for:\n\n ##description## \n\n'],

            ['id' => 179, 'template_id' => 6, 'key' => "ar-AE", 'value' => 'قم بإنشاء 10 عناوين مدونة جذابة لـ:\n\n ##description## \n\n'],

            ['id' => 180, 'template_id' => 6, 'key' => "cmn-CN", 'value' => '为以下内容生成 10 个吸引人的博客标题：\n\n ##description## \n\n'],

            ['id' => 181, 'template_id' => 6, 'key' => "hr-HR", 'value' => 'Generiraj 10 privlačnih naslova bloga za:\n\n ##description## \n\n'],

            ['id' => 182, 'template_id' => 6, 'key' => "cs-CZ", 'value' => 'Vygenerujte 10 chytlavých názvů blogů pro:\n\n ##description## \n\n'],

            ['id' => 183, 'template_id' => 6, 'key' => "da-DK", 'value' => 'Generer 10 fængende blogtitler til:\n\n ##description## \n\n'],

            ['id' => 184, 'template_id' => 6, 'key' => "nl-NL", 'value' => 'Genereer 10 pakkende blogtitels voor:\n\n ##description## \n\n'],

            ['id' => 185, 'template_id' => 6, 'key' => "et-EE", 'value' => 'Loo 10 meeldejäävat ajaveebi pealkirja:\n\n ##description## \n\n'],

            ['id' => 186, 'template_id' => 6, 'key' => "fi-FI", 'value' => 'Luo 10 tarttuvaa blogiotsikkoa:\n\n ##description## \n\n'],

            ['id' => 187, 'template_id' => 6, 'key' => "fr-FR", 'value' => 'Générez 10 titres de blog accrocheurs pour :\n\n ##description## \n\n'],

            ['id' => 188, 'template_id' => 6, 'key' => "de-DE", 'value' => 'Generiere 10 einprägsame Blog-Titel für:\n\n ##description## \n\n'],

            ['id' => 189, 'template_id' => 6, 'key' => "el-GR", 'value' => 'Δημιουργήστε 10 εντυπωσιακούς τίτλους ιστολογίου για:\n\n ##description## \n\n'],

            ['id' => 190, 'template_id' => 6, 'key' => "he-IL", 'value' => 'צור 10 כותרות בלוג קליטות עבור:\n\n ##description## \n\n'],

            ['id' => 191, 'template_id' => 6, 'key' => "hi-IN", 'value' => '10 आकर्षक ब्लॉग शीर्षक उत्पन्न करें:\n\n ##description## \n\n'],

            ['id' => 192, 'template_id' => 6, 'key' => "hu-HU", 'value' => 'Generálj 10 fülbemászó blogcímet a következőhöz:\n\n ##description## \n\n'],

            ['id' => 193, 'template_id' => 6, 'key' => "is-IS", 'value' => 'Búðu til 10 grípandi bloggtitla fyrir:\n\n ##description## \n\n'],

            ['id' => 194, 'template_id' => 6, 'key' => "id-ID", 'value' => 'Hasilkan 10 judul blog menarik untuk:\n\n ##description## \n\n'],

            ['id' => 195, 'template_id' => 6, 'key' => "it-IT", 'value' => 'Genera 10 titoli di blog accattivanti per:\n\n ##description## \n\n'],

            ['id' => 196, 'template_id' => 6, 'key' => "ja-JP", 'value' => 'キャッチーなブログ タイトルを 10 個生成します:\n\n ##description## \n\n'],

            ['id' => 197, 'template_id' => 6, 'key' => "ko-KR", 'value' => '다음에 대한 10개의 눈길을 끄는 블로그 제목 생성:\n\n ##description## \n\n'],

            ['id' => 198, 'template_id' => 6, 'key' => "ms-MY", 'value' => 'Jana 10 tajuk blog yang menarik untuk:\n\n ##description## \n\n'],

            ['id' => 199, 'template_id' => 6, 'key' => "nb-NO", 'value' => 'Generer 10 fengende bloggtitler for:\n\n ##description## \n\n'],

            ['id' => 200, 'template_id' => 6, 'key' => "pl-PL", 'value' => 'Wygeneruj 10 chwytliwych tytułów blogów dla:\n\n ##description## \n\n'],

            ['id' => 201, 'template_id' => 6, 'key' => "pt-PT", 'value' => 'Gerar 10 títulos de blog cativantes para:\n\n ##description## \n\n'],

            ['id' => 202, 'template_id' => 6, 'key' => "ru-RU", 'value' => 'Создайте 10 броских заголовков блога для:\n\n ##description## \п\п'],

            ['id' => 203, 'template_id' => 6, 'key' => "es-ES", 'value' => 'Generar 10 títulos de blog pegadizos para:\n\n ##description## \n\n'],

            ['id' => 204, 'template_id' => 6, 'key' => "sv-SE", 'value' => 'Generera 10 catchy bloggtitlar för:\n\n ##description## \n\n'],

            ['id' => 205, 'template_id' => 6, 'key' => "tr-TR", 'value' => '10 akılda kalıcı blog başlığı oluşturun:\n\n ##description## \n\n'],

            ['id' => 206, 'template_id' => 6, 'key' => "pt-BR", 'value' => 'Gerar 10 títulos de blog cativantes para:\n\n ##description## \n\n'],

            ['id' => 207, 'template_id' => 6, 'key' => "ro-RO", 'value' => 'Generează 10 titluri de blog atrăgătoare pentru:\n\n ##description## \n\n'],

            ['id' => 208, 'template_id' => 6, 'key' => "vi-VN", 'value' => 'Tạo 10 tiêu đề blog hấp dẫn cho:\n\n ##description## \n\n'],

            ['id' => 209, 'template_id' => 6, 'key' => "sw-KE", 'value' => 'Zalisha vichwa 10 vya blogu vya kuvutia vya:\n\n ##description## \n\n'],

            ['id' => 210, 'template_id' => 6, 'key' => "sl-SI", 'value' => 'Ustvari 10 privlačnih naslovov blogov za:\n\n ##description## \n\n'],

            ['id' => 211, 'template_id' => 6, 'key' => "th-TH", 'value' => 'สร้างชื่อบล็อกที่ดึงดูดใจ 10 ชื่อสำหรับ:\n\n ##description## \n\n'],

            ['id' => 212, 'template_id' => 6, 'key' => "uk-UA", 'value' => 'Створіть 10 привабливих назв блогу для:\n\n ##description## \n\n'],

            ['id' => 213, 'template_id' => 6, 'key' => "lt-LT", 'value' => 'Sukurkite 10 patrauklių tinklaraščio pavadinimų:\n\n ##description## \n\n'],

            ['id' => 214, 'template_id' => 6, 'key' => "bg-BG", 'value' => 'Генерирайте 10 закачливи заглавия на блогове за:\n\n ##description## \n\n'],

            ['id' => 215, 'template_id' => 7, 'key' => "en-US", 'value' => 'Write a full blog section with at least 5 large paragraphs about:\n\n ##title## \n\nSplit by subheadings:\n ##subheadings## \n\nTone of voice of the paragraphs must be:\n ##tone_language## \n\n'],

            ['id' => 216, 'template_id' => 7, 'key' => "ar-AE", 'value' => 'اكتب قسم مدونة كاملًا يحتوي على 5 فقرات كبيرة على الأقل حول:\n\n ##title## \n\nانقسام حسب العناوين الفرعية:\n ##subheadings## \n\nيجب أن تكون نغمة صوت الفقرات:\n ##tone_language## \n\n'],

            ['id' => 217, 'template_id' => 7, 'key' => "cmn-CN", 'value' => '写一个完整的博客部分，其中至少包含 5 个大段落：\n\n ##title## \n\n按副标题拆分：\n ##subheadings## \n\n段落的语气必须是：\n ##tone_language## \n\n'],

            ['id' => 218, 'template_id' => 7, 'key' => "hr-HR", 'value' => 'Napišite cijeli odjeljak bloga s najmanje 5 velikih odlomaka o:\n\n ##title## \n\nPodijeli po podnaslovima:\n ##subheadings## \n\nTon glasa odlomaka mora biti:\n ##tone_language## \n\n'],

            ['id' => 219, 'template_id' => 7, 'key' => "cs-CZ", 'value' => 'Napište celou sekci blogu s alespoň 5 velkými odstavci o:\n\n ##title## \n\nRozdělit podle podnadpisů:\n ##subheadings## \n\nTón hlasu odstavců musí být:\n ##tone_language## \n\n'],

            ['id' => 220, 'template_id' => 7, 'key' => "da-DK", 'value' => 'Skriv en komplet blogsektion med mindst 5 store afsnit om:\n\n ##title## \n\nOpdelt efter underoverskrifter:\n ##subheadings## \n\nTonefaldet i afsnittene skal være:\n ##tone_language## \n\n'],

            ['id' => 221, 'template_id' => 7, 'key' => "nl-NL", 'value' => 'Schrijf een volledig bloggedeelte met minimaal 5 grote paragrafen over:\n\n ##title## \n\nGesplitst door subkoppen:\n ##subheadings## \n\nDe toon van de alinea`s moet zijn:\n ##tone_language## \n\n'],

            ['id' => 222, 'template_id' => 7, 'key' => "et-EE", 'value' => 'Kirjutage terve blogijaotis vähemalt 5 suure lõiguga teemal:\n\n ##title## \n\nJagatud alampealkirjade järgi:\n ##subheadings## \n\nLõigete hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 223, 'template_id' => 7, 'key' => "fi-FI", 'value' => 'Kirjoita koko blogiosio, jossa on vähintään 5 suurta kappaletta aiheesta:\n\n ##title## \n\nJaettu alaotsikoiden mukaan:\n ##subheadings## \n\nKappaleiden äänensävyn on oltava:\n ##tone_language## \n\n'],

            ['id' => 224, 'template_id' => 7, 'key' => "fr-FR", 'value' => 'Écrivez une section de blog complète avec au moins 5 grands paragraphes sur :\n\n ##title## \n\nDiviser par sous-titres :\n ##subheadings## \n\nLe ton de la voix des paragraphes doit être :\n ##tone_language## \n\n'],

            ['id' => 225, 'template_id' => 7, 'key' => "de-DE", 'value' => 'Schreiben Sie einen vollständigen Blog-Abschnitt mit mindestens 5 großen Absätzen über:\n\n ##title## \n\nAufgeteilt nach Unterüberschriften:\n ##subheadings## \n\nTonfall der Absätze muss sein:\n ##tone_language## \n\n'],

            ['id' => 226, 'template_id' => 7, 'key' => "el-GR", 'value' => 'Γράψτε μια πλήρη ενότητα ιστολογίου με τουλάχιστον 5 μεγάλες παραγράφους σχετικά με:\n\n ##title## \n\nΔιαίρεση κατά υποτίτλους:\n ##subheadings## \n\nΟ τόνος της φωνής των παραγράφων πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 227, 'template_id' => 7, 'key' => "he-IL", 'value' => 'כתוב מדור בלוג מלא עם לפחות 5 פסקאות גדולות על:\n\n ##title## \n\nפיצול לפי כותרות משנה:\n ##subheadings## \n\nטון הדיבור של הפסקאות חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 228, 'template_id' => 7, 'key' => "hi-IN", 'value' => 'इस बारे में कम से कम 5 बड़े अनुच्छेदों के साथ एक पूर्ण ब्लॉग अनुभाग लिखें:\n\n ##title## \n\nउपशीर्षकों द्वारा विभाजित करें:\n ##subheadings## \n\nपैराग्राफ की आवाज का स्वर होना चाहिए:\n ##tone_language## \n\n'],

            ['id' => 229, 'template_id' => 7, 'key' => "hu-HU", 'value' => 'Írjon egy teljes blogrészt, legalább 5 nagy bekezdéssel erről:\n\n ##title## \n\nAlcímek szerint felosztva:\n ##subheadings## \n\nA bekezdések hangnemének a következőnek kell lennie:\n ##tone_language## \n\n'],

            ['id' => 230, 'template_id' => 7, 'key' => "is-IS", 'value' => 'Skrifaðu heilan blogghluta með að minnsta kosti 5 stórum málsgreinum um:\n\n ##title## \n\nDeilt eftir undirfyrirsögnum:\n ##subheadings## \n\nTónn málsgreina verður að vera:\n ##tone_language## \n\n'],

            ['id' => 231, 'template_id' => 7, 'key' => "id-ID", 'value' => 'Tulis bagian blog lengkap dengan setidaknya 5 paragraf besar tentang:\n\n ##title## \n\nDibagi berdasarkan subjudul:\n ##subheadings## \n\nNada suara paragraf harus:\n ##tone_language## \n\n'],

            ['id' => 232, 'template_id' => 7, 'key' => "it-IT", 'value' => 'Scrivi una sezione completa del blog con almeno 5 paragrafi di grandi dimensioni su:\n\n ##title## \n\nDiviso per sottotitoli:\n ##subheadings## \n\nIl tono di voce dei paragrafi deve essere:\n ##tone_language## \n\n'],

            ['id' => 233, 'template_id' => 7, 'key' => "ja-JP", 'value' => '次の内容について、少なくとも 5 つの大きな段落を含む完全なブログ セクションを作成します:\n\n ##title## \n\n小見出しで分割:\n ##subheadings## \n\n段落の口調は次のようにする必要があります:\n ##tone_language## \n\n'],

            ['id' => 234, 'template_id' => 7, 'key' => "ko-KR", 'value' => '다음에 대해 최소 5개의 큰 단락으로 전체 블로그 섹션을 작성하세요:\n\n ##title## \n\n하위 제목으로 분할:\n ##subheadings## \n\n문단의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n'],

            ['id' => 235, 'template_id' => 7, 'key' => "ms-MY", 'value' => 'Tulis bahagian blog penuh dengan sekurang-kurangnya 5 perenggan besar tentang:\n\n ##title## \n\nPisah mengikut subtajuk:\n ##subheadings## \n\nNada suara perenggan mestilah:\n ##tone_language## \n\n'],

            ['id' => 236, 'template_id' => 7, 'key' => "nb-NO", 'value' => 'Skriv en fullstendig bloggseksjon med minst 5 store avsnitt om:\n\n ##title## \n\nSplitt etter underoverskrifter:\n ##subheadings## \n\nTone i avsnittene må være:\n ##tone_language## \n\n'],

            ['id' => 237, 'template_id' => 7 , 'key' => "pl-PL", 'value' => 'Napisz pełną sekcję bloga zawierającą co najmniej 5 dużych akapitów na temat:\n\n ##title## \n\nPodział według podtytułów:\n ##subheadings## \n\nTon głosu akapitów musi być:\n ##tone_language## \n\n'],

            ['id' => 238, 'template_id' => 7, 'key' => "pt-PT", 'value' => 'Escreva uma seção de blog completa com pelo menos 5 parágrafos grandes sobre:\n\n ##title## \n\nDivisão por subtítulos:\n ##subheadings## \n\nTom de voz dos parágrafos deve ser:\n ##tone_language## \n\n'],

            ['id' => 239, 'template_id' => 7, 'key' => "ru-RU", 'value' => 'Напишите полный раздел блога, содержащий не менее 5 больших абзацев о:\n\n ##title## \n\nРазделить по подзаголовкам:\n ##subheadings## \n\nТон озвучивания абзацев должен быть:\n ##tone_language## \n\n'],

            ['id' => 240, 'template_id' => 7, 'key' => "es-ES", 'value' => 'Escribe una sección de blog completa con al menos 5 párrafos extensos sobre:\n\n ##title## \n\nDividir por subtítulos:\n ##subheadings## \n\nEl tono de voz de los párrafos debe ser:\n ##tone_language## \n\n'],

            ['id' => 241, 'template_id' => 7, 'key' => "sv-SE", 'value' => 'Skriv en fullständig bloggsektion med minst 5 stora stycken om:\n\n ##title## \n\nDela upp efter underrubriker:\n ##subheadings## \n\nTonfallet i styckena måste vara:\n ##tone_language## \n\n'],

            ['id' => 242, 'template_id' => 7, 'key' => "tr-TR", 'value' => 'Şununla ilgili en az 5 büyük paragraf içeren eksiksiz bir blog bölümü yazın:\n\n ##title## \n\nAlt başlıklara göre ayır:\n ##subheadings## \n\nParagrafların ses tonu şöyle olmalıdır:\n ##tone_language## \n\n'],

            ['id' => 243, 'template_id' => 7, 'key' => "pt-BR", 'value' => 'Escreva uma seção de blog completa com pelo menos 5 parágrafos grandes sobre:\n\n ##title## \n\nDivisão por subtítulos:\n ##subheadings## \n\nTom de voz dos parágrafos deve ser:\n ##tone_language## \n\n'],

            ['id' => 244, 'template_id' => 7, 'key' => "ro-RO", 'value' => 'Scrieți o secțiune completă de blog cu cel puțin 5 paragrafe mari despre:\n\n ##title## \n\nDivizat după subtitluri:\n ##subheadings## \n\nTonul de voce al paragrafelor trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 245, 'template_id' => 7, 'key' => "vi-VN", 'value' => 'Viết một mục blog đầy đủ với ít nhất 5 đoạn văn lớn về:\n\n ##title## \n\nChia theo tiêu đề phụ:\n ##subheadings## \n\nGiọng điệu của đoạn văn phải là:\n ##tone_language## \n\n'],

            ['id' => 246, 'template_id' => 7, 'key' => "sw-KE", 'value' => 'Andika sehemu kamili ya blogu iliyo na angalau aya 5 kubwa kuhusu:\n\n ##title## \n\nGawanya kwa vichwa vidogo:\n ##subheadings## \n\nToni ya sauti ya aya lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 247, 'template_id' => 7, 'key' => "sl-SI", 'value' => 'Napišite celoten razdelek spletnega dnevnika z vsaj 5 velikimi odstavki o:\n\n ##title## \n\nRazdeljeno po podnaslovih:\n ##subheadings## \n\nTon glasu odstavkov mora biti:\n ##tone_language## \n\n'],

            ['id' => 248, 'template_id' => 7, 'key' => "th-TH", 'value' => 'เขียนบล็อกแบบเต็มโดยมีย่อหน้าใหญ่อย่างน้อย 5 ย่อหน้าเกี่ยวกับ:\n\n ##title## \n\nแบ่งตามหัวข้อย่อย:\n ##subheadings## \n\nเสียงของย่อหน้าต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 249, 'template_id' => 7, 'key' => "uk-UA", 'value' => 'Напишіть повний розділ блогу, щонайменше 5 великих абзаців про:\n\n ##title## \n\nРозділити за підзаголовками:\n ##subheadings## \n\nТон абзаців має бути:\n ##tone_language## \n\n'],

            ['id' => 250, 'template_id' => 7, 'key' => "lt-LT", 'value' => 'Parašykite visą tinklaraščio skyrių su bent 5 didelėmis pastraipomis apie:\n\n ##title## \n\nPadalinta pagal paantraštes:\n ##subheadings## \n\nPastraipų balso tonas turi būti:\n ##tone_language## \n\n'],

            ['id' => 251, 'template_id' => 7, 'key' => "bg-BG", 'value' => 'Напишете цял блог с поне 5 големи абзаца за:\n\n ##title## \n\nРазделени по подзаглавия:\n ##subheadings## \n\nТонът на гласа на параграфите трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 252, 'template_id' => 8, 'key' => "en-US", 'value' => '"Write interesting blog ideas and outline about:\n\n ##title## \n\n Tone of voice of the ideas must be:\n ##tone_language## \n\n'],

            ['id' => 253, 'template_id' => 8, 'key' => "ar-AE", 'value' => 'اكتب أفكار مدونة ممتعة وحدد مخططًا تفصيليًا حول:\n\n ##title## \n\nيجب أن تكون نبرة صوت الأفكار:\n ##tone_language## \n\n'],

            ['id' => 254, 'template_id' => 8, 'key' => "cmn-CN", 'value' => '写下有趣的博客想法和大纲：\n\n ##title## \n\n 想法的语气必须是：\n ##tone_language## \n\n'],

            ['id' => 255, 'template_id' => 8, 'key' => "hr-HR", 'value' => 'Napišite zanimljive ideje za blog i skicirajte o:\n\n ##title## \n\n Ton glasa ideja mora biti:\n ##tone_language## \n\n'],

            ['id' => 256, 'template_id' => 8, 'key' => "cs-CZ", 'value' => 'Pište zajímavé nápady na blog a přehled o:\n\n ##title## \n\n Tón hlasu nápadů musí být:\n ##tone_language## \n\n'],

            ['id' => 257, 'template_id' => 8, 'key' => "da-DK", 'value' => 'Skriv interessante blogideer og skitser om:\n\n ##title## \n\n Tonen i ideerne skal være:\n ##tone_language## \n\n'],

            ['id' => 258, 'template_id' => 8, 'key' => "nl-NL", 'value' => 'Schrijf interessante blogideeën en schets over:\n\n ##title## \n\n De toon van de ideeën moet zijn:\n ##tone_language## \n\n'],

            ['id' => 259, 'template_id' => 8, 'key' => "et-EE", 'value' => 'Kirjutage huvitavaid ajaveebi ideid ja kirjeldage:\n\n ##title## \n\n Ideede hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 260, 'template_id' => 8, 'key' => "fi-FI", 'value' => 'Kirjoita mielenkiintoisia blogiideoita ja hahmotelkaa aiheesta:\n\n ##title## \n\n Ideoiden äänensävyn tulee olla:\n ##tone_language## \n\n'],

            ['id' => 261, 'template_id' => 8, 'key' => "fr-FR", 'value' => 'Rédigez des idées de blog intéressantes et décrivez :\n\n ##title## \n\n Le ton de la voix des idées doit être :\n ##tone_language## \n\n'],

            ['id' => 262, 'template_id' => 8, 'key' => "de-DE", 'value' => 'Schreiben Sie interessante Blog-Ideen und skizzieren Sie über:\n\n ##title## \n\n Tonfall der Ideen muss sein:\n ##tone_language## \n\n'],

            ['id' => 263, 'template_id' => 8, 'key' => "el-GR", 'value' => 'Γράψτε ενδιαφέρουσες ιδέες ιστολογίου και περιγράψτε τα σχετικά:\n\n ##title## \n\n Ο τόνος της φωνής των ιδεών πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 264, 'template_id' => 8, 'key' => "he-IL", 'value' => 'כתוב רעיונות מעניינים לבלוג ותאר את:\n\n ##title## \n\n טון הדיבור של הרעיונות חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 265, 'template_id' => 8, 'key' => "hi-IN", 'value' => 'दिलचस्प ब्लॉग विचार लिखें और इसके बारे में रूपरेखा लिखें:\n\n ##title## \n\n विचारों का स्वर होना चाहिए:\n ##tone_language## \n\n'],

            ['id' => 266, 'template_id' => 8, 'key' => "hu-HU", 'value' => 'Írjon érdekes blogötleteket és vázlatot erről:\n\n ##title## \n\n Az ötletek hangnemének a következőnek kell lennie:\n ##tone_language## \n\n'],

            ['id' => 267, 'template_id' => 8, 'key' => "is-IS", 'value' => 'Skrifaðu áhugaverðar blogghugmyndir og gerðu grein fyrir:\n\n ##title## \n\n Rödd hugmyndanna verður að vera:\n ##tone_language## \n\n'],

            ['id' => 268, 'template_id' => 8, 'key' => "id-ID", 'value' => 'Tulis ide blog yang menarik dan uraikan tentang:\n\n ##title## \n\n Nada suara ide harus:\n ##tone_language## \n\n'],

            ['id' => 269, 'template_id' => 8, 'key' => "it-IT", 'value' => 'Scrivi interessanti idee per il blog e delinea su:\n\n ##title## \n\n Il tono di voce delle idee deve essere:\n ##tone_language## \n\n'],

            ['id' => 270, 'template_id' => 8, 'key' => "ja-JP", 'value' => '興味深いブログのアイデアと概要を書きます:\n\n ##title## \n\n アイデアの口調は次のとおりでなければなりません:\n ##tone_language## \n\n'],

            ['id' => 271, 'template_id' => 8, 'key' => "ko-KR", 'value' => '흥미로운 블로그 아이디어를 작성하고 다음에 대한 개요를 작성하세요:\n\n ##title## \n\n 아이디어의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n'],

            ['id' => 272, 'template_id' => 8, 'key' => "ms-MY", 'value' => 'Tulis idea blog yang menarik dan gariskan tentang:\n\n ##title## \n\n Nada suara idea mestilah:\n ##tone_language## \n\n'],

            ['id' => 273, 'template_id' => 8, 'key' => "nb-NO", 'value' => 'Skriv interessante bloggideer og skisser om:\n\n ##title## \n\n Tonen til ideene må være:\n ##tone_language## \n\n'],

            ['id' => 274, 'template_id' => 8 , 'key' => "pl-PL", 'value' => 'Napisz ciekawe pomysły na bloga i zarys tematu:\n\n ##title## \n\n Ton głosu pomysłów musi być:\n ##tone_language## \n\n'],

            ['id' => 275, 'template_id' => 8, 'key' => "pt-PT", 'value' => 'Escreva ideias de blog interessantes e descreva sobre:\n\n ##title## \n\n Tom de voz das ideias deve ser:\n ##tone_language## \n\n'],

            ['id' => 276, 'template_id' => 8, 'key' => "ru-RU", 'value' => 'Напишите интересные идеи для блога и расскажите о:\n\n ##title## \n\n Тон голоса идей должен быть:\n ##tone_language## \n\n'],

            ['id' => 277, 'template_id' => 8, 'key' => "es-ES", 'value' => 'Escriba ideas de blog interesantes y esboce sobre:\n\n ##title## \n\n El tono de voz de las ideas debe ser:\n ##tone_language## \n\n'],

            ['id' => 278, 'template_id' => 8, 'key' => "sv-SE", 'value' => 'Skriv intressanta bloggidéer och beskriv:\n\n ##title## \n\n Tonen i idéerna måste vara:\n ##tone_language## \n\n'],

            ['id' => 279, 'template_id' => 8, 'key' => "tr-TR", 'value' => 'İlginç blog fikirleri yazın ve hakkında ana hatları çizin:\n\n ##title## \n\n Fikirlerin ses tonu şöyle olmalıdır:\n ##tone_language## \n\n'],

            ['id' => 280, 'template_id' => 8, 'key' => "pt-BR", 'value' => 'Escreva ideias de blog interessantes e descreva sobre:\n\n ##title## \n\n Tom de voz das ideias deve ser:\n ##tone_language## \n\n'],

            ['id' => 281, 'template_id' => 8, 'key' => "ro-RO", 'value' => 'Scrie idei interesante de blog și schiță despre:\n\n ##title## \n\n Tonul vocii ideilor trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 282, 'template_id' => 8, 'key' => "vi-VN", 'value' => 'Viết ý tưởng blog thú vị và phác thảo về:\n\n ##title## \n\n Giọng điệu của các ý tưởng phải là:\n ##tone_language## \n\n'],

            ['id' => 283, 'template_id' => 8, 'key' => "sw-KE", 'value' => 'Andika mawazo ya kuvutia ya blogu na muhtasari kuhusu:\n\n ##title## \n\n Toni ya sauti ya mawazo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 284, 'template_id' => 8, 'key' => "sl-SI", 'value' => 'Napišite zanimive ideje za blog in orišite o:\n\n ##title## \n\n Ton glasu idej mora biti:\n ##tone_language## \n\n'],

            ['id' => 285, 'template_id' => 8, 'key' => "th-TH", 'value' => 'เขียนแนวคิดและโครงร่างบล็อกที่น่าสนใจเกี่ยวกับ:\n\n ##title## \n\n น้ำเสียงของความคิดต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 286, 'template_id' => 8, 'key' => "uk-UA", 'value' => 'Напишіть цікаві ідеї для блогу та окресліть:\n\n ##title## \n\n Тон голосу ідей має бути:\n ##tone_language## \n\n'],

            ['id' => 287, 'template_id' => 8, 'key' => "lt-LT", 'value' => 'Parašykite įdomių tinklaraščio idėjų ir apibūdinkite apie:\n\n ##title## \n\n Idėjų balso tonas turi būti:\n ##tone_language## \n\n'],

            ['id' =>288, 'template_id' => 8, 'key' => "bg-BG", 'value' => 'Напишете интересни идеи за блог и очертайте:\n\n ##title## \n\n Тонът на гласа на идеите трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 289, 'template_id' => 9, 'key' => "en-US", 'value' => 'Write an interesting blog post intro about:\n\n ##description## \n\n Blog post title:\n ##title## \n\nTone of voice of the blog intro must be:\n ##tone_language## \n\n'],

            ['id' => 290, 'template_id' => 9, 'key' => "ar-AE", 'value' => 'اكتب مقدمة مدونة شيقة عن:\n\n ##description## \n\n عنوان منشور المدونة:\n ##title## \n\nيجب أن تكون نغمة الصوت في مقدمة المدونة:\n ##tone_language## \n\n'],

            ['id' => 291, 'template_id' => 9, 'key' => "cmn-CN", 'value' => '写一篇有趣的博客文章介绍：\n\n ##description## \n\n 博文标题：\n ##title## \n\n博客介绍的语气必须是：\n ##tone_language## \n\n'],

            ['id' => 292, 'template_id' => 9, 'key' => "hr-HR", 'value' => 'Napišite uvod u zanimljiv blog post o:\n\n ##description## \n\n Naslov posta na blogu:\n ##title## \n\nTon glasa uvoda u blog mora biti:\n ##tone_language## \n\n'],

            ['id' => 293, 'template_id' => 9, 'key' => "cs-CZ", 'value' => 'Napište zajímavý úvod do blogového příspěvku o:\n\n ##description## \n\n Název příspěvku na blogu:\n ##title## \n\nTón hlasu úvodu blogu musí být:\n ##tone_language## \n\n'],

            ['id' => 294, 'template_id' => 9, 'key' => "da-DK", 'value' => 'Skriv et interessant blogindlæg om:\n\n ##description## \n\n Blogindlægs titel:\n ##title## \n\nTone i blogintroen skal være:\n ##tone_language## \n\n'],

            ['id' => 295, 'template_id' => 9, 'key' => "nl-NL", 'value' => 'Schrijf een interessante blogpost-intro over:\n\n ##description## \n\n Titel blogpost:\n ##title## \n\nDe toon van de blogintro moet zijn:\n ##tone_language## \n\n'],

            ['id' => 296, 'template_id' => 9, 'key' => "et-EE", 'value' => 'Kirjutage huvitav blogipostituse tutvustus teemal:\n\n ##description## \n\n Blogipostituse pealkiri:\n ##title## \n\nBlogi sissejuhatuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 297, 'template_id' => 9, 'key' => "fi-FI", 'value' => 'Kirjoita mielenkiintoinen blogikirjoituksen esittely aiheesta:\n\n ##description## \n\n Blogiviestin otsikko:\n ##title## \n\nBlogin johdannon äänensävyn on oltava:\n ##tone_language## \n\n'],

            ['id' => 298, 'template_id' => 9, 'key' => "fr-FR", 'value' => 'Rédigez une introduction intéressante sur :\n\n ##description## \n\n Titre de larticle de blog :\n ##title## \n\nLe ton de la voix de l intro du blog doit être :\n ##tone_language## \n\n'],

            ['id' => 299, 'template_id' => 9, 'key' => "de-DE", 'value' => 'Schreiben Sie eine interessante Einführung in einen Blog-Beitrag über:\n\n ##description## \n\n Titel des Blogposts:\n ##title## \n\nTonlage des Blog-Intros muss sein:\n ##tone_language## \n\n'],

            ['id' => 300, 'template_id' => 9, 'key' => "el-GR", 'value' => 'Γράψτε μια ενδιαφέρουσα εισαγωγή δημοσίευσης ιστολογίου σχετικά με:\n\n ##description## \n\n Τίτλος ανάρτησης ιστολογίου:\n ##title## \n\nΟ τόνος της φωνής της εισαγωγής του ιστολογίου πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 301, 'template_id' => 9, 'key' => "he-IL", 'value' => 'כתוב מבוא פוסט מעניין בבלוג על:\n\n ##description## \n\n כותרת פוסט הבלוג:\n ##title## \n\nטון הדיבור של ההקדמה לבלוג חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 302, 'template_id' => 9, 'key' => "hi-IN", 'value' => 'इस बारे में एक रोचक ब्लॉग पोस्ट परिचय लिखें:\n\n ##description## \n\n ब्लॉग पोस्ट शीर्षक:\n ##title## \n\nब्लॉग के परिचय का स्वर होना चाहिए:\n ##tone_language## \n\n'],

            ['id' => 303, 'template_id' => 9, 'key' => "hu-HU", 'value' => 'Írjon érdekes blogbejegyzést erről:\n\n ##description## \n\n Blogbejegyzés címe:\n ##title## \n\nA blogbevezető hangnemének a következőnek kell lennie:\n ##tone_language## \n\n'],

            ['id' => 304, 'template_id' => 9, 'key' => "is-IS", 'value' => 'Skrifaðu áhugaverða bloggfærslu um:\n\n ##description## \n\n Titill bloggfærslu:\n ##title## \n\nTónn í blogginngangi verður að vera:\n ##tone_language## \n\n'],

            ['id' => 305, 'template_id' => 9, 'key' => "id-ID", 'value' => 'Tulis pengantar postingan blog yang menarik tentang:\n\n ##description## \n\n Judul entri blog:\n ##title## \n\nNada suara intro blog harus:\n ##tone_language## \n\n'],

            ['id' => 306, 'template_id' => 9, 'key' => "it-IT", 'value' => 'Scrivi un`interessante introduzione al post del blog su:\n\n ##description## \n\n Titolo del post del blog:\n ##title## \n\nIl tono di voce dell`introduzione del blog deve essere:\n ##tone_language## \n\n'],

            ['id' => 307, 'template_id' => 9, 'key' => "ja-JP", 'value' => '興味深いブログ投稿の紹介を書いてください:\n\n ##description## \n\n ブログ記事のタイトル:\n ##title## \n\nブログのイントロのトーンは次のようにする必要があります:\n ##tone_language## \n\n'],

            ['id' => 308, 'template_id' => 9, 'key' => "ko-KR", 'value' => '다음에 대한 흥미로운 블로그 게시물 소개 작성:\n\n ##description## \n\n 블로그 게시물 제목:\n ##title## \n\n블로그 소개의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n'],

            ['id' => 309, 'template_id' => 9, 'key' => "ms-MY", 'value' => 'Tulis intro catatan blog yang menarik tentang:\n\n ##description## \n\n Tajuk catatan blog:\n ##title## \n\nNada suara intro blog mestilah:\n ##tone_language## \n\n'],

            ['id' => 310, 'template_id' => 9, 'key' => "nb-NO", 'value' => 'Skriv en interessant introduksjon til blogginnlegg om:\n\n ##description## \n\n Tittel på blogginnlegg:\n ##title## \n\nTone i bloggintroen må være:\n ##tone_language## \n\n'],

            ['id' => 311, 'template_id' => 9 , 'key' => "pl-PL", 'value' => 'Napisz interesujące wprowadzenie do wpisu na blogu na temat:\n\n ##description## \n\n Tytuł wpisu na blogu:\n ##title## \n\nTon głosu we wstępie do bloga musi być:\n ##tone_language## \n\n'],

            ['id' => 312, 'template_id' => 9, 'key' => "pt-PT", 'value' => 'Escreva uma introdução de postagem de blog interessante sobre:\n\n ##description## \n\n Título da postagem do blog:\n ##title## \n\nTom de voz da introdução do blog deve ser:\n ##tone_language## \n\n'],

            ['id' => 313, 'template_id' => 9, 'key' => "ru-RU", 'value' => 'Напишите интересное введение в блог о:\n\n ##description## \n\n Заголовок сообщения в блоге:\n ##title## \n\nТон озвучивания вступления блога должен быть:\n ##tone_language## \n\n'],

            ['id' => 314, 'template_id' => 9, 'key' => "es-ES", 'value' => 'Escribe una introducción de blog interesante sobre:\n\n ##description## \n\n Título de la publicación del blog:\n ##title## \n\nEl tono de voz de la intro del blog debe ser:\n ##tone_language## \n\n'],

            ['id' => 315, 'template_id' => 9, 'key' => "sv-SE", 'value' => 'Skriv ett intressant blogginlägg om:\n\n ##description## \n\n Blogginläggets titel:\n ##title## \n\nRöst i bloggintrot måste vara:\n ##tone_language## \n\n'],

            ['id' => 316, 'template_id' => 9, 'key' => "tr-TR", 'value' => 'Şununla ilgili ilginç bir blog yazısı yaz:\n\n ##description## \n\n Blog gönderisi başlığı:\n ##title## \n\nBlog girişinin ses tonu şöyle olmalıdır:\n ##tone_language## \n\n'],

            ['id' => 317, 'template_id' => 9, 'key' => "pt-BR", 'value' => 'Escreva uma introdução de postagem de blog interessante sobre:\n\n ##description## \n\n Título da postagem do blog:\n ##title## \n\nTom de voz da introdução do blog deve ser:\n ##tone_language## \n\n'],

            ['id' => 318, 'template_id' => 9, 'key' => "ro-RO", 'value' => 'Scrieți o prezentare interesantă pe blog despre:\n\n ##description## \n\n Titlul postării de blog:\n ##title## \n\nTonul de voce al introducerii pe blog trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 319, 'template_id' => 9, 'key' => "vi-VN", 'value' => 'Viết một bài đăng blog thú vị giới thiệu về:\n\n ##description## \n\n Tiêu đề bài đăng trên blog:\n ##title## \n\nGiọng nói của phần giới thiệu blog phải là:\n ##tone_language## \n\n'],

            ['id' => 320, 'template_id' => 9, 'key' => "sw-KE", 'value' => 'Andika utangulizi wa chapisho la kuvutia la blogu kuhusu:\n\n ##description## \n\n Jina la chapisho la blogu:\n ##title## \n\nToni ya sauti ya utangulizi wa blogu lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 321, 'template_id' => 9, 'key' => "sl-SI", 'value' => 'Napišite zanimivo uvodno objavo v spletnem dnevniku o:\n\n ##description## \n\n Naslov objave v spletnem dnevniku:\n ##title## \n\nTon glasu uvoda v spletni dnevnik mora biti:\n ##tone_language## \n\n'],

            ['id' => 322, 'template_id' => 9, 'key' => "th-TH", 'value' => 'เขียนบทความแนะนำบล็อกที่น่าสนใจเกี่ยวกับ:\n\n ##description## \n\n ชื่อบล็อกโพสต์:\n ##title## \n\nเสียงแนะนำบล็อกต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 323, 'template_id' => 9, 'key' => "uk-UA", 'value' => 'Напишіть цікаву вступну публікацію в блозі про:\n\n ##description## \n\n Назва допису в блозі:\n ##title## \n\nТон вступу до блогу має бути:\n ##tone_language## \n\n'],

            ['id' => 324, 'template_id' => 9, 'key' => "lt-LT", 'value' => 'Parašykite įdomų tinklaraščio įrašą apie:\n\n ##description## \n\n Tinklaraščio įrašo pavadinimas:\n ##title## \n\nTinklaraščio įžangos balso tonas turi būti:\n ##tone_language## \n\n'],

            ['id' => 325, 'template_id' => 9, 'key' => "bg-BG", 'value' => 'Напишете интересна интро публикация в блог за:\n\n ##description## \n\n Заглавие на публикацията в блога:\n ##title## \n\nТонът на интрото на блога трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 326, 'template_id' => 10, 'key' => "en-US", 'value' => 'Write a blog article conclusion for:\n\n ##description## \n\n Blog article title:\n ##title## \n\nTone of voice of the conclusion must be:\n ##tone_language## \n\n'],

            ['id' => 327, 'template_id' => 10, 'key' => "ar-AE", 'value' => 'اكتب مقالاً ختامياً لـ:\n\n ##description## \n\n عنوان مقالة المدونة:\n ##title## \n\n يجب أن تكون نغمة صوت الاستنتاج:\n ##tone_language## \n\n'],

            ['id' => 328, 'template_id' => 10, 'key' => "cmn-CN", 'value' => '为以下内容写一篇博客文章结论：\n\n ##description## \n\n 博客文章标题：\n ##title## \n\n结论的语气必须是：\n ##tone_language## \n\n'],

            ['id' => 329, 'template_id' => 10, 'key' => "hr-HR", 'value' => 'Napišite zaključak članka na blogu za:\n\n ##description## \n\n Naslov članka na blogu:\n ##title## \n\nTon glasa zaključka mora biti:\n ##tone_language## \n\n'],

            ['id' => 330, 'template_id' => 10, 'key' => "cs-CZ", 'value' => 'Napište závěr článku blogu pro:\n\n ##description## \n\n Název článku blogu:\n ##title## \n\nTón hlasu závěru musí být:\n ##tone_language## \n\n'],

            ['id' => 331, 'template_id' => 10, 'key' => "da-DK", 'value' => 'Skriv en blogartikel konklusion for:\n\n ##description## \n\n Blogartikeltitel:\n ##title## \n\nTone i konklusionen skal være:\n ##tone_language## \n\n'],

            ['id' => 332, 'template_id' => 10, 'key' => "nl-NL", 'value' => 'Schrijf een conclusie van een blogartikel voor:\n\n ##description## \n\n Titel blogartikel:\n ##title## \n\nDe toon van de conclusie moet zijn:\n ##tone_language## \n\n'],

            ['id' => 333, 'template_id' => 10, 'key' => "et-EE", 'value' => 'Kirjutage blogiartikli järeldus:\n\n ##description## \n\n Blogi artikli pealkiri:\n ##title## \n\nJärelduse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 334, 'template_id' => 10, 'key' => "fi-FI", 'value' => 'Kirjoita blogiartikkelin päätelmä:\n\n ##description## \n\n Blogiartikkelin otsikko:\n ##title## \n\nJohtopäätöksen äänensävyn on oltava:\n ##tone_language## \n\n'],

            ['id' => 335, 'template_id' => 10, 'key' => "fr-FR", 'value' => "Rédigez une conclusion d'article de blog pour :\n\n ##description## \n\n Titre de l'article du blog :\n ##title## \n\nLe ton de la voix de la conclusion doit être :\n ##tone_language## \n\n"],

            ['id' => 336, 'template_id' => 10, 'key' => "de-DE", 'value' => 'Schreiben Sie einen Blogartikel-Abschluss für:\n\n ##description## \n\n Titel des Blog-Artikels:\n ##title## \n\nTonfall der Schlussfolgerung muss sein:\n ##tone_language## \n\n'],

            ['id' => 337, 'template_id' => 10, 'key' => "el-GR", 'value' => 'Γράψτε ένα συμπέρασμα άρθρου ιστολογίου για:\n\n ##description## \n\n Τίτλος άρθρου ιστολογίου:\n ##title## \n\nΟ τόνος της φωνής του συμπεράσματος πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 338, 'template_id' => 10, 'key' => "he-IL", 'value' => 'כתוב מסקנת מאמר בבלוג עבור:\n\n ##description## \n\n כותרת מאמר הבלוג:\n ##title## \n\nטון הדיבור של המסקנה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 339, 'template_id' => 10, 'key' => "hi-IN", 'value' => 'इसके लिए एक ब्लॉग लेख निष्कर्ष लिखें:\n\n ##description## \n\n ब्लॉग लेख का शीर्षक:\n ##title## \n\nनिष्कर्ष की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n'],

            ['id' => 340, 'template_id' => 10, 'key' => "hu-HU", 'value' => 'Írjon blogcikk következtetést:\n\n ##description## \n\n Blog cikk címe:\n ##title## \n\nA következtetés hangnemének a következőnek kell lennie:\n ##tone_language## \n\n'],

            ['id' => 341, 'template_id' => 10, 'key' => "is-IS", 'value' => 'Skrifaðu niðurstöðu blogggreinar fyrir:\n\n ##description## \n\n Titill blogggreinar:\n ##title## \n\nTónn í niðurstöðunni verður að vera:\n ##tone_language## \n\n'],

            ['id' => 342, 'template_id' => 10, 'key' => "id-ID", 'value' => 'Tulis kesimpulan artikel blog untuk:\n\n ##description## \n\n Judul artikel blog:\n ##title## \n\nNada suara kesimpulan harus:\n ##tone_language## \n\n'],

            ['id' => 343, 'template_id' => 10, 'key' => "it-IT", 'value' => 'Scrivi la conclusione di un articolo di blog per:\n\n ##description## \n\n Titolo articolo blog:\n ##title## \n\nIl tono di voce della conclusione deve essere:\n ##tone_language## \n\n'],

            ['id' => 344, 'template_id' => 10, 'key' => "ja-JP", 'value' => '次のブログ記事の結論を書きます:\n\n ##description## \n\n ブログ記事のタイトル:\n ##title## \n\n結論の口調は:\n ##tone_language## \n\n'],

            ['id' => 345, 'template_id' => 10, 'key' => "ko-KR", 'value' => '다음에 대한 블로그 기사 결론 쓰기:\n\n ##description## \n\n 블로그 기사 제목:\n ##title## \n\n결론의 어조는 다음과 같아야 합니다.\n ##tone_language## \n\n'],

            ['id' => 346, 'template_id' => 10, 'key' => "ms-MY", 'value' => '다음에 대한 블로그 기사 결론 쓰기:\n\n ##description## \n\n 블로그 기사 제목:\n ##title## \n\n결론의 어조는 다음과 같아야 합니다.\n ##tone_language## \n\n'],

            ['id' => 347, 'template_id' => 10, 'key' => "nb-NO", 'value' => 'Skriv en bloggartikkelkonklusjon for:\n\n ##description## \n\n Bloggartikkeltittel:\n ##title## \n\nTone i konklusjonen må være:\n ##tone_language## \n\n'],

            ['id' => 348, 'template_id' => 10, 'key' => "pl-PL", 'value' => 'Napisz zakończenie artykułu na blogu dla:\n\n ##description## \n\n Tytuł artykułu na blogu:\n ##title## \n\nTon wniosku musi być następujący:\n ##tone_language## \n\n'],

            ['id' => 349, 'template_id' => 10, 'key' => "pt-PT", 'value' => 'Escreva uma conclusão de artigo de blog para:\n\n ##description## \n\n Título do artigo do blog:\n ##title## \n\nTom de voz da conclusão deve ser:\n ##tone_language## \n\n'],

            ['id' => 350, 'template_id' => 10, 'key' => "ru-RU", 'value' => 'Напишите вывод статьи в блоге для:\n\n ##description## \n\n Название статьи в блоге:\n ##title## \n\nТон голоса заключения должен быть:\n ##tone_language## \n\n'],

            ['id' => 351, 'template_id' => 10, 'key' => "es-ES", 'value' => 'Escribe la conclusión de un artículo de blog para:\n\n ##description## \n\n Título del artículo del blog:\n ##title## \n\nEl tono de voz de la conclusión debe ser:\n ##tone_language## \n\n'],

            ['id' => 352, 'template_id' => 10, 'key' => "sv-SE", 'value' => 'Skriv en bloggartikelavslutning för:\n\n ##description## \n\n Bloggartikeltitel:\n ##title## \n\nTonfallet för slutsatsen måste vara:\n ##tone_language## \n\n'],

            ['id' => 353, 'template_id' => 10, 'key' => "tr-TR", 'value' => 'Bir blog makalesi sonucu yaz:\n\n ##description## \n\n Blog makalesi başlığı:\n ##title## \n\nSonuçtaki ses tonu şöyle olmalıdır:\n ##tone_language## \n\n'],

            ['id' => 354, 'template_id' => 10, 'key' => "pt-BR", 'value' => 'Escreva uma conclusão de artigo de blog para:\n\n ##description## \n\n Título do artigo do blog:\n ##title## \n\nTom de voz da conclusão deve ser:\n ##tone_language## \n\n'],

            ['id' => 355, 'template_id' => 10, 'key' => "ro-RO", 'value' => 'Scrieți o concluzie a unui articol de blog pentru:\n\n ##description## \n\n Titlul articolului de blog:\n ##title## \n\nTonul de voce al concluziei trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 356, 'template_id' => 10, 'key' => "vi-VN", 'value' => 'Viết kết luận bài viết trên blog cho:\n\n ##description## \n\n Tiêu đề bài viết trên blog:\n ##title## \n\nGiọng kết luận phải là:\n ##tone_language## \n\n'],

            ['id' => 357, 'template_id' => 10, 'key' => "sw-KE", 'value' => 'Andika hitimisho la makala ya blogu ya:\n\n ##description## \n\n Jina la makala ya blogu:\n ##title## \n\nToni ya sauti ya hitimisho lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 358, 'template_id' => 10, 'key' => "sl-SI", 'value' => 'Napišite zaključek članka v blogu za:\n\n ##description## \n\n Naslov članka v blogu:\n ##title## \n\nTon sklepa mora biti:\n ##tone_language## \n\n'],

            ['id' => 359, 'template_id' => 10, 'key' => "th-TH", 'value' => 'เขียนสรุปบทความบล็อกสำหรับ:\n\n ##description## \n\n ชื่อบทความบล็อก:\n ##title## \n\nเสียงสรุปต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 360, 'template_id' => 10, 'key' => "uk-UA", 'value' => 'Напишіть висновок статті в блозі для:\n\n ##description## \n\n Назва статті блогу:\n ##title## \n\nТон висновку повинен бути:\n ##tone_language## \n\n'],

            ['id' => 361, 'template_id' => 10, 'key' => "lt-LT", 'value' => 'Parašykite tinklaraščio straipsnio išvadą:\n\n ##description## \n\n Tinklaraščio straipsnio pavadinimas:\n ##title## \n\nIšvados balso tonas turi būti:\n ##tone_language## \n\n'],

            ['id' => 362, 'template_id' => 10, 'key' => "bg-BG", 'value' => 'Генерирайте 10 закачливи заглавия на блогове за:\n\n ##description## \n\nНапишете заключение за статия в блог за:\n\n ##description## \n\n Заглавие на блог статия:\n ##title## \n\nТонът на гласа на заключението трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 363, 'template_id' => 11, 'key' => "en-US", 'value' => 'Summarize this text in a short concise way:\n\n ##text## \n\nTone of summary must be:\n ##tone_language## \n\n'],

            ['id' => 364, 'template_id' => 11, 'key' => "ar-AE", 'value' => 'لخص هذا النص بإيجاز قصير:\n\n ##text## \n\nيجب أن تكون نغمة التلخيص:\n ##tone_language## \n\n'],

            ['id' => 365, 'template_id' => 11, 'key' => "cmn-CN", 'value' => '用简短的方式总结这段文字：\n\n ##text## \n\n摘要语气必须是：\n ##tone_language## \n\n'],

            ['id' => 366, 'template_id' => 11, 'key' => "hr-HR", 'value' => 'Ukratko sažeti ovaj tekst:\n\n ##text## \n\nTon sažetka mora biti:\n ##tone_language## \n\n'],

            ['id' => 367, 'template_id' => 11, 'key' => "cs-CZ", 'value' => 'Shrňte tento text krátkým výstižným způsobem:\n\n ##text## \n\nTón shrnutí musí být:\n ##tone_language## \n\n'],

            ['id' => 368, 'template_id' => 11, 'key' => "da-DK", 'value' => 'Opsummer denne tekst på en kort og præcis måde:\n\n ##text## \n\nTone i resumé skal være:\n ##tone_language## \n\n'],

            ['id' => 369, 'template_id' => 11, 'key' => "nl-NL", 'value' => 'Vat deze tekst kort en bondig samen:\n\n ##text## \n\nDe toon van de samenvatting moet zijn:\n ##tone_language## \n\n'],

            ['id' => 370, 'template_id' => 11, 'key' => "et-EE", 'value' => 'Tehke see tekst lühidalt kokkuvõtlikult:\n\n ##text## \n\nKokkuvõtte toon peab olema:\n ##tone_language## \n\n'],

            ['id' => 371, 'template_id' => 11, 'key' => "fi-FI", 'value' => 'Tee tämä teksti lyhyesti ytimekkäästi:\n\n ##text## \n\nYhteenvedon äänen tulee olla:\n ##tone_language## \n\n'],

            ['id' => 372, 'template_id' => 11, 'key' => "fr-FR", 'value' => 'Résumez ce texte de manière courte et concise :\n\n ##text## \n\nLe ton du résumé doit être :\n ##tone_language## \n\n'],

            ['id' => 373, 'template_id' => 11, 'key' => "de-DE", 'value' => 'Fass diesen Text kurz und prägnant zusammen:\n\n ##text## \n\nTon der Zusammenfassung muss sein:\n ##tone_language## \n\n'],

            ['id' => 374, 'template_id' => 11, 'key' => "el-GR", 'value' => 'Συνοψήστε αυτό το κείμενο με σύντομο και συνοπτικό τρόπο:\n\n ##text## \n\nΟ τόνος της σύνοψης πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 375, 'template_id' => 11, 'key' => "he-IL", 'value' => 'סכם את הטקסט הזה בצורה קצרה תמציתית:\n\n ##text## \n\nטון הסיכום חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 376, 'template_id' => 11, 'key' => "hi-IN", 'value' => 'इस पाठ को संक्षेप में संक्षेप में प्रस्तुत करें:\n\n ##text## \n\nसारांश का लहजा होना चाहिए:\n ##tone_language## \n\n'],

            ['id' => 377, 'template_id' => 11, 'key' => "hu-HU", 'value' => 'Összefoglalja ezt a szöveget röviden, tömören:\n\n ##text## \n\nAz összefoglaló hangjának a következőnek kell lennie:\n ##tone_language## \n\n'],

            ['id' => 378, 'template_id' => 11, 'key' => "is-IS", 'value' => 'Dregðu saman þennan texta á stuttan hnitmiðaðan hátt:\n\n ##text## \n\nTónn yfirlits verður að vera:\n ##tone_language## \n\n'],

            ['id' => 379, 'template_id' => 11, 'key' => "id-ID", 'value' => 'Rangkum teks ini dengan cara yang singkat dan padat:\n\n ##text## \n\nNada ringkasan harus:\n ##tone_language## \n\n'],

            ['id' => 380, 'template_id' => 11, 'key' => "it-IT", 'value' => 'Riassumi questo testo in modo breve e conciso:\n\n ##text## \n\nIl tono del riassunto deve essere:\n ##tone_language## \n\n'],

            ['id' => 381, 'template_id' => 11, 'key' => "ja-JP", 'value' => 'このテキストを短く簡潔に要約してください:\n\n ##text## \n\n要約のトーンは:\n ##tone_language## \n\n'],

            ['id' => 382, 'template_id' => 11, 'key' => "ko-KR", 'value' => '이 텍스트를 짧고 간결하게 요약:\n\n ##text## \n\n요약 톤은 다음과 같아야 합니다.\n ##tone_language## \n\n'],

            ['id' => 383, 'template_id' => 11, 'key' => "ms-MY", 'value' => 'Ringkaskan teks ini dengan cara ringkas yang ringkas:\n\n ##text## \n\nNada ringkasan mestilah:\n ##tone_language## \n\n'],

            ['id' => 384, 'template_id' => 11, 'key' => "nb-NO", 'value' => 'Opsummer denne teksten på en kortfattet måte:\n\n ##text## \n\nTone i sammendraget må være:\n ##tone_language## \n\n'],

            ['id' => 385, 'template_id' => 11, 'key' => "pl-PL", 'value' => 'Podsumuj ten tekst w zwięzły sposób:\n\n ##text## \n\nTon podsumowania musi być:\n ##tone_language## \n\n'],

            ['id' => 386, 'template_id' => 11, 'key' => "pt-PT", 'value' => 'Resuma este texto de forma curta e concisa:\n\n ##text## \n\nO tom do resumo deve ser:\n ##tone_language## \n\n'],

            ['id' => 387, 'template_id' => 11, 'key' => "ru-RU", 'value' => 'Кратко изложите этот текст:\n\n ##text## \n\nТон резюме должен быть:\n ##tone_language## \n\n'],

            ['id' => 388, 'template_id' => 11, 'key' => "es-ES", 'value' => 'Resume este texto de forma breve y concisa:\n\n ##text## \n\nEl tono del resumen debe ser:\n ##tone_language## \n\n'],

            ['id' => 389, 'template_id' => 11, 'key' => "sv-SE", 'value' => 'Sammanfatta den här texten på ett kortfattat sätt:\n\n ##text## \n\nTonen i sammanfattningen måste vara:\n ##tone_language## \n\n'],

            ['id' => 390, 'template_id' => 11, 'key' => "tr-TR", 'value' => 'Bu metni kısa ve öz bir şekilde özetleyin:\n\n ##text## \n\nÖzetin tonu şöyle olmalıdır:\n ##tone_language## \n\n'],

            ['id' => 391, 'template_id' => 11, 'key' => "pt-BR", 'value' => 'Resuma este texto de forma curta e concisa:\n\n ##text## \n\nO tom do resumo deve ser:\n ##tone_language## \n\n'],

            ['id' => 392, 'template_id' => 11, 'key' => "ro-RO", 'value' => 'Rezumați acest text într-un mod scurt și concis:\n\n ##text## \n\nTonul rezumatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 393, 'template_id' => 11, 'key' => "vi-VN", 'value' => 'Tóm tắt văn bản này một cách ngắn gọn súc tích:\n\n ##text## \n\nGiọng tóm tắt phải là:\n ##tone_language## \n\n'],

            ['id' => 394, 'template_id' => 11, 'key' => "sw-KE", 'value' => 'Fanya muhtasari wa maandishi haya kwa njia fupi fupi:\n\n ##text## \n\nToni ya muhtasari lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 395, 'template_id' => 11, 'key' => "sl-SI", 'value' => 'Povzemite to besedilo na kratek jedrnat način:\n\n ##text## \n\nTon povzetka mora biti:\n ##tone_language## \n\n'],

            ['id' => 396, 'template_id' => 11, 'key' => "th-TH", 'value' => 'สรุปข้อความนี้อย่างสั้นกระชับ:\n\n ##text## \n\nเสียงสรุปต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 397, 'template_id' => 11, 'key' => "uk-UA", 'value' => 'Коротко викладіть цей текст:\n\n ##text## \n\nТон резюме має бути:\n ##tone_language## \n\n'],

            ['id' => 398, 'template_id' => 11, 'key' => "lt-LT", 'value' => 'Trumpai glaustai apibendrinkite šį tekstą:\n\n ##text## \n\nSantraukos tonas turi būti:\n ##tone_language## \n\n'],

            ['id' => 399, 'template_id' => 11, 'key' => "bg-BG", 'value' => 'Резюмирайте този текст по кратък стегнат начин:\n\n ##text## \n\nТонът на обобщението трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 400, 'template_id' => 12, 'key' => "en-US", 'value' => 'Write a long creative product description for: ##title## \n\nTarget audience is: ##audience## \n\nUse this description: ##description## \n\nTone of generated text must be:\n ##tone_language## \n\n'],

            ['id' => 401, 'template_id' => 12, 'key' => "ar-AE", 'value' => 'اكتب وصفًا إبداعيًا طويلًا للمنتج لـ: ##title## \n\nالجمهور المستهدف هو: ##audience## \n\nاستخدم هذا الوصف:". $description. "\n\nيجب أن تكون نغمة النص الناتج:\n ##tone_language## \n\n'],

            ['id' => 402, 'template_id' => 12, 'key' => "cmn-CN", 'value' => '为：写一个长的创意产品描述 ##title## \n\n目标受众是： ##audience## \n\n使用这个描述： ##description## \n\n生成文本的基调必须是：\n ##tone_language## \n\n'],

            ['id' => 403, 'template_id' => 12, 'key' => "hr-HR", 'value' => 'Napišite dugačak kreativni opis proizvoda za: ##title## \n\nCiljana publika je: ##audience## \n\nKoristite ovaj opis: ##description## \n\nTon generiranog teksta mora biti:\n ##tone_language## \n\n'],

            ['id' => 404, 'template_id' => 12, 'key' => "cs-CZ", 'value' => 'Napište dlouhý popis kreativního produktu pro: ##title## \n\nCílové publikum je: ##audience## \n\nPoužijte tento popis: ##description## \n\nTón generovaného textu musí být:\n ##tone_language## \n\n'],

            ['id' => 405, 'template_id' => 12, 'key' => "da-DK", 'value' => 'Skriv en lang kreativ produktbeskrivelse til: ##title## \n\nMålgruppe er: ##audience## \n\nBrug denne beskrivelse: ##description## \n\nTone i genereret tekst skal være:\n ##tone_language## \n\n'],

            ['id' => 406, 'template_id' => 12, 'key' => "nl-NL", 'value' => 'Schrijf een lange creatieve productbeschrijving voor: ##title## \n\nDoelgroep is: ##audience## \n\nGebruik deze omschrijving: ##description## \n\nDe toon van gegenereerde tekst moet zijn:\n ##tone_language## \n\n'],

            ['id' => 407, 'template_id' => 12, 'key' => "et-EE", 'value' => 'Kirjutage pikk loominguline tootekirjeldus: ##title## \n\nSihtpublik on: ##audience## \n\nKasutage seda kirjeldust: ##description## \n\nLoodud teksti toon peab olema:\n ##tone_language## \n\n'],

            ['id' => 408, 'template_id' => 12, 'key' => "fi-FI", 'value' => 'Kirjoita pitkä luova tuotekuvaus: ##title## \n\nKohdeyleisö on: ##audience## \n\nKäytä tätä kuvausta: ##description## \n\nLuodun tekstin äänen tulee olla:\n ##tone_language## \n\n'],

            ['id' => 409, 'template_id' => 12, 'key' => "fr-FR", 'value' => 'Rédigez une longue description de produit créative pour : ##title## \n\nLe public cible est : ##audience## \n\nUtilisez cette description : ##description## \n\nLe ton du texte généré doit être :\n ##tone_language## \n\n'],

            ['id' => 410, 'template_id' => 12, 'key' => "de-DE", 'value' => 'Schreiben Sie eine lange kreative Produktbeschreibung für: ##title## \n\nZielpublikum ist: ##audience## \n\nVerwenden Sie diese Beschreibung: ##description## \n\nTon des generierten Textes muss sein:\n ##tone_language## \n\n'],

            ['id' => 411, 'template_id' => 12, 'key' => "el-GR", 'value' => 'Γράψτε μια μεγάλη περιγραφή δημιουργικού προϊόντος για: ##title## \n\nΤο κοινό-στόχος είναι: ##audience## \n\nΧρησιμοποιήστε αυτήν την περιγραφή: ##description## \n\nΟ τόνος του κειμένου που δημιουργείται πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 412, 'template_id' => 12, 'key' => "he-IL", 'value' => 'כתוב תיאור מוצר יצירתי ארוך עבור: ##title## \n\nקהל היעד הוא: ##audience## \n\nהשתמש בתיאור הזה: ##description## \n\nטון הטקסט שנוצר חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 413, 'template_id' => 12, 'key' => "hi-IN", 'value' => 'इसके लिए एक लंबा रचनात्मक उत्पाद विवरण लिखें: ##title## \n\nलक्षित दर्शक हैं: ##audience## \n\nइस विवरण का उपयोग करें: ##description## \n\nजनरेट किए गए टेक्स्ट का टोन होना चाहिए:\n ##tone_language## \n\n'],

            ['id' => 414, 'template_id' => 12, 'key' => "hu-HU", 'value' => 'Írjon hosszú kreatív termékleírást a következőhöz: ##title## \n\nA célközönség: ##audience## \n\nHasználja ezt a leírást: ##description## \n\nA generált szöveg hangjának a következőnek kell lennie:\n ##tone_language## \n\n'],

            ['id' => 415, 'template_id' => 12, 'key' => "is-IS", 'value' => 'Skrifaðu langa skapandi vörulýsingu fyrir: ##title## \n\nMarkhópur er: ##audience## \n\nNotaðu þessa lýsingu: ##description## \n\nTónn texta sem myndast verður að vera:\n ##tone_language## \n\n'],

            ['id' => 416, 'template_id' => 12, 'key' => "id-ID", 'value' => 'Tulis deskripsi produk kreatif yang panjang untuk: ##title## \n\nTarget audiens adalah: ##audience## \n\nGunakan deskripsi ini: ##description## \n\nNada teks yang dihasilkan harus:\n ##tone_language## \n\n'],

            ['id' => 417, 'template_id' => 12, 'key' => "it-IT", 'value' => 'Scrivi una lunga descrizione del prodotto creativo per: ##title## \n\nIl pubblico di destinazione è: ##audience## \n\nUsa questa descrizione: ##description## \n\nIl tono del testo generato deve essere:\n ##tone_language## \n\n'],

            ['id' => 418, 'template_id' => 12, 'key' => "ja-JP", 'value' => '次の製品の長いクリエイティブな説明を書いてください: ##title## \n\n対象者: ##audience## \n\nこの説明を使用してください: ##description## \n\n生成されたテキストのトーンは:\n ##tone_language## \n\n'],

            ['id' => 419, 'template_id' => 12, 'key' => "ko-KR", 'value' => '다음에 대한 길고 창의적인 제품 설명 작성: ##title## \n\n대상은: ##audience## \n\n이 설명을 사용하십시오: ##description## \n\n생성된 텍스트의 톤은 다음과 같아야 합니다.\n ##tone_language## \n\n'],

            ['id' => 420, 'template_id' => 12, 'key' => "ms-MY", 'value' => 'Tulis penerangan produk kreatif yang panjang untuk: ##title## \n\nKhalayak sasaran ialah: ##audience## \n\nGunakan penerangan ini: ##description## \n\nNada teks yang dijana mestilah:\n ##tone_language## \n\n'],

            ['id' => 421, 'template_id' => 12, 'key' => "nb-NO", 'value' => 'Skriv en lang kreativ produktbeskrivelse for: ##title## \n\nMålgruppen er: ##audience## \n\nBruk denne beskrivelsen: ##description## \n\nTone i generert tekst må være:\n ##tone_language## \n\n'],

            ['id' => 422, 'template_id' => 12, 'key' => "pl-PL", 'value' => 'Napisz długi kreatywny opis produktu dla: ##title## \n\nDocelowi odbiorcy to: ##audience## \n\nUżyj tego opisu: ##description## \n\nTon generowanego tekstu musi być:\n ##tone_language## \n\n'],

            ['id' => 423, 'template_id' => 12, 'key' => "pt-PT", 'value' => 'Escreva uma longa descrição de produto criativo para: ##title## \n\nO público-alvo é: ##audience## \n\nUse esta descrição: ##description## \n\nO tom do texto gerado deve ser:\n ##tone_language## \n\n'],

            ['id' => 424, 'template_id' => 12, 'key' => "ru-RU", 'value' => 'Напишите длинное креативное описание продукта для: ##title## \n\nЦелевая аудитория: ##audience## \n\nИспользуйте это описание: ##description## \n\nТон генерируемого текста должен быть:\n ##tone_language## \n\n'],

            ['id' => 425, 'template_id' => 12, 'key' => "es-ES", 'value' => 'Escriba una descripción de producto creativa larga para: ##title## \n\nEl público objetivo es: ##audience## \n\nUsar esta descripción: ##description## \n\nEl tono del texto generado debe ser:\n ##tone_language## \n\n'],

            ['id' => 426, 'template_id' => 12, 'key' => "sv-SE", 'value' => 'Skriv en lång kreativ produktbeskrivning för: ##title## \n\nMålgruppen är: ##audience## \n\nAnvänd denna beskrivning: ##description## \n\nTonen i genererad text måste vara:\n ##tone_language## \n\n'],

            ['id' => 427, 'template_id' => 12, 'key' => "tr-TR", 'value' => 'Şunun için uzun bir yaratıcı ürün açıklaması yazın: ##title## \n\nHedef kitle: ##audience## \n\nBu açıklamayı kullanın: ##description## \n\nOluşturulan metnin tonu şöyle olmalıdır:\n ##tone_language## \n\n'],

            ['id' => 428, 'template_id' => 12, 'key' => "pt-BR", 'value' => 'Escreva uma longa descrição de produto criativo para: ##title## \n\nO público-alvo é: ##audience## \n\nUse esta descrição: ##description## \n\nO tom do texto gerado deve ser:\n ##tone_language## \n\n'],

            ['id' => 429, 'template_id' => 12, 'key' => "ro-RO", 'value' => 'Scrieți o descriere creativă lungă a produsului pentru: ##title## \n\nPublicul țintă este: ##audience## \n\nUtilizați această descriere: ##description## \n\nTonul textului generat trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 430, 'template_id' => 12, 'key' => "vi-VN", 'value' => 'Viết một đoạn mô tả sản phẩm sáng tạo dài cho: ##title## \n\nĐối tượng mục tiêu là: ##audience## \n\nSử dụng mô tả này: ##description## \n\nÂm của văn bản được tạo phải là:\n ##tone_language## \n\n'],

            ['id' => 431, 'template_id' => 12, 'key' => "sw-KE", 'value' => 'Andika maelezo marefu ya bidhaa ya ubunifu kwa: ##title## \n\nHadhira inayolengwa ni: ##audience## \n\nTumia maelezo haya: ##description## \n\nToni ya maandishi yanayozalishwa lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 432, 'template_id' => 12, 'key' => "sl-SI", 'value' => 'Napišite dolg ustvarjalni opis izdelka za: ##title## \n\nCiljna publika je: ##audience## \n\nUporabi ta opis: ##description## \n\nTon ustvarjenega besedila mora biti:\n ##tone_language## \n\n'],

            ['id' => 433, 'template_id' => 12, 'key' => "th-TH", 'value' => 'เขียนคำอธิบายผลิตภัณฑ์ที่สร้างสรรค์แบบยาวสำหรับ: ##title## \n\nกลุ่มเป้าหมายคือ: ##audience## \n\nใช้คำอธิบายนี้:" .$description. "\n\nเสียงของข้อความที่สร้างขึ้นต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 434, 'template_id' => 12, 'key' => "uk-UA", 'value' => 'Напишіть довгий креативний опис продукту для: ##title## \n\nЦільова аудиторія: ##audience## \n\nВикористовуйте цей опис: ##description## \n\nТон створеного тексту має бути:\n ##tone_language## \n\n'],

            ['id' => 435, 'template_id' => 12, 'key' => "lt-LT", 'value' => 'Напишіть довгий креативний опис продукту для: ##title## \n\nЦільова аудиторія: ##audience## \n\nВикористовуйте цей опис: ##description## \n\nТон створеного тексту має бути:\n ##tone_language## \n\n'],

            ['id' => 436, 'template_id' => 12, 'key' => "bg-BG", 'value' => 'Напишете дълго творческо описание на продукта за: ##title## \n\nЦелевата аудитория е: ##audience## \n\nИзползвайте това описание: ##description## \n\nТонът на генерирания текст трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 437, 'template_id' => 13, 'key' => "en-US", 'value' => 'Generate cool, creative, and catchy names for startup description: ##description## \n\nSeed words: ##keywords## \n\n'],

            ['id' => 438, 'template_id' => 13, 'key' => "ar-AE", 'value' => 'أنشئ أسماء رائعة ومبتكرة وجذابة لوصف بدء التشغيل: ##description## \n\nكلمات المصدر: ##keywords## \n\n'],

            ['id' => 439, 'template_id' => 13, 'key' => "cmn-CN", 'value' => '为启动描述生成酷炫、有创意且朗朗上口的名称: ##description## \n\n种子词: ##keywords## \n\n'],

            ['id' => 440, 'template_id' => 13, 'key' => "hr-HR", 'value' => 'Generiraj cool, kreativna i privlačna imena za opis pokretanja: ##description## \n\nPočetne riječi: ##keywords## \n\n'],

            ['id' => 441, 'template_id' => 13, 'key' => "cs-CZ", 'value' => 'Vygenerujte skvělé, kreativní a chytlavé názvy pro popis spuštění: ##description## \n\nVýchozí slova: ##keywords## \n\n'],

            ['id' => 442, 'template_id' => 13, 'key' => "da-DK", 'value' => 'Generer seje, kreative og fængende navne til opstartsbeskrivelse: ##description## \n\nSeed ord: ##keywords## \n\n'],

            ['id' => 443, 'template_id' => 13, 'key' => "nl-NL", 'value' => 'Genereer coole, creatieve en pakkende namen voor opstartbeschrijving: ##description## \n\nZaalwoorden: ##keywords## \n\n'],

            ['id' => 444, 'template_id' => 13, 'key' => "et-EE", 'value' => 'Looge käivituskirjelduse jaoks lahedad, loomingulised ja meeldejäävad nimed: ##description## \n\nAlgussõnad: ##keywords## \n\n'],

            ['id' => 445, 'template_id' => 13, 'key' => "fi-FI", 'value' => 'Luo siistejä, luovia ja tarttuvia nimiä käynnistyksen kuvaukselle: ##description## \n\nSiemensanat: ##keywords## \n\n'],

            ['id' => 446, 'template_id' => 13, 'key' => "fr-FR", 'value' => 'Générez des noms sympas, créatifs et accrocheurs pour la description de démarrage : ##description## \n\nMots clés : ##keywords## \n\n'],

            ['id' => 447, 'template_id' => 13, 'key' => "de-DE", 'value' => 'Erzeuge coole, kreative und einprägsame Namen für die Startup-Beschreibung: ##description## \n\nStartwörter: ##keywords## \n\n'],

            ['id' => 448, 'template_id' => 13, 'key' => "el-GR", 'value' => 'Δημιουργήστε όμορφα, δημιουργικά και ελκυστικά ονόματα για περιγραφή εκκίνησης: ##description## \n\nΔείτε λέξεις: ##keywords## \n\n'],

            ['id' => 449, 'template_id' => 13, 'key' => "he-IL", 'value' => "ור שמות מגניבים, יצירתיים וקליטים לתיאור ההפעלה: ##description## \n\nמילות הזרע: ##keywords## \n\n"],

            ['id' => 450, 'template_id' => 13, 'key' => "hi-IN", 'value' => "स्टार्टअप विवरण के लिए बढ़िया, रचनात्मक और आकर्षक नाम उत्पन्न करें: ##description## \n\nबीज शब्द: ##keywords## \n\n"],

            ['id' => 451, 'template_id' => 13, 'key' => "hu-HU", 'value' => "Generál menő, kreatív és fülbemászó neveket az indítási leíráshoz: ##description## \n\nKezdőszavak: ##keywords## \n\n"],

            ['id' => 452, 'template_id' => 13, 'key' => "is-IS", 'value' => "Búðu til flott, skapandi og grípandi nöfn fyrir ræsingarlýsingu: ##description## \n\n Fræorð: ##keywords## \n\n"],

            ['id' => 453, 'template_id' => 13, 'key' => "id-ID", 'value' => "Hasilkan nama yang keren, kreatif, dan menarik untuk deskripsi startup: ##description## \n\nBenih kata: ##keywords## \n\n"],

            ['id' => 454, 'template_id' => 13, 'key' => "it-IT", 'value' => "Genera nomi interessanti, creativi e accattivanti per la descrizione dell avvio: ##description## \n\nParole seme: ##keywords## \n\n"],

            ['id' => 455, 'template_id' => 13, 'key' => "ja-JP", 'value' => "スタートアップの説明にクールでクリエイティブでキャッチーな名前を付けてください: ##description## \n\nシード ワード: ##keywords## \n\n"],

            ['id' => 456, 'template_id' => 13, 'key' => "ko-KR", 'value' => "스타트업 설명을 위한 멋지고 창의적이며 기억하기 쉬운 이름 생성: ##description## \n\n시드 단어: ##keywords## \n\n"],

            ['id' => 457, 'template_id' => 13, 'key' => "ms-MY", 'value' => "Jana nama yang keren, kreatif dan menarik untuk penerangan permulaan: ##description## \n\nPerkataan benih: ##keywords## \n\n"],

            ['id' => 458, 'template_id' => 13, 'key' => "nb-NO", 'value' => "Generer kule, kreative og fengende navn for oppstartsbeskrivelse: ##description## \n\nFrøord: ##keywords## \n\n"],

            ['id' => 459, 'template_id' => 13, 'key' => "pl-PL", 'value' => "Wygeneruj fajne, kreatywne i chwytliwe nazwy dla opisu startowego: ##description## \n\nSłowa źródłowe: ##keywords## \n\n"],

            ['id' => 460, 'template_id' => 13, 'key' => "pt-PT", 'value' => "Gerar nomes legais, criativos e atraentes para a descrição da inicialização: ##description## \n\nPalavras iniciais: ##keywords## \n\n"],

            ['id' => 461, 'template_id' => 13, 'key' => "ru-RU", 'value' => "Создавайте крутые, креативные и запоминающиеся названия для описания стартапа: ##description## \n\nИсходные слова: ##keywords## \n\n"],

            ['id' => 462, 'template_id' => 13, 'key' => "es-ES", 'value' => "Genera nombres geniales, creativos y pegadizos para la descripción de inicio: ##description## \n\nPalabras semilla: ##keywords## \n\n"],

            ['id' => 463, 'template_id' => 13, 'key' => "sv-SE", 'value' => "Skapa coola, kreativa och catchy namn för startbeskrivning: ##description## \n\nFröord: ##keywords## \n\n"],

            ['id' => 464, 'template_id' => 13, 'key' => "tr-TR", 'value' => "Başlangıç açıklaması için havalı, yaratıcı ve akılda kalıcı adlar oluşturun: ##description## \n\nÖz sözcükler: ##keywords## \n\n"],

            ['id' => 465, 'template_id' => 13, 'key' => "pt-BR", 'value' => "Gerar nomes legais, criativos e atraentes para a descrição da inicialização: ##description## \n\nPalavras iniciais: ##keywords## \n\n"],

            ['id' => 466, 'template_id' => 13, 'key' => "ro-RO", 'value' => "Generează nume interesante, creative și atrăgătoare pentru descrierea startup-ului: ##description## \n\nCuvinte semințe: ##keywords## \n\n"],

            ['id' => 467, 'template_id' => 13, 'key' => "vi-VN", 'value' => "Tạo tên thú vị, sáng tạo và hấp dẫn cho phần mô tả công ty khởi nghiệp:##description## \n\nTừ giống: ##keywords## \n\n"],

            ['id' => 468, 'template_id' => 13, 'key' => "sw-KE", 'value' => "Tengeneza majina mazuri, ya ubunifu na ya kuvutia kwa maelezo ya kuanza: ##description## \n\nManeno ya mbegu: ##keywords## \n\n"],

            ['id' => 469, 'template_id' => 13, 'key' => "sl-SI", 'value' => "Ustvarite kul, kreativna in privlačna imena za opis zagona: ##description## \n\nSemenske besede: ##keywords## \n\n"],

            ['id' => 470, 'template_id' => 13, 'key' => "th-TH", 'value' => "สร้างชื่อที่เจ๋ง สร้างสรรค์ และติดหูสำหรับคำอธิบายการเริ่มต้น: ##description## \n\nคำที่ใช้: ##keywords## \n\n"],

            ['id' => 471, 'template_id' => 13, 'key' => "uk-UA", 'value' => "Створюйте круті, креативні та привабливі назви для опису запуску: ##description## \n\nПочаткові слова: ##keywords## \n\n"],

            ['id' => 472, 'template_id' => 13, 'key' => "lt-LT", 'value' => "Generuokite šaunius, kūrybingus ir patrauklius paleidimo aprašymo pavadinimus:##description## \n\nPradiniai žodžiai: ##keywords## \n\n"],

            ['id' => 473, 'template_id' => 13, 'key' => "bg-BG", 'value' => "Генерирайте страхотни, креативни и запомнящи се имена за описание при стартиране: ##description## \n\nСедлови думи: ##keywords## \n\n"],

            ['id' => 474, 'template_id' => 14, 'key' => "en-US", 'value' => "Create creative product names:  ##description## \n\nSeed words: ##keywords## \n\n"],

            ['id' => 475, 'template_id' => 14, 'key' => "ar-AE", 'value' => "إنشاء أسماء المنتجات الإبداعية: ##description## \n\n كلمات المصدر: ##keywords## \n\n"],

            ['id' => 476, 'template_id' => 14, 'key' => "cmn-CN", 'value' => "创建有创意的产品名称： ##description## \n\n种子词：##keywords## \n\n"],

            ['id' => 477, 'template_id' => 14, 'key' => "hr-HR", 'value' => "Stvorite kreativne nazive proizvoda:  ##description## \n\nPočetne riječi: ##keywords## \n\n"],

            ['id' => 478, 'template_id' => 14, 'key' => "cs-CZ", 'value' => "Vytvořit názvy kreativních produktů:  ##description## \n\nVýchozí slova: ##keywords## \n\n"],

            ['id' => 479, 'template_id' => 14, 'key' => "da-DK", 'value' => "Opret kreative produktnavne: ##description## \n\nSeed ord: ##keywords## \n\n"],

            ['id' => 480, 'template_id' => 14, 'key' => "nl-NL", 'value' => "Creëer creatieve productnamen:  ##description## \n\nZaalwoorden: ##keywords## \n\n"],

            ['id' => 481, 'template_id' => 14, 'key' => "et-EE", 'value' => "Loo loomingulised tootenimed:  ##description## \n\nAlgussõnad: ##keywords## \n\n"],

            ['id' => 482, 'template_id' => 14, 'key' => "fi-FI", 'value' => "Luo luovia tuotenimiä:  ##description## \n\nSiemensanat: ##keywords## \n\n"],

            ['id' => 483, 'template_id' => 14, 'key' => "fr-FR", 'value' => "Créer des noms de produits créatifs :  ##description## \n\nMots clés : ##keywords## \n\n"],

            ['id' => 484, 'template_id' => 14, 'key' => "de-DE", 'value' => "Kreative Produktnamen erstellen:  ##description## \n\nStartwörter: ##keywords## \n\n"],

            ['id' => 485, 'template_id' => 14, 'key' => "el-GR", 'value' => "Δημιουργία δημιουργικών ονομάτων προϊόντων:  ##description## \n\nΔείτε λέξεις: ##keywords## \n\n"],

            ['id' => 486, 'template_id' => 14, 'key' => "he-IL", 'value' => "צור שמות מוצרים יצירתיים:  ##description## \n\nמילות הזרע: ##keywords## \n\n"],

            ['id' => 487, 'template_id' => 14, 'key' => "hi-IN", 'value' => "रचनात्मक उत्पाद नाम बनाएँ: ##description## \n\nबीज शब्द:##keywords## \n\n"],

            ['id' => 488, 'template_id' => 14, 'key' => "hu-HU", 'value' => "Kreatív terméknevek létrehozása:  ##description## \n\nKiinduló szavak: ##keywords## \n\n"],

            ['id' => 489, 'template_id' => 14, 'key' => "is-IS", 'value' => "Búa til skapandi vöruheiti: ##description## \n\nSeed orð: ##keywords## \n\n"],

            ['id' => 490, 'template_id' => 14, 'key' => "id-ID", 'value' => "Buat nama produk kreatif:  ##description## \n\nBenih kata: ##keywords## \n\n"],

            ['id' => 491, 'template_id' => 14, 'key' => "it-IT", 'value' => "Crea nomi di prodotti creativi:  ##description## \n\nParole iniziali: ##keywords## \n\n"],

            ['id' => 492, 'template_id' => 14, 'key' => "ja-JP", 'value' => "クリエイティブな商品名を作成する: ##description## \n\nシード ワード: ##keywords## \n\n"],

            ['id' => 493, 'template_id' => 14, 'key' => "ko-KR", 'value' => "창의적인 제품 이름 만들기:  ##description## \n\n시드 단어: ##keywords## \n\n"],

            ['id' => 494, 'template_id' => 14, 'key' => "ms-MY", 'value' => "Buat nama produk kreatif:  ##description## \n\nPerkataan benih: ##keywords## \n\n"],

            ['id' => 495, 'template_id' => 14, 'key' => "nb-NO", 'value' => "Lag kreative produktnavn:  ##description## \n\nSeed ord: ##keywords## \n\n"],

            ['id' => 496, 'template_id' => 14, 'key' => "pl-PL", 'value' => "Utwórz kreatywne nazwy produktów: ##description## \n\nSłowa źródłowe: ##keywords## \n\n"],

            ['id' => 497, 'template_id' => 14, 'key' => "pt-PT", 'value' => "Criar nomes de produtos criativos: ##description## \n\nPalavras iniciais: ##keywords## \n\n"],

            ['id' => 498, 'template_id' => 14, 'key' => "ru-RU", 'value' => "Создайте креативные названия продуктов:  ##description## \n\nИсходные слова: ##keywords## \n\n"],

            ['id' => 499, 'template_id' => 14, 'key' => "es-ES", 'value' => "Crear nombres de productos creativos:  ##description## \n\nPalabras semilla: ##keywords## \n\n"],

            ['id' => 500, 'template_id' => 14, 'key' => "sv-SE", 'value' => "Skapa kreativa produktnamn:  ##description## \n\nFröord: ##keywords## \n\n"],

            ['id' => 501, 'template_id' => 14, 'key' => "tr-TR", 'value' => "Yaratıcı ürün adları oluşturun: ##description## \n\nÖz sözcükler: ##keywords## \n\n"],

            ['id' => 502, 'template_id' => 14, 'key' => "pt-BR", 'value' => "Criar nomes de produtos criativos: ##description## \n\nPalavras iniciais: ##keywords## \n\n"],

            ['id' => 503, 'template_id' => 14, 'key' => "ro-RO", 'value' => "Creați nume de produse creative:  ##description## \n\nCuvinte semințe: ##keywords## \n\n"],

            ['id' => 504, 'template_id' => 14, 'key' => "vi-VN", 'value' => "Tạo tên sản phẩm sáng tạo:  ##description## \n\nTừ giống: ##keywords## \n\n"],

            ['id' => 505, 'template_id' => 14, 'key' => "sw-KE", 'value' => "Unda majina ya ubunifu ya bidhaa:  ##description## \n\nManeno ya mbegu: ##keywords## \n\n"],

            ['id' => 506, 'template_id' => 14, 'key' => "sl-SI", 'value' => "Ustvarite ustvarjalna imena izdelkov:  ##description## \n\nSemenske besede: ##keywords## \n\n"],

            ['id' => 507, 'template_id' => 14, 'key' => "th-TH", 'value' => "สร้างชื่อผลิตภัณฑ์อย่างสร้างสรรค์:##description## \n\nคำที่ใช้: ##keywords## \n\n"],

            ['id' => 508, 'template_id' => 14, 'key' => "uk-UA", 'value' => "Створіть творчі назви продуктів:  ##description## \n\nПочаткові слова: ##keywords## \n\n"],

            ['id' => 509, 'template_id' => 14, 'key' => "lt-LT", 'value' => "Sukurkite kūrybinius produktų pavadinimus:  ##description## \n\nPradiniai žodžiai: ##keywords## \n\n"],

            ['id' => 510, 'template_id' => 14, 'key' => "bg-BG", 'value' => "Създайте творчески имена на продукти:  ##description## \n\nСедлови думи: ##keywords## \n\n"],

            ['id' => 511, 'template_id' => 15, 'key' => "en-US", 'value' => "Write SEO meta description for:\n\n ##description## \n\nWebsite name is:\n ##title## \n\nSeed words:\n ##keywords## \n\n"],

            ['id' => 512, 'template_id' => 15, 'key' => "ar-AE", 'value' =>"اكتب وصف تعريف SEO لـ:\n\n ##description## \n\nاسم موقع الويب هو:\n ##title## \n\nكلمات المصدر:\n ##keywords## \n\n"],

            ['id' => 513, 'template_id' => 15, 'key' => "cmn-CN", 'value' => "为以下内容编写 SEO 元描述：\n\n ##description## \n\n 网站名称是：\n ##title## \n\n 种子词：\n ##keywords## \n\n"],

            ['id' => 514, 'template_id' => 15, 'key' => "hr-HR", 'value' => "Napišite SEO meta opis za:\n\n ##description## \n\n Naziv web stranice je:\n ##title## \n\n Početne riječi:\n ##keywords## \n\n"],

            ['id' => 515, 'template_id' => 15, 'key' => "cs-CZ", 'value' => "Zapsat SEO meta popis pro:\n\n ##description## \n\n Název webu je:\n ##title## \n\n Výchozí slova:\n ##keywords## \n\n"],

            ['id' => 516, 'template_id' => 15, 'key' => "da-DK", 'value' => "Skriv SEO-metabeskrivelse for:\n\n ##description## \n\n Webstedets navn er:\n ##title## \n\n Frøord:\n ##keywords## \n\n"],

            ['id' => 517, 'template_id' => 15, 'key' => "nl-NL", 'value' => "Schrijf SEO-metabeschrijving voor:\n\n ##description## \n\n Websitenaam is:\n ##title## \n\n Zaadwoorden:\n ##keywords## \n\n"],

            ['id' => 518, 'template_id' => 15, 'key' => "et-EE", 'value' => "Kirjutage SEO metakirjeldus:\n\n ##description## \n\n Veebisaidi nimi on:\n ##title## \n\n Algsõnad:\n ##keywords## \n\n"],

            ['id' => 519, 'template_id' => 15, 'key' => "fi-FI", 'value' => "Kirjoita hakukoneoptimoinnin metakuvaus kohteelle:\n\n ##description## \n\n Verkkosivuston nimi on:\n ##title## \n\n Alkusanat:\n ##keywords## \n\n"],

            ['id' => 520, 'template_id' => 15, 'key' => "fr-FR", 'value' => "Ecrire une méta description SEO pour :\n\n ##description## \n\n Le nom du site Web est :\n ##title## \n\n Mots clés :\n ##keywords## \n\n"],

            ['id' => 521, 'template_id' => 15, 'key' => "de-DE", 'value' => "SEO-Metabeschreibung schreiben für:\n\n ##description## \n\n Website-Name ist:\n ##title## \n\n Ausgangswörter:\n ##keywords## \n\n"],

            ['id' => 522, 'template_id' => 15, 'key' => "el-GR", 'value' => "Γράψτε μετα-περιγραφή SEO για:\n\n ##description## \n\n Το όνομα ιστότοπου είναι:\n ##title## \n\n Βασικές λέξεις:\n ##keywords## \n\n"],

            ['id' => 523, 'template_id' => 15, 'key' => "he-IL", 'value' => "כתוב מטא תיאור SEO עבור:\n\n ##description## \n\n שם האתר הוא:\n ##title## \n\n מילות זרע:\n ##keywords## \n\n"],

            ['id' => 524, 'template_id' => 15, 'key' => "hi-IN", 'value' => "इसके लिए SEO मेटा विवरण लिखें:\n\n ##description## \n\n वेबसाइट का नाम है:\n ##title## \n\n बीज शब्द:\n ##keywords## \n\n"],

            ['id' => 525, 'template_id' => 15, 'key' => "hu-HU", 'value' => "Írjon SEO meta leírást:\n\n ##description## \n\n A webhely neve:\n ##title## \n\n Kezdőszavak:\n ##keywords## \n\n"],

            ['id' => 526, 'template_id' => 15, 'key' => "is-IS", 'value' => "Skrifaðu SEO lýsilýsingu fyrir:\n\n ##description## \n\n Heiti vefsvæðis er:\n ##title## \n\n Fræorð:\n ##keywords## \n\n"],

            ['id' => 527, 'template_id' => 15, 'key' => "id-ID", 'value' => "Tulis deskripsi meta SEO untuk:\n\n ##description## \n\n Nama situs web adalah:\n ##title## \n\n Kata bibit:\n ##keywords## \n\n"],

            ['id' => 528, 'template_id' => 15, 'key' => "it-IT", 'value' => "Scrivi meta descrizione SEO per:\n\n ##description## \n\n Il nome del sito web è:\n ##title## \n\n Parole seme:\n ##keywords## \n\n"],

            ['id' => 529, 'template_id' => 15, 'key' => "ja-JP", 'value' => "以下の SEO メタ ディスクリプションを書き込みます:\n\n ##description## \n\n ウェブサイト名:\n ##title## \n\n シード ワード:\n ##keywords## \n\n"],

            ['id' => 530, 'template_id' => 15, 'key' => "ko-KR", 'value' => "다음에 대한 SEO 메타 설명 쓰기:\n\n ##description## \n\n 웹사이트 이름:\n ##title## \n\n 시드 단어:\n ##keywords## \n\n"],

            ['id' => 531, 'template_id' => 15, 'key' => "ms-MY", 'value' => "Tulis perihalan meta SEO untuk:\n\n ##description## \n\n Nama tapak web ialah:\n ##title## \n\n Perkataan benih:\n ##keywords## \n\n"],

            ['id' => 532, 'template_id' => 15, 'key' => "nb-NO", 'value' => "Skriv SEO-metabeskrivelse for:\n\n ##description## \n\n Nettstedets navn er:\n ##title## \n\n Frøord:\n ##keywords## \n\n"],

            ['id' => 533, 'template_id' => 15, 'key' => "pl-PL", 'value' => "Napisz metaopis SEO dla:\n\n ##description## \n\n Nazwa witryny to:\n ##title## \n\n Słowa źródłowe:\n ##keywords## \n\n"],

            ['id' => 534, 'template_id' => 15, 'key' => "pt-PT", 'value' => "Escreva a meta descrição de SEO para:\n\n ##description## \n\n O nome do site é:\n ##title## \n\n Palavras-chave:\n ##keywords## \n\n"],

            ['id' => 535, 'template_id' => 15, 'key' => "ru-RU", 'value' => "Напишите мета-описание SEO для:\n\n ##description## \n\n Имя веб-сайта:\n ##title## \n\n Исходные слова:\n ##keywords## \n\n"],

            ['id' => 536, 'template_id' => 15, 'key' => "es-ES", 'value' => "Escribir meta descripción SEO para:\n\n ##description## \n\n El nombre del sitio web es:\n ##title## \n\n Palabras semilla:\n ##keywords## \n\n"],

            ['id' => 537, 'template_id' => 15, 'key' => "sv-SE", 'value' => "Skriv SEO-metabeskrivning för:\n\n ##description## \n\n Webbplatsens namn är:\n ##title## \n\n Fröord:\n ##keywords## \n\n"],

            ['id' => 538, 'template_id' => 15, 'key' => "tr-TR", 'value' => "Şunun için SEO meta açıklaması yaz:\n\n ##description## \n\n Web sitesi adı:\n ##title## \n\n Çekirdek sözcükler:\n ##keywords## \n\n"],

            ['id' => 539, 'template_id' => 15, 'key' => "pt-BR", 'value' => "Escreva a meta descrição de SEO para:\n\n ##description## \n\n O nome do site é:\n ##title## \n\n Palavras-chave:\n ##keywords## \n\n"],

            ['id' => 540, 'template_id' => 15, 'key' => "ro-RO", 'value' => "Scrieți metadescriere SEO pentru:\n\n ##description## \n\nNumele site-ului este:\n ##title## \n\n nevoie de cuvinte:\n ##keywords## \n\n"],

            ['id' => 541, 'template_id' => 15, 'key' => "vi-VN", 'value' => "Viết mô tả meta SEO cho:\n\n ##description## \n\nTên trang web là:\n ##title## \n\ncần từ:\n ##keywords## \n\n"],

            ['id' => 542, 'template_id' => 15, 'key' => "sw-KE", 'value' => "Andika maelezo ya meta ya SEO ya:\n\n ##description## \n\nJina la tovuti ni:\n ##title## \n\nManeno ya mbegu:\n ##keywords## \n\n"],

            ['id' => 543, 'template_id' => 15, 'key' => "sl-SI", 'value' => "Napišite meta opis SEO za:\n\n ##description## \n\nIme spletne strani je:\n ##title## \n\nSemenske besede:\n ##keywords## \n\n"],

            ['id' => 544, 'template_id' => 15, 'key' => "th-TH", 'value' => "เขียนคำอธิบายเมตา SEO สำหรับ:\n\n ##description## \n\nชื่อเว็บไซต์คือ:\n ##title## \n\nคำเมล็ด:\n ##keywords## \n\n"],

            ['id' => 545, 'template_id' => 15, 'key' => "uk-UA", 'value' => "Напишіть метаопис SEO для:\n\n ##description## \n\nНазва сайту:\n ##title## \n\nНасіннєві слова:\n ##keywords## \n\n"],

            ['id' => 546, 'template_id' => 15, 'key' => "lt-LT", 'value' => "Parašykite SEO meta aprašymą:\n\n ##description## \n\nSvetainės pavadinimas yra:\n ##title## \n\nPradiniai žodžiai:\n ##keywords## \n\n"],

            ['id' => 547, 'template_id' => 15, 'key' => "bg-BG", 'value' => "Напишете SEO мета описание за:\n\n ##description## \n\nИмето на уебсайта е:\n ##title## \n\nЗародишни думи:\n ##keywords## \n\n"],

            ['id' => 548, 'template_id' => 16, 'key' => "en-US", 'value' => "Generate list of 10 frequently asked questions based on description:\n\n ##description## \n\n Product name:\n ##title## \n\n Tone of voice of the questions must be:\n ##tone_language## \n\n"],

            ['id' => 549, 'template_id' => 16, 'key' => "ar-AE", 'value' => "قم بإنشاء قائمة من 10 أسئلة متداولة بناءً على الوصف:\n\n ##description## \n\nاسم المنتج:\n ##title## \n\nيجب أن تكون نبرة صوت الأسئلة:\n ##tone_language## \n\n"],

            ['id' => 550, 'template_id' => 16, 'key' => "cmn-CN", 'value' => "根据描述生成 10 个常见问题列表：\n\n ##description## \n\n 产品名称：\n ##title## \n\n 提问的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 551, 'template_id' => 16, 'key' => "hr-HR", 'value' => "Generiraj popis od 10 često postavljanih pitanja na temelju opisa:\n\n ##description## \n\n Naziv proizvoda:\n ##title## \n\n Ton glasa pitanja mora biti:\n ##tone_language## \n\n"],

            ['id' => 552, 'template_id' => 16, 'key' => "cs-CZ", 'value' => "Vygenerujte seznam 10 často kladených otázek na základě popisu:\n\n ##description## \n\n Název produktu:\n ##title## \n\n Tón hlasu otázek musí být:\n ##tone_language## \n\n"],

            ['id' => 553, 'template_id' => 16, 'key' => "da-DK", 'value' => "Generer en liste med 10 ofte stillede spørgsmål baseret på beskrivelse:\n\n ##description## \n\n Produktnavn:\n ##title## \n\n Tonen i spørgsmålene skal være:\n ##tone_language## \n\n"],

            ['id' => 554, 'template_id' => 16, 'key' => "nl-NL", 'value' => "Genereer een lijst met 10 veelgestelde vragen op basis van beschrijving:\n\n ##description## \n\n Productnaam:\n ##title## \n\n Tone of voice van de vragen moet zijn:\n ##tone_language## \n\n"],

            ['id' => 555, 'template_id' => 16, 'key' => "et-EE", 'value' => "Loo kirjelduse põhjal 10 korduma kippuva küsimuse loend:\n\n ##description## \n\n Toote nimi:\n ##title## \n\n Küsimuste hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 556, 'template_id' => 16, 'key' => "fi-FI", 'value' => "Luo luettelo 10 usein kysytystä kysymyksestä kuvauksen perusteella:\n\n ##description## \n\n Tuotteen nimi:\n ##title## \n\n Kysymysten äänensävyn tulee olla:\n ##tone_language## \n\n"],

            ['id' => 557, 'template_id' => 16, 'key' => "fr-FR", 'value' => "Générer une liste de 10 questions fréquemment posées en fonction de la description :\n\n ##description## \n\n Nom du produit :\n ##title## \n\n Le ton de la voix des questions doit être :\n ##tone_language## \n\n"],

            ['id' => 558, 'template_id' => 16, 'key' => "de-DE", 'value' => "Erzeuge eine Liste mit 10 häufig gestellten Fragen basierend auf der Beschreibung:\n\n ##description## \n\n Produktname:\n ##title## \n\n Tonfall der Fragen muss sein:\n ##tone_language## \n\n"],

            ['id' => 559, 'template_id' => 16, 'key' => "el-GR", 'value' => "Δημιουργία λίστας 10 συχνών ερωτήσεων με βάση την περιγραφή:\n\n ##description## \n\n Όνομα προϊόντος:\n ##title## \n\n Ο τόνος της φωνής των ερωτήσεων πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 560, 'template_id' => 16, 'key' => "he-IL", 'value' => "צור רשימה של 10 שאלות נפוצות על סמך תיאור:\n\n ##description## \n\n שם המוצר:\n ##title## \n\n טון הדיבור של השאלות חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 561, 'template_id' => 16, 'key' => "hi-IN", 'value' => "विवरण के आधार पर अक्सर पूछे जाने वाले 10 प्रश्नों की सूची तैयार करें:\n\n ##description## \n\n उत्पाद का नाम:\n ##title## \n\n प्रश्नों का स्वर इस प्रकार होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 562, 'template_id' => 16, 'key' => "hu-HU", 'value' => "Létrehozzon 10 gyakran ismételt kérdést tartalmazó listát a leírás alapján:\n\n ##description## \n\n Terméknév:\n ##title## \n\n A kérdések hangnemének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 563, 'template_id' => 16, 'key' => "is-IS", 'value' => "Búðu til lista yfir 10 algengar spurningar byggðar á lýsingu:\n\n ##description## \n\n Vöruheiti:\n ##title## \n\n Röddtónn spurninganna verður að vera:\n ##tone_language## \n\n"],

            ['id' => 564, 'template_id' => 16, 'key' => "id-ID", 'value' => "Buat daftar 10 pertanyaan umum berdasarkan deskripsi:\n\n ##description## \n\n Nama produk:\n ##title## \n\n Nada suara pertanyaan harus:\n ##tone_language## \n\n"],

            ['id' => 565, 'template_id' => 16, 'key' => "it-IT", 'value' => "Genera elenco di 10 domande frequenti in base alla descrizione:\n\n ##description## \n\n Nome prodotto:\n ##title## \n\n Il tono di voce delle domande deve essere:\n ##tone_language## \n\n"],

            ['id' => 566, 'template_id' => 16, 'key' => "ja-JP", 'value' => "説明に基づいて 10 のよくある質問のリストを生成します:\n\n ##description## \n\n 製品名:\n ##title## \n\n 質問のトーンは次のようにする必要があります:\n ##tone_language## \n\n"],

            ['id' => 567, 'template_id' => 16, 'key' => "ko-KR", 'value' => "설명에 따라 자주 묻는 질문 10개 목록 생성:\n\n ##description## \n\n 제품 이름:\n ##title## \n\n 질문의 어조는 다음과 같아야 합니다.\n ##tone_language## \n\n"],

            ['id' => 568, 'template_id' => 16, 'key' => "ms-MY", 'value' => "Jana senarai 10 soalan lazim berdasarkan penerangan:\n\n ##description## \n\n Nama produk:\n ##title## \n\n Nada suara soalan mestilah:\n ##tone_language## \n\n"],

            ['id' => 569, 'template_id' => 16, 'key' => "nb-NO", 'value' => "Generer liste over 10 vanlige spørsmål basert på beskrivelse:\n\n ##description## \n\n Produktnavn:\n ##title## \n\n Tonen i spørsmålene må være:\n ##tone_language## \n\n"],

            ['id' => 570, 'template_id' => 16, 'key' => "pl-PL", 'value' => "Wygeneruj listę 10 najczęściej zadawanych pytań na podstawie opisu:\n\n ##description## \n\n Nazwa produktu:\n ##title## \n\n Ton pytań musi być następujący:\n ##tone_language## \n\n"],

            ['id' => 571, 'template_id' => 16, 'key' => "pt-PT", 'value' => "Gerar lista de 10 perguntas frequentes com base na descrição:\n\n ##description## \n\n Nome do produto:\n ##title## \n\n Tom de voz das perguntas deve ser:\n ##tone_language## \n\n"],

            ['id' => 572, 'template_id' => 16, 'key' => "ru-RU", 'value' => "Создать список из 10 часто задаваемых вопросов на основе описания:\n\n ##description## \n\n Название продукта:\n ##title## \n\n Тон вопросов должен быть:\n ##tone_language## \n\n"],

            ['id' => 573, 'template_id' => 16, 'key' => "es-ES", 'value' => "Generar una lista de 10 preguntas frecuentes según la descripción:\n\n ##description## \n\n Nombre del producto:\n ##title## \n\n El tono de voz de las preguntas debe ser:\n ##tone_language## \n\n"],

            ['id' => 574, 'template_id' => 16, 'key' => "sv-SE", 'value' => "Skapa en lista med 10 vanliga frågor baserat på beskrivning:\n\n ##description## \n\n Produktnamn:\n ##title## \n\n Tonen i frågorna måste vara:\n ##tone_language## \n\n"],

            ['id' => 575, 'template_id' => 16, 'key' => "tr-TR", 'value' => "Açıklamaya göre sık sorulan 10 sorudan oluşan bir liste oluşturun:\n\n ##description## \n\n Ürün adı:\n ##title## \n\n Soruların ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 576, 'template_id' => 16, 'key' => "pt-BR", 'value' => "Gerar lista de 10 perguntas frequentes com base na descrição:\n\n ##description## \n\n Nome do produto:\n ##title## \n\n Tom de voz das perguntas deve ser:\n ##tone_language## \n\n"],

            ['id' => 577, 'template_id' => 16, 'key' => "ro-RO", 'value' => "Generează o listă cu 10 întrebări frecvente pe baza descrierii:\n\n ##description## \n\n Nume produs:\n ##title## \n\n Tonul de voce al întrebărilor trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 578, 'template_id' => 16, 'key' => "vi-VN", 'value' => "Tạo danh sách 10 câu hỏi thường gặp dựa trên mô tả:\n\n ##description## \n\n Tên sản phẩm:\n ##title## \n\n Giọng điệu của câu hỏi phải là:\n ##tone_language## \n\n"],

            ['id' => 579, 'template_id' => 16, 'key' => "sw-KE", 'value' => "Tengeneza orodha ya maswali 10 yanayoulizwa mara kwa mara kulingana na maelezo:\n\n ##description## \n\n Jina la bidhaa:\n ##title## \n\n Toni ya sauti ya maswali lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 580, 'template_id' => 16, 'key' => "sl-SI", 'value' => "Ustvari seznam 10 pogosto zastavljenih vprašanj na podlagi opisa:\n\n ##description## \n\n Ime izdelka:\n ##title## \n\n Ton glasu vprašanj mora biti:\n ##tone_language## \n\n"],

            ['id' => 581, 'template_id' => 16, 'key' => "th-TH", 'value' => "สร้างรายการคำถามที่พบบ่อย 10 รายการตามคำอธิบาย:\n\n ##description## \n\n ชื่อผลิตภัณฑ์:\n ##title## \n\n เสียงของคำถามต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 582, 'template_id' => 16, 'key' => "uk-UA", 'value' => "Створити список із 10 поширених запитань на основі опису:\n\n ##description## \n\n Назва продукту:\n ##title## \n\n Тон голосу запитань має бути:\n ##tone_language## \n\n"],

            ['id' => 583, 'template_id' => 16, 'key' => "lt-LT", 'value' => "Sukurkite 10 dažniausiai užduodamų klausimų sąrašą pagal aprašymą:\n\n ##description## \n\n Produkto pavadinimas:\n ##title## \n\n Klausimų balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 584, 'template_id' => 16, 'key' => "bg-BG", 'value' => "Генериране на списък от 10 често задавани въпроса въз основа на описание:\n\n ##description## \n\n Име на продукта:\n ##title## \n\n Тонът на гласа на въпросите трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 585, 'template_id' => 17, 'key' => "en-US", 'value' => "Generate creative 5 answers to question:\n\n ##question## \n\n Product name:\n ##title## \n\n Product description:\n ##description## \n\n Tone of voice of the answers must be:\n ##tone_language## \n\n"],

            ['id' => 586, 'template_id' => 17, 'key' => "ar-AE", 'value' => "إنشاء 5 إجابات إبداعية على السؤال:\n\n ##question## \n\nاسم المنتج:\n ##title## \n\nوصف المنتج:\n ##description## \n\nيجب أن تكون نبرة صوت الإجابات:\n ##tone_language## \n\n"],

            ['id' => 587, 'template_id' => 17, 'key' => "cmn-CN", 'value' => "为问题生成有创意的 5 个答案：\n\n ##question## \n\n 产品名称：\n ##title## \n\n 产品描述：\n ##description## \n\n 回答的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 588, 'template_id' => 17, 'key' => "hr-HR", 'value' => "Generiraj kreativnih 5 odgovora na pitanje:\n\n ##question## \n\n Naziv proizvoda:\n ##title## \n\n Opis proizvoda:\n ##description## \n\n Ton glasa odgovora mora biti:\n ##tone_language## \n\n"],

            ['id' => 589, 'template_id' => 17, 'key' => "cs-CZ", 'value' => "Vygenerujte kreativu 5 odpovědí na otázku:\n\n ##question## \n\n Název produktu:\n ##title## \n\n Popis produktu:\n ##description## \n\n Tón hlasu odpovědí musí být:\n ##tone_language## \n\n"],

            ['id' => 590, 'template_id' => 17, 'key' => "da-DK", 'value' => "Generer kreative 5 svar på spørgsmål:\n\n ##question## \n\n Produktnavn:\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen i svarene skal være:\n ##tone_language## \n\n"],

            ['id' => 591, 'template_id' => 17, 'key' => "nl-NL", 'value' => "Genereer creatieve 5 antwoorden op vraag:\n\n ##question## \n\n Productnaam:\n ##title## \n\n Productbeschrijving:\n ##description## \n\n Tone of voice van de antwoorden moet zijn:\n ##tone_language## \n\n"],

            ['id' => 592, 'template_id' => 17, 'key' => "et-EE", 'value' => "Loo 5 loomingulist vastust küsimusele:\n\n ##question## \n\n Toote nimi:\n ##title## \n\n Toote kirjeldus:\n ##description## \n\n Vastuste hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 593, 'template_id' => 17, 'key' => "fi-FI", 'value' => "Luo luova 5 vastausta kysymykseen:\n\n ##question## \n\n Tuotteen nimi:\n ##title## \n\n Tuotteen kuvaus:\n ##description## \n\n Vastausten äänensävyn tulee olla:\n ##tone_language## \n\n"],

            ['id' => 594, 'template_id' => 17, 'key' => "fr-FR", 'value' => "Générer la création 5 réponses à la question :\n\n ##question## \n\n Nom du produit :\n ##title## \n\n Description du produit :\n ##description## \n\n Le ton de la voix des réponses doit être :\n ##tone_language## \n\n"],

            ['id' => 595, 'template_id' => 17, 'key' => "de-DE", 'value' => "Erzeuge kreative 5 Antworten auf Frage:\n\n ##question## \n\n Produktname:\n ##title## \n\n Produktbeschreibung:\n ##description## \n\n Tonfall der Antworten muss sein:\n ##tone_language## \n\n"],

            ['id' => 596, 'template_id' => 17, 'key' => "el-GR", 'value' => "Δημιουργία δημιουργικού 5 απαντήσεων στην ερώτηση:\n\n ##question## \n\n Όνομα προϊόντος:\n ##title## \n\n Περιγραφή προϊόντος:\n ##description## \n\n Ο τόνος της φωνής των απαντήσεων πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 597, 'template_id' => 17, 'key' => "he-IL", 'value' => "צור קריאייטיב 5 תשובות לשאלה:\n\n ##question## \n\n שם המוצר:\n ##title## \n\n תיאור המוצר:\n ##description## \n\n טון הדיבור של התשובות חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 598, 'template_id' => 17, 'key' => "hi-IN", 'value' => "प्रश्न के रचनात्मक 5 उत्तर उत्पन्न करें:\n\n ##question## \n\n उत्पाद का नाम:\n ##title## \n\n उत्पाद विवरण:\n ##description## \n\n जवाबों के स्वर इस प्रकार होने चाहिए:\n ##tone_language## \n\n"],

            ['id' => 599, 'template_id' => 17, 'key' => "hu-HU", 'value' => "Kreatív 5 válasz létrehozása a következő kérdésre:\n\n ##question## \n\n Terméknév:\n ##title## \n\n Termékleírás:\n ##description## \n\n A válaszok hangszínének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 600, 'template_id' => 17, 'key' => "is-IS", 'value' => "Búðu til skapandi 5 svör við spurningu:\n\n ##question## \n\n Vöruheiti:\n ##title## \n\n Vörulýsing:\n ##description## \n\n Röddtónn svara verður að vera:\n ##tone_language## \n\n"],

            ['id' => 601, 'template_id' => 17, 'key' => "id-ID", 'value' => "Hasilkan 5 jawaban kreatif untuk pertanyaan:\n\n ##question## \n\n Nama produk:\n ##title## \n\n Deskripsi produk:\n ##description## \n\n Nada suara jawaban harus:\n ##tone_language## \n\n"],

            ['id' => 602, 'template_id' => 17, 'key' => "it-IT", 'value' => "Genera creatività 5 risposte alla domanda:\n\n ##question## \n\n Nome prodotto:\n ##title## \n\n Descrizione del prodotto:\n ##description## \n\n Il tono di voce delle risposte deve essere:\n ##tone_language## \n\n"],

            ['id' => 603, 'template_id' => 17, 'key' => "ja-JP", 'value' => "質問に対する創造的な 5 つの回答を生成します:\n\n ##question## \n\n 製品名:\n ##title## \n\n 製品説明:\n ##description## \n\n 回答の声のトーンは次のとおりでなければなりません:\n ##tone_language## \n\n"],

            ['id' => 604, 'template_id' => 17, 'key' => "ko-KR", 'value' => "질문에 대한 창의적인 5개 답변 생성:\n\n ##question## \n\n 제품 이름:\n ##title## \n\n 제품 설명:\n ##description## \n\n 답변의 어조는 다음과 같아야 합니다.\n ##tone_language## \n\n"],

            ['id' => 605, 'template_id' => 17, 'key' => "ms-MY", 'value' => "Jana 5 jawapan kreatif untuk soalan:\n\n ##question## \n\n Nama produk:\n ##title## \n\n Penerangan produk:\n ##description## \n\n Nada suara jawapan mestilah:\n ##tone_language## \n\n"],

            ['id' => 606, 'template_id' => 17, 'key' => "nb-NO", 'value' => "Generer kreative 5 svar på spørsmål:\n\n ##question## \n\n Produktnavn:\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen til svarene må være:\n ##tone_language## \n\n"],

            ['id' => 607, 'template_id' => 17, 'key' => "pl-PL", 'value' => "Wygeneruj kreację 5 odpowiedzi na pytanie:\n\n ##question## \n\n Nazwa produktu:\n ##title## \n\n Opis produktu:\n ##description## \n\n Ton odpowiedzi musi być:\n ##tone_language## \n\n"],

            ['id' => 608, 'template_id' => 17, 'key' => "pt-PT", 'value' => "Gerar criativo 5 respostas para a pergunta:\n\n ##question## \n\n Nome do produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n Tom de voz das respostas deve ser:\n ##tone_language## \n\n"],

            ['id' => 609, 'template_id' => 17, 'key' => "ru-RU", 'value' => "Сгенерировать креативные 5 ответов на вопрос:\n\n ##question## \n\n Название продукта:\n ##title## \n\n Описание товара:\n ##description## \n\n Тон голоса ответов должен быть:\n ##tone_language## \п\п"],

            ['id' => 610, 'template_id' => 17, 'key' => "es-ES", 'value' => "Generar 5 respuestas creativas a la pregunta:\n\n ##question## \n\n Nombre del producto:\n ##title## \n\n Descripción del producto:\n ##description## \n\n El tono de voz de las respuestas debe ser:\n ##tone_language## \n\n"],

            ['id' => 611, 'template_id' => 17, 'key' => "sv-SE", 'value' => "Generera kreativa fem svar på frågan:\n\n ##question## \n\n Produktnamn:\n ##title## \n\n Produktbeskrivning:\n ##description## \n\n Tonen i svaren måste vara:\n ##tone_language## \n\n"],

            ['id' => 612, 'template_id' => 17, 'key' => "tr-TR", 'value' => "Soruya yaratıcı 5 yanıt oluşturun:\n\n ##question## \n\n Ürün adı:\n ##title## \n\n Ürün açıklaması:\n ##description## \n\n Cevapların ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 613, 'template_id' => 17, 'key' => "pt-BR", 'value' => "Gerar criativo 5 respostas para a pergunta:\n\n ##question## \n\n Nome do produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n Tom de voz das respostas deve ser:\n ##tone_language## \n\n"],

            ['id' => 614, 'template_id' => 17, 'key' => "ro-RO", 'value' => "Generează reclame 5 răspunsuri la întrebarea:\n\n ##question## \n\n Nume produs:\n ##title## \n\n Descrierea produsului:\n ##description## \n\n Tonul vocii răspunsurilor trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 615, 'template_id' => 17, 'key' => "vi-VN", 'value' => "Tạo 5 câu trả lời sáng tạo cho câu hỏi:\n\n ##question## \n\n Tên sản phẩm:\n ##title## \n\n Mô tả sản phẩm:\n ##description## \n\n Giọng điệu của câu trả lời phải là:\n ##tone_language## \n\n"],

            ['id' => 616, 'template_id' => 17, 'key' => "sw-KE", 'value' => "Toa majibu 5 ya ubunifu kwa swali:\n\n ##question## \n\n Jina la bidhaa:\n ##title## \n\n Maelezo ya bidhaa:\n ##description## \n\n Toni ya sauti ya majibu lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 617, 'template_id' => 17, 'key' => "sl-SI", 'value' => "Ustvari kreativnih 5 odgovorov na vprašanje:\n\n ##question## \n\n Ime izdelka:\n ##title## \n\n Opis izdelka:\n ##description## \n\n Ton glasu odgovorov mora biti:\n ##tone_language## \n\n"],

            ['id' => 618, 'template_id' => 17, 'key' => "th-TH", 'value' => "สร้างครีเอทีฟ 5 คำตอบสำหรับคำถาม:\n\n ##question## \n\n ชื่อผลิตภัณฑ์:\n ##title## \n\n รายละเอียดสินค้า:\n ##description## \n\n น้ำเสียงของคำตอบต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 619, 'template_id' => 17, 'key' => "uk-UA", 'value' => "Створіть творчі 5 відповідей на запитання:\n\n ##question## \n\n Назва продукту:\n ##title## \n\n Опис продукту:\n ##description## \n\n Тон голосу відповідей має бути:\n ##tone_language## \n\n"],

            ['id' => 620, 'template_id' => 17, 'key' => "lt-LT", 'value' => "Sukurkite 5 kūrybingus atsakymus į klausimą:\n\n ##question## \n\n Produkto pavadinimas:\n ##title## \n\n Produkto aprašymas:\n ##description## \n\n Atsakymų balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 621, 'template_id' => 17, 'key' => "bg-BG", 'value' => "Генерирайте творчески 5 отговора на въпрос:\n\n ##question## \n\n Име на продукта:\n ##title## \n\n Описание на продукта:\n ##description## \n\n Тонът на гласа на отговорите трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 622, 'template_id' => 18, 'key' => "en-US", 'value' => "Create 5 creative customer reviews for a product. Product name:\n\n ##title## \n\n Product description:\n ##description## \n\n Tone of voice of the customer review must be:\n ##tone_language## \n\n"],

            ['id' => 623, 'template_id' => 18, 'key' => "ar-AE", 'value' => "أنشئ 5 مراجعات إبداعية للعملاء لمنتج ما. اسم المنتج:\n\n ##title## \n\nوصف المنتج:\n ##description## \n\nيجب أن تكون نبرة صوت مراجعة العميل:\n ##tone_language## \n\n"],

            ['id' => 623, 'template_id' => 18, 'key' => "cmn-CN", 'value' => "为产品创建 5 个有创意的客户评论。产品名称：\n\n ##title## \n\n 产品描述：\n ##description## \n\n 客户评论的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 624, 'template_id' => 18, 'key' => "hr-HR", 'value' => "Stvorite 5 kreativnih korisničkih recenzija za proizvod. Naziv proizvoda:\n\n ##title## \n\n Opis proizvoda:\n ##description## \n\n Ton glasa klijentove recenzije mora biti:\n ##tone_language## \n\n"],

            ['id' => 625, 'template_id' => 18, 'key' => "cs-CZ", 'value' => "Vytvořte 5 kreativních zákaznických recenzí pro produkt. Název produktu:\n\n ##title## \n\n Popis produktu:\n ##description## \n\n Tón hlasu zákaznické recenze musí být:\n ##tone_language## \n\n"],

            ['id' => 626, 'template_id' => 18, 'key' => "da-DK", 'value' => "Opret 5 kreative kundeanmeldelser for et produkt. Produktnavn:\n\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen i kundeanmeldelsen skal være:\n ##tone_language## \n\n"],

            ['id' => 627, 'template_id' => 18, 'key' => "nl-NL", 'value' => "Maak 5 creatieve klantrecensies voor een product. Productnaam:\n\n ##title## \n\n Productbeschrijving:\n ##description## \n\n Tone of voice van de klantrecensie moet zijn:\n ##tone_language## \n\n"],

            ['id' => 628, 'template_id' => 18, 'key' => "et-EE", 'value' => "Looge toote kohta 5 loomingulist kliendiarvustust. Toote nimi:\n\n ##title## \n\n Toote kirjeldus:\n ##description## \n\n Kliendiarvustuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 629, 'template_id' => 18, 'key' => "fi-FI", 'value' => "Luo 5 luovaa asiakasarvostelua tuotteelle. Tuotteen nimi:\n\n ##title## \n\n Tuotteen kuvaus:\n ##description## \n\n Asiakasarvion äänensävyn on oltava:\n ##tone_language## \n\n"],

            ['id' => 630, 'template_id' => 18, 'key' => "fr-FR", 'value' => "Créez 5 avis clients créatifs pour un produit. Nom du produit :\n\n ##title## \n\n Description du produit :\n ##description## \n\n Le ton de la voix de l'avis client doit être :\n ##tone_language## \n\n"],

            ['id' => 631, 'template_id' => 18, 'key' => "de-DE", 'value' => "Erstellen Sie 5 kreative Kundenrezensionen für ein Produkt. Produktname:\n\n ##title## \n\n Produktbeschreibung:\n ##description## \n\n Tonfall der Kundenrezension muss sein:\n ##tone_language## \n\n"],

            ['id' => 632, 'template_id' => 18, 'key' => "el-GR", 'value' => "Δημιουργήστε 5 δημιουργικές κριτικές πελατών για ένα προϊόν. Όνομα προϊόντος:\n\n ##title## \n\n Περιγραφή προϊόντος:\n ##description## \n\n Ο ήχος της κριτικής πελάτη πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 633, 'template_id' => 18, 'key' => "he-IL", 'value' => "צור 5 ביקורות יצירתיות של לקוחות עבור מוצר. שם המוצר:\n\n ##title## \n\n תיאור המוצר:\n ##description## \n\n טון הדיבור של ביקורת הלקוח חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 634, 'template_id' => 18, 'key' => "hi-IN", 'value' => "एक उत्पाद के लिए 5 रचनात्मक ग्राहक समीक्षाएँ बनाएँ। उत्पाद का नाम:\n\n ##title## \n\n उत्पाद विवरण:\n ##description## \n\n ग्राहक समीक्षा का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 635, 'template_id' => 18, 'key' => "hu-HU", 'value' => "Hozzon létre 5 kreatív vásárlói véleményt egy termékről. Termék neve:\n\n ##title## \n\n Termékleírás:\n ##description## \n\n A vásárlói vélemény hangnemének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 636, 'template_id' => 18, 'key' => "is-IS", 'value' => "Búðu til 5 skapandi umsagnir viðskiptavina fyrir vöru. Vöruheiti:\n\n ##title## \n\n Vörulýsing:\n ##description## \n\n Rödd í umsögn viðskiptavina verður að vera:\n ##tone_language## \n\n"],

            ['id' => 637, 'template_id' => 18, 'key' => "id-ID", 'value' => "Buat 5 ulasan pelanggan yang kreatif untuk sebuah produk. Nama produk:\n\n ##title## \n\n Deskripsi produk:\n ##description## \n\n Nada suara ulasan pelanggan harus:\n ##tone_language## \n\n"],

            ['id' => 638, 'template_id' => 18, 'key' => "it-IT", 'value' => "Crea 5 recensioni cliente creative per un prodotto. Nome prodotto:\n\n ##title## \n\n Descrizione del prodotto:\n ##description## \n\n Il tono di voce della recensione del cliente deve essere:\n ##tone_language## \n\n"],

            ['id' => 639, 'template_id' => 18, 'key' => "ja-JP", 'value' => "商品のクリエイティブなカスタマー レビューを 5 つ作成します。商品名:\n\n ##title## \n\n 製品説明:\n ##description## \n\n カスタマー レビューの声調は次のとおりでなければなりません:\n ##tone_language## \n\n"],

            ['id' => 640, 'template_id' => 18, 'key' => "ko-KR", 'value' => "제품에 대해 5개의 창의적인 고객 리뷰를 작성하십시오. 제품 이름:\n\n ##title## \n\n 제품 설명:\n ##description## \n\n 고객 리뷰의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 641, 'template_id' => 18, 'key' => "ms-MY", 'value' => "Buat 5 ulasan pelanggan kreatif untuk produk. Nama produk:\n\n ##title## \n\n Penerangan produk:\n ##description## \n\n Nada suara ulasan pelanggan mestilah:\n ##tone_language## \n\n"],

            ['id' => 642, 'template_id' => 18, 'key' => "nb-NO", 'value' => "Lag 5 kreative kundeanmeldelser for et produkt. Produktnavn:\n\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen i kundeanmeldelsen må være:\n ##tone_language## \n\n"],

            ['id' => 643, 'template_id' => 18, 'key' => "pl-PL", 'value' => "Utwórz 5 kreatywnych recenzji klientów dla produktu. Nazwa produktu:\n\n ##title## \n\n Opis produktu:\n ##description## \n\n Ton opinii klienta musi być następujący:\n ##tone_language## \n\n"],

            ['id' => 644, 'template_id' => 18, 'key' => "pt-PT", 'value' => "Crie 5 avaliações criativas de clientes para um produto. Nome do produto:\n\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz da avaliação do cliente deve ser:\n ##tone_language## \n\n"],

            ['id' => 645, 'template_id' => 18, 'key' => "ru-RU", 'value' => "Создайте 5 креативных отзывов клиентов о продукте. Название продукта:\n\n ##title## \n\n Описание товара:\n ##description## \n\n Тон голоса отзыва клиента должен быть:\n ##tone_language## \n\n"],

            ['id' => 646, 'template_id' => 18, 'key' => "es-ES", 'value' => "Cree 5 reseñas creativas de clientes para un producto. Nombre del producto:\n\n ##title## \n\n Descripción del producto:\n ##description## \n\n El tono de voz de la reseña del cliente debe ser:\n ##tone_language## \n\n"],

            ['id' => 647, 'template_id' => 18, 'key' => "sv-SE", 'value' =>  "Skapa 5 kreativa kundrecensioner för en produkt. Produktnamn:\n\n ##title## \n\n Produktbeskrivning:\n ##description## \n\n Tonen i kundrecensionen måste vara:\n ##tone_language## \n\n"],

            ['id' => 648, 'template_id' => 18, 'key' => "tr-TR", 'value' => "Bir ürün için 5 yaratıcı müşteri yorumu oluşturun. Ürün adı:\n\n ##title## \n\n Ürün açıklaması:\n ##description## \n\n Müşteri incelemesinin ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 649, 'template_id' => 18, 'key' => "pt-BR", 'value' => "Crie 5 avaliações criativas de clientes para um produto. Nome do produto:\n\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz da avaliação do cliente deve ser:\n ##tone_language## \n\n"],

            ['id' => 650, 'template_id' => 18, 'key' => "ro-RO", 'value' => "Creați 5 recenzii creative ale clienților pentru un produs. Nume produs:\n\n ##title## \n\n Descrierea produsului:\n ##description## \n\n Tonul de voce al recenziei clientului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 651, 'template_id' => 18, 'key' => "vi-VN", 'value' => "Tạo 5 bài đánh giá sáng tạo của khách hàng cho một sản phẩm. Tên sản phẩm:\n\n ##title## \n\n Mô tả sản phẩm:\n ##description## \n\n Giọng điệu của bài đánh giá của khách hàng phải là:\n ##tone_language## \n\n"],

            ['id' => 652, 'template_id' => 18, 'key' => "sw-KE", 'value' => "Unda maoni 5 bunifu ya wateja kwa bidhaa. Jina la bidhaa:\n\n ##title## \n\n Maelezo ya bidhaa:\n ##description## \n\n Toni ya sauti ya ukaguzi wa mteja lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 653, 'template_id' => 18, 'key' => "sl-SI", 'value' => "Ustvarite 5 kreativnih ocen strank za izdelek. Ime izdelka:\n\n ##title## \n\n Opis izdelka:\n ##description## \n\n Ton ocene stranke mora biti:\n ##tone_language## \n\n"],

            ['id' => 654, 'template_id' => 18, 'key' => "th-TH", 'value' => "สร้างบทวิจารณ์จากลูกค้าเชิงสร้างสรรค์ 5 รายการสำหรับผลิตภัณฑ์หนึ่งๆ ชื่อผลิตภัณฑ์:\n\n ##title## \n\n รายละเอียดสินค้า:\n ##description## \n\n ความคิดเห็นของลูกค้าต้องเป็นเสียง:\n ##tone_language## \n\n"],

            ['id' => 655, 'template_id' => 18, 'key' => "uk-UA", 'value' => "Створіть 5 креативних відгуків клієнтів про продукт. Назва продукту:\n\n ##title## \n\n Опис продукту:\n ##description## \n\n Тон голосу відгуку клієнта повинен бути:\n ##tone_language## \n\n"],

            ['id' => 656, 'template_id' => 18, 'key' => "lt-LT", 'value' => "Sukurkite 5 kūrybingus klientų atsiliepimus apie produktą. Produkto pavadinimas:\n\n ##title## \n\n Produkto aprašymas:\n ##description## \n\n Kliento atsiliepimo balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 657, 'template_id' => 18, 'key' => "bg-BG", 'value' => "Създайте 5 креативни клиентски рецензии за продукт. Име на продукта:\n\n ##title## \n\n Описание на продукта:\n ##description## \n\n Тонът на рецензията на клиента трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 659, 'template_id' => 19, 'key' => "en-US", 'value' => "Write a creative ad for the following product to run on Facebook aimed at:\n\n ##audience## \n\n Product name:\n ##title## \n\n Product description:\n ##description## \n\n Tone of voice of the ad must be:\n ##tone_language## \n\n"],

            ['id' => 660, 'template_id' => 19, 'key' => "ar-AE", 'value' => "اكتب إعلانًا إبداعيًا للمنتج التالي ليتم تشغيله على Facebook بهدف:\n\n ##audience## \n\nاسم المنتج:\n ##title## \n\nوصف المنتج:\n ##description## \n\nيجب أن تكون نغمة صوت الإعلان:\n ##tone_language## \n\n"],

            ['id' => 661, 'template_id' => 19, 'key' => "cmn-CN", 'value' => "为以下产品编写创意广告以在 Facebook 上投放，目标是：\n\n ##audience## \n\n 产品名称：\n ##title## \n\n 产品描述：\n ##description## \n\n 广告语调必须是：\n ##tone_language## \n\n"],

            ['id' => 662, 'template_id' => 19, 'key' => "hr-HR", 'value' => "Napišite kreativni oglas za sljedeći proizvod za prikazivanje na Facebooku s ciljem:\n\n ##audience## \n\n Naziv proizvoda:\n ##title## \n\n Opis proizvoda:\n ##description## \n\n Ton glasa oglasa mora biti:\n ##tone_language## \n\n"],

            ['id' => 663, 'template_id' => 19, 'key' => "cs-CZ", 'value' => "Napište kreativní reklamu pro následující produkt, který se bude zobrazovat na Facebooku a je zaměřen na:\n\n ##audience## \n\n Název produktu:\n ##title## \n\n Popis produktu:\n ##description## \n\n Tón hlasu reklamy musí být:\n ##tone_language## \n\n"],

            ['id' => 664, 'template_id' => 19, 'key' => "da-DK", 'value' => "Skriv en kreativ annonce for følgende produkt til at køre på Facebook rettet mod:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen i annoncen skal være:\n ##tone_language## \n\n"],

            ['id' => 665, 'template_id' => 19, 'key' => "nl-NL", 'value' => "Schrijf een creatieve advertentie voor het volgende product voor weergave op Facebook gericht op:\n\n ##audience## \n\n Productnaam:\n ##title## \n\n Productbeschrijving:\n ##description## \n\n Tone of voice van de advertentie moet zijn:\n ##tone_language## \n\n"],

            ['id' => 666, 'template_id' => 19, 'key' => "et-EE", 'value' => "Kirjutage Facebookis esitamiseks järgmise toote loov reklaam, mille eesmärk on:\n\n ##audience## \n\n Toote nimi:\n ##title## \n\n Toote kirjeldus:\n ##description## \n\n Reklaami hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 667, 'template_id' => 19, 'key' => "fi-FI", 'value' => "Kirjoita luova mainos seuraavalle tuotteelle Facebookissa, jonka tarkoituksena on:\n\n ##audience## \n\n Tuotteen nimi:\n ##title## \n\n Tuotteen kuvaus:\n ##description## \n\n Mainoksen äänensävyn on oltava:\n ##tone_language## \n\n"],

            ['id' => 668, 'template_id' => 19, 'key' => "fr-FR", 'value' => "Rédigez une publicité créative pour le produit suivant à diffuser sur Facebook et destinée à :\n\n ##audience## \n\n Nom du produit :\n ##title## \n\n Description du produit :\n ##description## \n\n Le ton de la voix de l'annonce doit être :\n ##tone_language## \n\n"],

            ['id' => 669, 'template_id' => 19, 'key' => "de-DE", 'value' => "Schreiben Sie eine kreative Anzeige für das folgende Produkt, das auf Facebook geschaltet werden soll:\n\n ##audience## \n\n Produktname:\n ##title## \n\n Produktbeschreibung:\n ##description## \n\n Tonfall der Anzeige muss sein:\n ##tone_language## \n\n"],

            ['id' => 670, 'template_id' => 19, 'key' => "el-GR", 'value' => "Γράψτε μια δημιουργική διαφήμιση για το ακόλουθο προϊόν για προβολή στο Facebook με στόχο:\n\n ##audience## \n\n Όνομα προϊόντος:\n ##title## \n\n Περιγραφή προϊόντος:\n ##description## \n\n Ο τόνος της διαφήμισης πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 671, 'template_id' => 19, 'key' => "hi-IN", 'value' => "Facebook पर चलाने के लिए निम्नलिखित उत्पाद के लिए एक रचनात्मक विज्ञापन लिखें:\n\n ##audience## \n\n उत्पाद का नाम:\n ##title## \n\n उत्पाद विवरण:\n ##description## \n\n विज्ञापन का स्वर इस प्रकार होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 672, 'template_id' => 19, 'key' => "hu-HU", 'value' => "Írjon kreatív hirdetést a következő termékhez a Facebookon való futtatáshoz, amelynek célja:\n\n ##audience## \n\n Terméknév:\n ##title## \n\n Termékleírás:\n ##description## \n\n A hirdetés hangszínének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 673, 'template_id' => 19, 'key' => "is-IS", 'value' => "Skrifaðu skapandi auglýsingu fyrir eftirfarandi vöru til að birta á Facebook sem miðar að:\n\n ##audience## \n\n Vöruheiti:\n ##title## \n\n Vörulýsing:\n ##description## \n\n Röddtónn auglýsingarinnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 674, 'template_id' => 19, 'key' => "id-ID", 'value' => "Tulis iklan kreatif untuk produk berikut agar berjalan di Facebook yang ditujukan untuk:\n\n ##audience## \n\n Nama produk:\n ##title## \n\n Deskripsi produk:\n ##description## \n\n Nada suara iklan harus:\n ##tone_language## \n\n"],

            ['id' => 675, 'template_id' => 19, 'key' => "it-IT", 'value' => "Scrivi un annuncio creativo per il seguente prodotto da pubblicare su Facebook rivolto a:\n\n ##audience## \n\n Nome prodotto:\n ##title## \n\n Descrizione del prodotto:\n ##description## \n\n Il tono di voce dell'annuncio deve essere:\n ##tone_language## \n\n"],

            ['id' => 676, 'template_id' => 19, 'key' => "ja-JP", 'value' => "次の製品のクリエイティブ広告を作成して、Facebook で実行することを目的としています:\n\n ##audience## \n\n 製品名:\n ##title## \n\n 製品説明:\n ##description## \n\n 広告のトーンは次のようにする必要があります:\n ##tone_language## \n\n"],

            ['id' => 677, 'template_id' => 19, 'key' => "ko-KR", 'value' => "Facebook에서 실행할 다음 제품에 대한 크리에이티브 광고 작성:\n\n ##audience## \n\n 제품 이름:\n ##title## \n\n 제품 설명:\n ##description## \n\n 광고 음성 톤은 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 678, 'template_id' => 19, 'key' => "ms-MY", 'value' => "Tulis iklan kreatif untuk produk berikut untuk disiarkan di Facebook bertujuan:\n\n ##audience## \n\n Nama produk:\n ##title## \n\n Penerangan produk:\n ##description## \n\n Nada suara iklan mestilah:\n ##tone_language## \n\n"],

            ['id' => 679, 'template_id' => 19, 'key' => "nb-NO", 'value' => "Skriv en kreativ annonse for følgende produkt som skal kjøres på Facebook rettet mot:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen i annonsen må være:\n ##tone_language## \n\n"],

            ['id' => 680, 'template_id' => 19, 'key' => "pl-PL", 'value' => "Napisz kreatywną reklamę następującego produktu do wyświetlania na Facebooku, skierowaną do:\n\n ##audience## \n\n Nazwa produktu:\n ##title## \n\n Opis produktu:\n ##description## \n\n Ton reklamy musi być:\n ##tone_language## \n\n"],

            ['id' => 681, 'template_id' => 19, 'key' => "pt-PT", 'value' => "Escreva um anúncio criativo para o seguinte produto para exibição no Facebook destinado a:\n\n ##audience## \n\n Nome do produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do anúncio deve ser:\n ##tone_language## \n\n"],

            ['id' => 682, 'template_id' => 19, 'key' => "ru-RU", 'value' => "Напишите креативную рекламу следующего продукта для показа на Facebook, нацеленную на:\n\n ##audience## \n\n Название продукта:\n ##title## \n\n Описание товара:\n ##description## \n\n Тон объявления должен быть:\n ##tone_language## \n\n"],

            ['id' => 683, 'template_id' => 19, 'key' => "es-ES", 'value' => "Escriba un anuncio creativo para que el siguiente producto se publique en Facebook dirigido a:\n\n ##audience## \n\n Nombre del producto:\n ##title## \n\n Descripción del producto:\n ##description## \n\n El tono de voz del anuncio debe ser:\n ##tone_language## \n\n"],

            ['id' => 684, 'template_id' => 19, 'key' => "sv-SE", 'value' => "Skriv en kreativ annons för följande produkt som ska visas på Facebook som syftar till:\n\n ##audience## \n\n Produktnamn:\n ##title## \n\n Produktbeskrivning:\n ##description## \n\n Tonen i annonsen måste vara:\n ##tone_language## \n\n"],

            ['id' => 685, 'template_id' => 19, 'key' => "tr-TR", 'value' => "Aşağıdaki ürün için Facebook'ta yayınlanması hedeflenen yaratıcı bir reklam yazın:\n\n ##audience## \n\n Ürün adı:\n ##title## \n\n Ürün açıklaması:\n ##description## \n\n Reklamın ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 686, 'template_id' => 19, 'key' => "pt-BR", 'value' => "Escreva um anúncio criativo para o seguinte produto para exibição no Facebook destinado a:\n\n ##audience## \n\n Nome do produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do anúncio deve ser:\n ##tone_language## \n\n"],

            ['id' => 687, 'template_id' => 19, 'key' => "ro-RO", 'value' => "Scrieți un anunț creativ pentru următorul produs, care să fie difuzat pe Facebook, care vizează:\n\n ##audience## \n\n Nume produs:\n ##title## \n\n Descrierea produsului:\n ##description## \n\n Tonul vocii al anunțului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 688, 'template_id' => 19, 'key' => "vi-VN", 'value' => "Viết quảng cáo sáng tạo cho sản phẩm sau để chạy trên Facebook nhằm mục đích:\n\n ##audience## \n\n Tên sản phẩm:\n ##title## \n\n Mô tả sản phẩm:\n ##description## \n\n Giọng điệu của quảng cáo phải là:\n ##tone_language## \n\n"],

            ['id' => 689, 'template_id' => 19, 'key' => "sw-KE", 'value' => "Andika tangazo la ubunifu ili bidhaa ifuatayo ionyeshwe kwenye Facebook inayolenga:\n\n ##audience## \n\n Jina la bidhaa:\n ##title## \n\n Maelezo ya bidhaa:\n ##description## \n\n Toni ya sauti ya tangazo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 690, 'template_id' => 19, 'key' => "sl-SI", 'value' => "Napišite kreativni oglas za naslednji izdelek za prikazovanje na Facebooku, namenjen:\n\n ##audience## \n\n Ime izdelka:\n ##title## \n\n Opis izdelka:\n ##description## \n\n Ton glasu oglasa mora biti:\n ##tone_language## \n\n"],

            ['id' => 691, 'template_id' => 19, 'key' => "th-TH", 'value' => "เขียนโฆษณาที่สร้างสรรค์สำหรับผลิตภัณฑ์ต่อไปนี้เพื่อทำงานบน Facebook โดยมุ่งเป้าไปที่:\n\n ##audience## \n\n ชื่อผลิตภัณฑ์:\n ##title## \n\n รายละเอียดสินค้า:\n ##description## \n\n น้ำเสียงของโฆษณาต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 692, 'template_id' => 19, 'key' => "uk-UA", 'value' => "Напишіть креативне оголошення для наступного продукту для показу на Facebook, спрямоване на:\n\n ##audience## \n\n Назва продукту:\n ##title## \n\n Опис продукту:\n ##description## \n\n Тон голосу оголошення має бути:\n ##tone_language## \n\n"],

            ['id' => 693, 'template_id' => 19, 'key' => "lt-LT", 'value' => "Parašykite šio produkto kūrybinį skelbimą, kad jis būtų rodomas „Facebook“, skirtas:\n\n ##audience## \n\n Produkto pavadinimas:\n ##title## \n\n Produkto aprašymas:\n ##description## \n\n Skelbimo balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 694, 'template_id' => 19, 'key' => "bg-BG", 'value' => "Напишете творческа реклама за следния продукт, която да се пусне във Facebook и е насочена към:\n\n ##audience## \n\n Име на продукта:\n ##title## \n\n Описание на продукта:\n ##description## \n\n Тонът на гласа на рекламата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 695, 'template_id' => 20, 'key' => "en-US", 'value' => "Write compelling YouTube description to get people interested in watching.\n\nVideo description:\n ##description## \n\nTone of voice of the video description must be:\n ##tone_language## \n\n"],

            ['id' => 696, 'template_id' => 20, 'key' => "ar-AE", 'value' => "اكتب وصفًا مقنعًا على YouTube لجذب اهتمام الأشخاص بالمشاهدة.\n\n وصف الفيديو:\n ##description## \n\nيجب أن تكون نغمة الصوت في وصف الفيديو:\n ##tone_language## \n\n"],

            ['id' => 697, 'template_id' => 20, 'key' => "cmn-CN", 'value' => "撰写引人注目的 YouTube 说明，让人们有兴趣观看。\n\n视频说明：\n ##description## \n\n视频描述的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 698, 'template_id' => 20, 'key' => "hr-HR", 'value' => "Napišite uvjerljiv YouTube opis kako biste zainteresirali ljude za gledanje.\n\nOpis videozapisa:\n ##description## \n\nTon glasa opisa videa mora biti:\n ##tone_language## \n\n"],

            ['id' => 699, 'template_id' => 20, 'key' => "cs-CZ", 'value' => "Napište působivý popis na YouTube, aby se lidé zajímali o sledování.\n\nPopis videa:\n ##description## \n\nTón hlasu popisu videa musí být:\n ##tone_language## \n\n"],

            ['id' => 700, 'template_id' => 20, 'key' => "da-DK", 'value' => "Skriv en overbevisende YouTube-beskrivelse for at få folk interesseret i at se.\n\nVideobeskrivelse:\n ##description## \n\nTone i videobeskrivelsen skal være:\n ##tone_language## \n\n"],

            ['id' => 701, 'template_id' => 20, 'key' => "nl-NL", 'value' => "Schrijf een overtuigende YouTube-beschrijving om mensen te interesseren om te kijken.\n\nVideobeschrijving:\n ##description## \n\nDe toon van de videobeschrijving moet zijn:\n ##tone_language## \n\n"],

            ['id' => 702, 'template_id' => 20, 'key' => "et-EE", 'value' => "Kirjutage ahvatlev YouTube'i kirjeldus, et tekitada inimestes vaatamisest huvi.\n\nVideo kirjeldus:\n ##description## \n\nVideo kirjelduse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 703, 'template_id' => 20, 'key' => "fi-FI", 'value' => "Kirjoita houkutteleva YouTube-kuvaus saadaksesi ihmiset kiinnostumaan katselusta.\n\nVideon kuvaus:\n ##description## \n\nVideon kuvauksen äänensävyn on oltava:\n ##tone_language## \n\n"],

            ['id' => 704, 'template_id' => 20, 'key' => "fr-FR", 'value' => "Rédigez une description YouTube convaincante pour intéresser les internautes.\n\nDescription de la vidéo :\n ##description## \n\nLe ton de la description de la vidéo doit être :\n ##tone_language## \n\n"],

            ['id' => 705, 'template_id' => 20, 'key' => "de-DE", 'value' => "Schreiben Sie eine überzeugende YouTube-Beschreibung, um das Interesse der Zuschauer zu wecken.\n\nVideobeschreibung:\n ##description## \n\nTonfall der Videobeschreibung muss sein:\n ##tone_language## \n\n"],

            ['id' => 706, 'template_id' => 20, 'key' => "el-GR", 'value' => "Γράψτε μια συναρπαστική περιγραφή στο YouTube για να κάνετε τους ανθρώπους να ενδιαφέρονται να παρακολουθήσουν.\n\nΠεριγραφή βίντεο:\n ##description## \n\nΟ τόνος της φωνής της περιγραφής του βίντεο πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 707, 'template_id' => 20, 'key' => "he-IL", 'value' => "כתוב תיאור משכנע של YouTube כדי לגרום לאנשים להתעניין בצפייה.\n\nתיאור הסרטון:\n ##description## \n\nטון הדיבור של תיאור הסרטון חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 708, 'template_id' => 20, 'key' => "hi-IN", 'value' => "लोगों को देखने में रुचि लेने के लिए आकर्षक YouTube विवरण लिखें।\n\nवीडियो विवरण:\n ##description## \n\nवीडियो विवरण का स्वर इस प्रकार होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 709, 'template_id' => 20, 'key' => "hu-HU", 'value' => "Írjon lenyűgöző YouTube-leírást, hogy felkeltse az emberek érdeklődését a megtekintés iránt.\n\nVideó leírása:\n ##description## \n\nA videó leírásának hangszínének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 710, 'template_id' => 20, 'key' => "is-IS", 'value' => "Skrifaðu sannfærandi YouTube lýsingu til að vekja áhuga fólks á að horfa.\n\nLýsing myndskeiðs:\n ##description## \n\nTónn í lýsingu myndbandsins verður að vera:\n ##tone_language## \n\n"],

            ['id' => 711, 'template_id' => 20, 'key' => "id-ID", 'value' => "Tulis deskripsi YouTube yang menarik agar orang tertarik menonton.\n\nDeskripsi video:\n ##description## \n\nNada suara deskripsi video harus:\n ##tone_language## \n\n"],

            ['id' => 712, 'template_id' => 20, 'key' => "it-IT", 'value' => "Scrivi una descrizione accattivante per YouTube per attirare l'interesse delle persone a guardarlo.\n\nDescrizione del video:\n ##description## \n\nIl tono di voce della descrizione del video deve essere:\n ##tone_language## \n\n"],

            ['id' => 713, 'template_id' => 20, 'key' => "ja-JP", 'value' => "魅力的な YouTube の説明を書いて、視聴者に興味を持ってもらいましょう。\n\n動画の説明:\n ##description## \n\nビデオの説明のトーンは次のようにする必要があります:\n ##tone_language## \n\n"],

            ['id' => 714, 'template_id' => 20, 'key' => "ko-KR", 'value' => "사람들이 시청에 관심을 갖도록 설득력 있는 YouTube 설명을 작성하세요.\n\n동영상 설명:\n ##description## \n\n동영상 설명의 음성 톤은 다음과 같아야 합니다.\n ##tone_language## \n\n"],

            ['id' => 715, 'template_id' => 20, 'key' => "ms-MY", 'value' => "Tulis perihalan YouTube yang menarik untuk menarik minat orang untuk menonton.\n\nPerihalan video:\n ##description## \n\nNada suara penerangan video mestilah:\n ##tone_language## \n\n"],

            ['id' => 716, 'template_id' => 20, 'key' => "nb-NO", 'value' => "Skriv overbevisende YouTube-beskrivelse for å få folk interessert i å se.\n\nVideobeskrivelse:\n ##description## \n\nTone i videobeskrivelsen må være:\n ##tone_language## \n\n"],

            ['id' => 717, 'template_id' => 20, 'key' => "pl-PL", 'value' => "Napisz przekonujący opis w YouTube, aby zainteresować ludzi oglądaniem.\n\nOpis filmu:\n ##description## \n\nTon głosu w opisie filmu musi być:\n ##tone_language## \n\n"],

            ['id' => 718, 'template_id' => 20, 'key' => "pt-PT", 'value' => "Escreva uma descrição atraente do YouTube para atrair o interesse das pessoas em assistir.\n\nDescrição do vídeo:\n ##description## \n\nTom de voz da descrição do vídeo deve ser:\n ##tone_language## \n\n"],

            ['id' => 719, 'template_id' => 20, 'key' => "ru-RU", 'value' => "Напишите привлекательное описание для YouTube, чтобы заинтересовать людей.\n\nОписание видео:\n ##description## \n\nТон описания видео должен быть:\n ##tone_language## \п\п"],

            ['id' => 720, 'template_id' => 20, 'key' => "es-ES", 'value' => "Escribe una descripción convincente de YouTube para que las personas se interesen en verlo.\n\nDescripción del video:\n ##description## \n\nEl tono de voz de la descripción del video debe ser:\n ##tone_language## \n\n"],

            ['id' => 721, 'template_id' => 20, 'key' => "sv-SE", 'value' => "Skriv övertygande YouTube-beskrivning för att få folk intresserade av att titta.\n\nVideobeskrivning:\n ##description## \n\nRösttonen i videobeskrivningen måste vara:\n ##tone_language## \n\n"],

            ['id' => 722, 'template_id' => 20, 'key' => "tr-TR", 'value' => "İnsanların izlemekle ilgilenmesini sağlamak için etkileyici bir YouTube açıklaması yazın.\n\nVideo açıklaması:\n ##description## \n\nVideo açıklamasının ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 723, 'template_id' => 20, 'key' => "pt-BR", 'value' => "Escreva uma descrição atraente do YouTube para atrair o interesse das pessoas em assistir.\n\nDescrição do vídeo:\n ##description## \n\nTom de voz da descrição do vídeo deve ser:\n ##tone_language## \n\n"],

            ['id' => 724, 'template_id' => 20, 'key' => "ro-RO", 'value' => "Scrieți o descriere YouTube convingătoare pentru a-i determina pe oameni să fie interesați de vizionare.\n\nDescrierea videoclipului:\n ##description## \n\nTonul de voce al descrierii videoclipului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 725, 'template_id' => 20, 'key' => "vi-VN", 'value' => "Viết mô tả YouTube hấp dẫn để thu hút mọi người thích xem.\n\nMô tả video:\n ##description## \n\nGiọng điệu của mô tả video phải là:\n ##tone_language## \n\n"],

            ['id' => 726, 'template_id' => 20, 'key' => "sw-KE", 'value' => "Andika maelezo ya YouTube ya kuvutia ili kuwavutia watu kutazama.\n\nMaelezo ya video:\n ##description## \n\nToni ya sauti ya maelezo ya video lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 727, 'template_id' => 20, 'key' => "sl-SI", 'value' => "Napišite privlačen opis YouTube, da boste ljudi zanimali za ogled.\n\nOpis videa:\n ##description## \n\nTon glasu opisa videa mora biti:\n ##tone_language## \n\n"],

            ['id' => 728, 'template_id' => 20, 'key' => "th-TH", 'value' => "เขียนคำอธิบาย YouTube ที่น่าสนใจเพื่อให้ผู้คนสนใจรับชม\n\n คำอธิบายวิดีโอ:\n ##description## \nเสียงของคำอธิบายวิดีโอต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 729, 'template_id' => 20, 'key' => "uk-UA", 'value' => "Напишіть переконливий опис YouTube, щоб зацікавити людей переглядом.\n\n Опис відео:\n ##description## \n\nТон опису відео має бути:\n ##tone_language## \n\n"],

            ['id' => 730, 'template_id' => 20, 'key' => "lt-LT", 'value' => "Parašykite patrauklų „YouTube“ aprašą, kad žmonės susidomėtų žiūrėti.\n\n Vaizdo įrašo aprašymas:\n ##description## \n\Vaizdo įrašo aprašymo balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 731, 'template_id' => 20, 'key' => "bg-BG", 'value' => "Напишете завладяващо описание в YouTube, за да накарате хората да се заинтересуват да гледат.\n\n Описание на видеоклипа:\n ##description## \n\nТонът на гласа на видео описанието трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 732, 'template_id' => 21, 'key' => "en-US", 'value' => "Write compelling YouTube video title for the provided video description to get people interested in watching:\n\nVideo description:\n ##description## \n\n"],

            ['id' => 733, 'template_id' => 21, 'key' => "ar-AE", 'value' => "اكتب عنوان فيديو YouTube مقنعًا لوصف الفيديو المقدم لجذب اهتمام الأشخاص بالمشاهدة:\n\nوصف الفيديو:\n ##description## \n\n"],

            ['id' => 734, 'template_id' => 21, 'key' => "cmn-CN", 'value' => "为提供的视频描述写引人注目的 YouTube 视频标题，以引起人们对观看的兴趣：\n\n视频描述：\n ##description## \n\n"],

            ['id' => 735, 'template_id' => 21, 'key' => "hr-HR", 'value' => "Napišite uvjerljiv naslov YouTube videozapisa za navedeni opis videozapisa kako biste zainteresirali ljude za gledanje:\n\nOpis videozapisa:\n ##description## \n\n"],

            ['id' => 736, 'template_id' => 21, 'key' => "cs-CZ", 'value' => "K poskytnutému popisu videa napište působivý název videa na YouTube, aby lidi zaujalo sledování:\n\nPopis videa:\n ##description## \n\n"],

            ['id' => 737, 'template_id' => 21, 'key' => "da-DK", 'value' => "Skriv en overbevisende YouTube-videotitel til den medfølgende videobeskrivelse for at få folk interesseret i at se:\n\nVideobeskrivelse:\n ##description## \n\n"],

            ['id' => 738, 'template_id' => 21, 'key' => "nl-NL", 'value' => "Schrijf een pakkende YouTube-videotitel voor de verstrekte videobeschrijving om mensen te interesseren om te kijken:\n\nVideobeschrijving:\n ##description## \n\n"],

            ['id' => 739, 'template_id' => 21, 'key' => "et-EE", 'value' => "Kirjutage esitatud video kirjeldusele köitev YouTube'i video pealkiri, et tekitada inimestes huvi vaatamise vastu:\n\nVideo kirjeldus:\n ##description## \n\n"],

            ['id' => 740, 'template_id' => 21, 'key' => "fi-FI", 'value' => "Kirjoita houkutteleva YouTube-videon nimi annetulle videon kuvaukselle saadaksesi ihmiset kiinnostumaan:\n\nVideon kuvaus:\n ##description## \n\n"],

            ['id' => 741, 'template_id' => 21, 'key' => "fr-FR", 'value' => "Écrivez un titre de vidéo YouTube convaincant pour la description de la vidéo fournie afin d'intéresser les gens à regarder :\n\nDescription de la vidéo :\n ##description## \n\n"],

            ['id' => 742, 'template_id' => 21, 'key' => "de-DE", 'value' => "Schreiben Sie einen überzeugenden YouTube-Videotitel für die bereitgestellte Videobeschreibung, um das Interesse der Zuschauer zu wecken:\n\nVideobeschreibung:\n ##description## \n\n"],

            ['id' => 743, 'template_id' => 21, 'key' => "el-GR", 'value' => "Γράψτε τον συναρπαστικό τίτλο του βίντεο YouTube για την παρεχόμενη περιγραφή του βίντεο για να ενθαρρύνετε τους χρήστες να το παρακολουθήσουν:\n\nΠεριγραφή βίντεο:\n ##description## \n\n"],

            ['id' => 744, 'template_id' => 21, 'key' => "he-IL", 'value' => "כתוב כותרת סרטון YouTube משכנעת עבור תיאור הסרטון שסופק כדי לגרום לאנשים להתעניין בצפייה:\n\nתיאור הסרטון:\n ##description## \n\n"],

            ['id' => 745, 'template_id' => 21, 'key' => "hi-IN", 'value' => "लोगों को देखने में रुचि लेने के लिए प्रदान किए गए वीडियो विवरण के लिए आकर्षक YouTube वीडियो शीर्षक लिखें:\n\nवीडियो विवरण:\n ##description## \n\n"],

            ['id' => 746, 'template_id' => 21, 'key' => "hu-HU", 'value' => "Írjon lenyűgöző YouTube-videócímet a mellékelt videó leírásához, hogy felkeltse az emberek érdeklődését a megtekintés iránt:\n\nVideó leírása:\n ##description## \n\n"],

            ['id' => 747, 'template_id' => 21, 'key' => "is-IS", 'value' => "Skrifaðu sannfærandi titil á YouTube vídeói fyrir vídeólýsinguna sem fylgir til að vekja áhuga fólks á að horfa á:\n\nLýsing myndskeiðs:\n ##description## \n\n"],

            ['id' => 748, 'template_id' => 21, 'key' => "id-ID", 'value' => "Tulis judul video YouTube yang menarik untuk deskripsi video yang diberikan agar orang-orang tertarik untuk menonton:\n\nDeskripsi video:\n ##description## \n\n"],

            ['id' => 749, 'template_id' => 21, 'key' => "it-IT", 'value' => "Scrivi un titolo del video di YouTube convincente per la descrizione del video fornita per attirare l'interesse delle persone a guardarlo:\n\nDescrizione del video:\n ##description## \n\n"],

            ['id' => 750, 'template_id' => 21, 'key' => "ja-JP", 'value' => "視聴者に興味を持ってもらうために、提供された動画の説明に説得力のある YouTube 動画のタイトルを書いてください:\n\n動画の説明:\n ##description## \n\n"],

            ['id' => 751, 'template_id' => 21, 'key' => "ko-KR", 'value' => "사람들이 시청에 관심을 갖도록 제공된 동영상 설명에 대한 매력적인 YouTube 동영상 제목을 작성하세요:\n\n동영상 설명:\n ##description## \n\n"],

            ['id' => 752, 'template_id' => 21, 'key' => "ms-MY", 'value' => "Tulis tajuk video YouTube yang menarik untuk penerangan video yang disediakan untuk menarik minat orang untuk menonton:\n\nPerihalan video:\n ##description## \n\n"],

            ['id' => 753, 'template_id' => 21, 'key' => "nb-NO", 'value' => "Skriv en overbevisende YouTube-videotittel for den oppgitte videobeskrivelsen for å få folk interessert i å se:\n\nVideobeskrivelse:\n ##description## \n\n"],

            ['id' => 754, 'template_id' => 21, 'key' => "pl-PL", 'value' => "Napisz przekonujący tytuł filmu YouTube dla podanego opisu filmu, aby zainteresować ludzi oglądaniem:\n\nOpis filmu:\n ##description## \n\n"],

            ['id' => 755, 'template_id' => 21, 'key' => "pt-PT", 'value' => "Escreva um título de vídeo do YouTube atraente para a descrição do vídeo fornecida para atrair o interesse das pessoas em assistir:\n\nDescrição do vídeo:\n ##description## \n\n"],

            ['id' => 756, 'template_id' => 21, 'key' => "ru-RU", 'value' => "Напишите привлекательное название видео YouTube для предоставленного описания видео, чтобы заинтересовать людей в просмотре:\n\nОписание видео:\n ##description## \n\n"],

            ['id' => 757, 'template_id' => 21, 'key' => "es-ES", 'value' => "Escribe un título de video de YouTube atractivo para la descripción del video proporcionada para que las personas se interesen en verlo:\n\nDescripción del video:\n ##description## \n\n"],

            ['id' => 758, 'template_id' => 21, 'key' => "sv-SE", 'value' => "Skriv övertygande YouTube-videotitel för den medföljande videobeskrivningen för att få folk intresserade av att titta på:\n\nVideobeskrivning:\n ##description## \n\n"],

            ['id' => 759, 'template_id' => 21, 'key' => "tr-TR", 'value' => "Kullanıcıların izlemekle ilgilenmesini sağlamak için sağlanan video açıklamasına çekici bir YouTube video başlığı yazın:\n\nVideo açıklaması:\n ##description## \n\n"],

            ['id' => 760, 'template_id' => 21, 'key' => "pt-BR", 'value' => "Escreva um título de vídeo do YouTube atraente para a descrição do vídeo fornecida para atrair o interesse das pessoas em assistir:\n\nDescrição do vídeo:\n ##description## \n\n"],

            ['id' => 761, 'template_id' => 21, 'key' => "ro-RO", 'value' => "Scrieți un titlu convingător al videoclipului YouTube pentru descrierea videoclipului furnizată pentru a-i determina pe oameni să fie interesați să vizioneze:\n\nDescrierea videoclipului:\n ##description## \n\n"],

            ['id' => 762, 'template_id' => 21, 'key' => "vi-VN", 'value' => "Viết tiêu đề video hấp dẫn trên YouTube cho phần mô tả video được cung cấp để thu hút mọi người quan tâm đến việc xem:\n\nMô tả video:\n ##description## \n\n"],

            ['id' => 763, 'template_id' => 21, 'key' => "sw-KE", 'value' => "Andika mada ya video ya YouTube yenye kuvutia kwa maelezo ya video yaliyotolewa ili kuwavutia watu kutazama:\n\nMaelezo ya video:\n ##description## \n\n"],

            ['id' => 764, 'template_id' => 21, 'key' => "sl-SI", 'value' => "Napišite privlačen naslov videoposnetka YouTube za predloženi opis videoposnetka, da boste ljudi zanimali za ogled:\n\nOpis videoposnetka:\n ##description## \n\n"],

            ['id' => 765, 'template_id' => 21, 'key' => "th-TH", 'value' => "เขียนชื่อวิดีโอ YouTube ที่น่าสนใจสำหรับคำอธิบายวิดีโอที่ให้มาเพื่อให้ผู้คนสนใจรับชม:\n\nคำอธิบายวิดีโอ:\n ##description## \n\n"],

            ['id' => 766, 'template_id' => 21, 'key' => "uk-UA", 'value' => "Напишіть переконливу назву відео YouTube для наданого опису відео, щоб зацікавити людей до перегляду:\n\nОпис відео:\n ##description## \n\n"],

            ['id' => 767, 'template_id' => 21, 'key' => "lt-LT", 'value' => "Parašykite patrauklų „YouTube“ vaizdo įrašo pavadinimą pateiktam vaizdo įrašo aprašui, kad žmonės susidomėtų žiūrėti:\n\nVaizdo įrašo aprašas:\n ##description## \n\n"],

            ['id' => 768, 'template_id' => 21, 'key' => "bg-BG", 'value' => "Напишете завладяващо заглавие на видеоклипа в YouTube за предоставеното описание на видеоклипа, за да предизвикате интерес у хората да гледат:\n\nОписание на видеоклипа:\n ##description## \n\n"],

            ['id' => 769, 'template_id' => 22, 'key' => "en-US", 'value' => "Generate SEO-optimized YouTube tags and keywords for:\n\n ##title## \n\n"],

            ['id' => 770, 'template_id' => 22, 'key' => "ar-AE", 'value' => "إنشاء علامات وكلمات رئيسية على YouTube مُحسّنة لتحسين محركات البحث لـ:\n\n ##title## \n\n"],

            ['id' => 771, 'template_id' => 22, 'key' => "cmn-CN", 'value' => "为以下内容生成针对 SEO 优化的 YouTube 标签和关键字：\n\n ##title## \n\n"],

            ['id' => 772, 'template_id' => 22, 'key' => "hr-HR", 'value' => "Generiraj SEO-optimizirane YouTube oznake i ključne riječi za:\n\n ##title## \n\n"],

            ['id' => 773, 'template_id' => 22, 'key' => "cs-CZ", 'value' => "Generujte značky a klíčová slova YouTube optimalizované pro SEO pro:\n\n ##title## \n\n"],

            ['id' => 774, 'template_id' => 22, 'key' => "da-DK", 'value' => "Generer SEO-optimerede YouTube-tags og søgeord til:\n\n ##title## \n\n"],

            ['id' => 775, 'template_id' => 22, 'key' => "nl-NL", 'value' => "Genereer SEO-geoptimaliseerde YouTube-tags en trefwoorden voor:\n\n ##title## \n\n"],

            ['id' => 776, 'template_id' => 22, 'key' => "et-EE", 'value' => "SEO jaoks optimeeritud YouTube'i märgendite ja märksõnade loomine:\n\n ##title## \n\n"],

            ['id' => 777, 'template_id' => 22, 'key' => "fi-FI", 'value' => "Luo SEO-optimoituja YouTube-tageja ja avainsanoja:\n\n ##title## \n\n"],

            ['id' => 778, 'template_id' => 22, 'key' => "fr-FR", 'value' => "Générez des balises et des mots clés YouTube optimisés pour le référencement pour :\n\n ##title## \n\n"],

            ['id' => 779, 'template_id' => 22, 'key' => "de-DE", 'value' => "Generiere SEO-optimierte YouTube-Tags und Keywords für:\n\n ##title## \n\n"],

            ['id' => 780, 'template_id' => 22, 'key' => "el-GR", 'value' => "Δημιουργήστε ετικέτες και λέξεις-κλειδιά YouTube βελτιστοποιημένες για SEO για:\n\n ##title## \n\n"],

            ['id' => 781, 'template_id' => 22, 'key' => "he-IL", 'value' => "צור תגיות YouTube ומילות מפתח מותאמות לקידום אתרים עבור:\n\n ##title## \n\n"],

            ['id' => 782, 'template_id' => 22, 'key' => "hi-IN", 'value' => "इनके लिए SEO-अनुकूलित YouTube टैग और कीवर्ड जनरेट करें:\n\n ##title## \n\n"],

            ['id' => 783, 'template_id' => 22, 'key' => "hu-HU", 'value' => "SEO-optimalizált YouTube-címkék és kulcsszavak létrehozása a következőkhöz:\n\n ##title## \n\n"],

            ['id' => 784, 'template_id' => 22, 'key' => "is-IS", 'value' => "Búðu til SEO-bjartsýni YouTube merki og leitarorð fyrir:\n\n ##title## \n\n"],

            ['id' => 785, 'template_id' => 22, 'key' => "id-ID", 'value' => "Buat tag dan kata kunci YouTube yang dioptimalkan untuk SEO untuk:\n\n ##title## \n\n"],

            ['id' => 786, 'template_id' => 22, 'key' => "it-IT", 'value' => "Genera tag e parole chiave YouTube ottimizzati per la SEO per:\n\n ##title## \n\n"],

            ['id' => 787, 'template_id' => 22, 'key' => "ja-JP", 'value' => "SEO 用に最適化された YouTube タグとキーワードを生成します:\n\n ##title## \n\n"],

            ['id' => 788, 'template_id' => 22, 'key' => "ko-KR", 'value' => "SEO에 최적화된 YouTube 태그 및 키워드 생성:\n\n ##title## \n\n"],

            ['id' => 789, 'template_id' => 22, 'key' => "ms-MY", 'value' => "Jana teg dan kata kunci YouTube yang dioptimumkan SEO untuk:\n\n ##title## \n\n"],

            ['id' => 790, 'template_id' => 22, 'key' => "nb-NO", 'value' => "Generer SEO-optimaliserte YouTube-tagger og søkeord for:\n\n ##title## \n\n"],

            ['id' => 791, 'template_id' => 22, 'key' => "pl-PL", 'value' => "Wygeneruj zoptymalizowane pod kątem SEO tagi i słowa kluczowe YouTube dla:\n\n ##title## \n\n"],

            ['id' => 792, 'template_id' => 22, 'key' => "pt-PT", 'value' => "Gerar tags e palavras-chave do YouTube otimizadas para SEO para:\n\n ##title## \n\n"],

            ['id' => 793, 'template_id' => 22, 'key' => "ru-RU", 'value' => "Создать SEO-оптимизированные теги и ключевые слова YouTube для:\n\n ##title## \n\n"],

            ['id' => 794, 'template_id' => 22, 'key' => "es-ES", 'value' => "Generar etiquetas y palabras clave de YouTube optimizadas para SEO para:\n\n ##title## \n\n"],

            ['id' => 795, 'template_id' => 22, 'key' => "sv-SE", 'value' => "Zalisha lebo za YouTube zilizoboreshwa na SEO na maneno muhimu ya:\n\n ##title## \n\n"],

            ['id' => 796, 'template_id' => 22, 'key' => "tr-TR", 'value' => "Şunlar için SEO için optimize edilmiş YouTube etiketleri ve anahtar kelimeler oluşturun:\n\n ##title## \n\n"],

            ['id' => 797, 'template_id' => 22, 'key' => "pt-BR", 'value' => "Gerar tags e palavras-chave do YouTube otimizadas para SEO para:\n\n ##title## \n\n"],

            ['id' => 798, 'template_id' => 22, 'key' => "ro-RO", 'value' => "Generează etichete și cuvinte cheie YouTube optimizate pentru SEO pentru:\n\n ##title## \n\n"],

            ['id' => 799, 'template_id' => 22, 'key' => "vi-VN", 'value' => "Tạo thẻ và từ khóa YouTube được tối ưu hóa cho SEO cho:\n\n ##title## \n\n" ],

            ['id' => 800, 'template_id' => 22, 'key' => "sw-KE", 'value' => "Zalisha lebo za YouTube zilizoboreshwa na SEO na maneno muhimu ya:\n\n ##title## \n\n"],

            ['id' => 801, 'template_id' => 22, 'key' => "sl-SI", 'value' => "Ustvari YouTube oznake in ključne besede, optimizirane za SEO za:\n\n ##title## \n\n"],

            ['id' => 802, 'template_id' => 22, 'key' => "th-TH", 'value' => "สร้างแท็ก YouTube ที่ปรับให้เหมาะสม SEO และคำหลักสำหรับ:\n\n ##title## \n\n"],

            ['id' => 803, 'template_id' => 22, 'key' => "uk-UA", 'value' => "Створіть оптимізовані для SEO теги та ключові слова YouTube для:\n\n ##title## \n\n"],

            ['id' => 804, 'template_id' => 22, 'key' => "lt-LT", 'value' => "Generuokite pagal SEO optimizuotas „YouTube“ žymas ir raktinius žodžius:\n\n ##title## \n\n"],

            ['id' => 805, 'template_id' => 22, 'key' => "bg-BG", 'value' => "Генериране на оптимизирани за SEO маркери и ключови думи в YouTube за:\n\n ##title## \n\n"],

            ['id' => 806, 'template_id' => 23, 'key' => "en-US", 'value' => "Grab attention with catchy captions for this Instagram post:\n\n ##description## \n\nTone of voice of the captions must be:\n ##tone_language## \n\n"],

            ['id' => 807, 'template_id' => 23, 'key' => "ar-AE", 'value' => "اجذب الانتباه باستخدام التسميات التوضيحية الجذابة لمشاركة Instagram هذه:\n\n ##description## \n\nيجب أن تكون نغمة صوت التسميات التوضيحية:\n ##tone_language## \n\n"],

            ['id' => 808, 'template_id' => 23, 'key' => "cmn-CN", 'value' => "为这条 Instagram 帖子添加朗朗上口的标题以吸引注意力：\n\n ##description## \n\n字幕的语调必须是：\n ##tone_language## \n\n"],

            ['id' => 809, 'template_id' => 23, 'key' => "hr-HR", 'value' => "Privucite pažnju privlačnim natpisima za ovu objavu na Instagramu:\n\n ##description## \n\nTon glasa titlova mora biti:\n ##tone_language## \n\n"],

            ['id' => 810, 'template_id' => 23, 'key' => "cs-CZ", 'value' => "Přitáhněte pozornost chytlavými titulky k tomuto příspěvku na Instagramu:\n\n ##description## \n\nTón hlasu titulků musí být:\n ##tone_language## \n\n"],

            ['id' => 811, 'template_id' => 23, 'key' => "da-DK", 'value' => "Fang opmærksomhed med fængende billedtekster til dette Instagram-opslag:\n\n ##description## \n\nTelefonen for billedteksterne skal være:\n ##tone_language## \n\n"],

            ['id' => 812, 'template_id' => 23, 'key' => "nl-NL", 'value' => "Trek de aandacht met pakkende bijschriften voor dit Instagram-bericht:\n\n ##description## \n\nDe toon van de ondertiteling moet zijn:\n ##tone_language## \n\n"],

            ['id' => 813, 'template_id' => 23, 'key' => "et-EE", 'value' => "Pöörake tähelepanu selle Instagrami postituse meeldejäävate pealkirjadega:\n\n ##description## \n\nTiitrite hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 814, 'template_id' => 23, 'key' => "fi-FI", 'value' => "Kiinnitä huomiota tarttuvilla kuvateksteillä tälle Instagram-julkaisulle:\n\n ##description## \n\nTekstitysten äänensävyn on oltava:\n ##tone_language## \n\n"],

            ['id' => 815, 'template_id' => 23, 'key' => "fr-FR", 'value' => "Attirez l'attention avec des légendes accrocheuses pour cette publication Instagram :\n\n ##description## \n\nLe ton de la voix des sous-titres doit être :\n ##tone_language## \n\n"],

            ['id' => 816, 'template_id' => 23, 'key' => "de-DE", 'value' => "Erregen Sie Aufmerksamkeit mit einprägsamen Bildunterschriften für diesen Instagram-Post:\n\n ##description## \n\nTonlage der Untertitel muss sein:\n ##tone_language## \n\n"],

            ['id' => 817, 'template_id' => 23, 'key' => "el-GR", 'value' => "Τραβήξτε την προσοχή με εντυπωσιακούς λεζάντες για αυτήν την ανάρτηση στο Instagram:\n\n ##description## \n\nΟ τόνος της φωνής των υπότιτλων πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 818, 'template_id' => 23, 'key' => "he-IL", 'value' => "משכו תשומת לב עם כיתובים קליטים לפוסט הזה באינסטגרם:\n\n ##description## \n\nטון הדיבור של הכתוביות חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 819, 'template_id' => 23, 'key' => "hi-IN", 'value' => "इस Instagram पोस्ट के लिए आकर्षक कैप्शन के साथ ध्यान आकर्षित करें:\n\n ##description## \n\nकैप्शन की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 820, 'template_id' => 23, 'key' => "hu-HU", 'value' => "Felhívja fel a figyelmet ennek az Instagram-bejegyzésnek a fülbemászó felirataival:\n\n ##description## \n\nA feliratok hangszínének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 821, 'template_id' => 23, 'key' => "is-IS", 'value' => "Gríptu athygli með grípandi texta fyrir þessa Instagram færslu:\n\n ##description## \n\nTónn skjátextanna verður að vera:\n ##tone_language## \n\n"],

            ['id' => 822, 'template_id' => 23, 'key' => "id-ID", 'value' => "Raih perhatian dengan teks menarik untuk postingan Instagram ini:\n\n ##description## \n\nNada suara teks harus:\n ##tone_language## \n\n"],

            ['id' => 823, 'template_id' => 23, 'key' => "it-IT", 'value' => "Attira l'attenzione con didascalie accattivanti per questo post di Instagram:\n\n ##description## \n\nIl tono di voce dei sottotitoli deve essere:\n ##tone_language## \n\n"],

            ['id' => 824, 'template_id' => 23, 'key' => "ja-JP", 'value' => "この Instagram 投稿のキャッチーなキャプションで注目を集めましょう:\n\n ##description## \n\nキャプションの声のトーンは次のようにする必要があります:\n ##tone_language## \n\n"],

            ['id' => 825, 'template_id' => 23, 'key' => "ko-KR", 'value' => "이 Instagram 게시물에 대한 눈길을 끄는 캡션으로 관심 끌기:\n\n ##description## \n\n캡션의 목소리 톤은 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 826, 'template_id' => 23, 'key' => "ms-MY", 'value' => "Tarik perhatian dengan kapsyen yang menarik untuk siaran Instagram ini:\n\n ##description## \n\nNada suara kapsyen mestilah:\n ##tone_language## \n\n"],

            ['id' => 827, 'template_id' => 23, 'key' => "nb-NO", 'value' => "Fang oppmerksomhet med fengende bildetekster for dette Instagram-innlegget:\n\n ##description## \n\nStemmetonen til bildetekstene må være:\n ##tone_language## \n\n"],

            ['id' => 828, 'template_id' => 23, 'key' => "pl-PL", 'value' => "Przyciągnij uwagę chwytliwymi napisami do tego posta na Instagramie:\n\n ##description## \n\nTon głosu napisów musi być:\n ##tone_language## \n\n"],

            ['id' => 829, 'template_id' => 23, 'key' => "pt-PT", 'value' => "Chame a atenção com legendas cativantes para esta postagem do Instagram:\n\n ##description## \n\nTom de voz das legendas deve ser:\n ##tone_language## \n\n"],

            ['id' => 830, 'template_id' => 23, 'key' => "ru-RU", 'value' => "Привлеките внимание броскими подписями к этому посту в Instagram:\n\n ##description## \n\nТон титров должен быть:\n ##tone_language## \n\n"],

            ['id' => 831, 'template_id' => 23, 'key' => "es-ES", 'value' => "Capte la atención con subtítulos pegadizos para esta publicación de Instagram:\n\n ##description## \n\nEl tono de voz de los subtítulos debe ser:\n ##tone_language## \n\n"],

            ['id' => 832, 'template_id' => 23, 'key' => "sv-SE", 'value' => "Fånga uppmärksamhet med catchy bildtexter för detta Instagram-inlägg:\n\n ##description## \n\nRösten för bildtexterna måste vara:\n ##tone_language## \n\n"],

            ['id' => 833, 'template_id' => 23, 'key' => "tr-TR", 'value' => "Bu Instagram gönderisi için akılda kalıcı altyazılarla dikkat çekin:\n\n ##description## \n\nAltyazıların ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 834, 'template_id' => 23, 'key' => "pt-BR", 'value' =>"Chame a atenção com legendas cativantes para esta postagem do Instagram:\n\n ##description## \n\nTom de voz das legendas deve ser:\n ##tone_language## \n\n"],

            ['id' => 835, 'template_id' => 23, 'key' => "ro-RO", 'value' => "Atrageți atenția cu subtitrări captivante pentru această postare pe Instagram:\n\n ##description## \n\nTonul vocii subtitrărilor trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 836, 'template_id' => 23, 'key' => "vi-VN", 'value' => "Thu hút sự chú ý bằng chú thích hấp dẫn cho bài đăng trên Instagram này:\n\n ##description## \n\nGiọng điệu của phụ đề phải là:\n ##tone_language## \n\n"],

            ['id' => 837, 'template_id' => 23, 'key' => "sw-KE", 'value' => "Chukua umakini na manukuu ya kuvutia ya chapisho hili la Instagram:\n\n ##description## \n\nToni ya sauti ya manukuu lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 838, 'template_id' => 23, 'key' => "sl-SI", 'value' => "Pritegnite pozornost s privlačnimi napisi za to objavo na Instagramu:\n\n ##description## \n\nTon glasu napisov mora biti:\n ##tone_language## \n\n"],

            ['id' => 838, 'template_id' => 23, 'key' => "th-TH", 'value' => "ดึงดูดความสนใจด้วยคำบรรยายที่ติดหูสำหรับโพสต์ Instagram นี้:\n\n ##description## \n\nน้ำเสียงของคำบรรยายต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 830, 'template_id' => 23, 'key' => "uk-UA", 'value' => "Привертайте увагу привабливими підписами до цієї публікації в Instagram:\n\n ##description## \n\nТон субтитрів має бути:\n ##tone_language## \n\n"],

            ['id' => 831, 'template_id' => 23, 'key' => "lt-LT", 'value' => "Patraukite dėmesį patraukliais šio Instagram įrašo antraštėmis:\n\n ##description## \n\nSubtitrų balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 832, 'template_id' => 23, 'key' => "bg-BG", 'value' => "Привлечете вниманието със закачливи надписи за тази публикация в Instagram:\n\n ##description## \n\nТонът на надписите трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 833, 'template_id' => 24, 'key' => "en-US", 'value' => "Find the best hashtags to use for this Instagram keyword:\n\n ##keyword## \n\n"],

            ['id' => 834, 'template_id' => 24, 'key' => "ar-AE", 'value' => "ابحث عن أفضل علامات التصنيف لاستخدامها لهذه الكلمة الأساسية في Instagram:\n\n ##keyword## \n\n"],

            ['id' => 835, 'template_id' => 24, 'key' => "cmn-CN", 'value' => "找到用于此 Instagram 关键字的最佳主题标签：\n\n ##keyword## \n\n"],

            ['id' => 836, 'template_id' => 24, 'key' => "hr-HR", 'value' => "Pronađite najbolje hashtagove za ovu ključnu riječ za Instagram:\n\n ##keyword## \n\n"],

            ['id' => 837, 'template_id' => 24, 'key' => "cs-CZ", 'value' => "Najděte nejlepší hashtagy pro toto klíčové slovo na Instagramu:\n\n ##keyword## \n\n"],

            ['id' => 838, 'template_id' => 24, 'key' => "da-DK", 'value' => "Find de bedste hashtags til dette Instagram-søgeord:\n\n ##keyword## \n\n"],

            ['id' => 839, 'template_id' => 24, 'key' => "nl-NL", 'value' => "Vind de beste hashtags om te gebruiken voor dit Instagram-trefwoord:\n\n ##keyword## \n\n"],

            ['id' => 840, 'template_id' => 24, 'key' => "et-EE", 'value' => "Leia parimad hashtagid, mida selle Instagrami märksõna jaoks kasutada:\n\n ##keyword## \n\n"],

            ['id' => 841, 'template_id' => 24, 'key' => "fi-FI", 'value' => "Etsi parhaat hashtagit tälle Instagram-avainsanalle:\n\n ##keyword## \n\n"],

            ['id' => 842, 'template_id' => 24, 'key' => "fr-FR", 'value' => "Trouvez les meilleurs hashtags à utiliser pour ce mot clé Instagram :\n\n ##keyword## \n\n"],

            ['id' => 843, 'template_id' => 24, 'key' => "de-DE", 'value' => "Finde die besten Hashtags für dieses Instagram-Keyword:\n\n ##keyword## \n\n"],

            ['id' => 844, 'template_id' => 24, 'key' => "el-GR", 'value' => "Βρείτε τα καλύτερα hashtags για χρήση για αυτήν τη λέξη-κλειδί Instagram:\n\n ##keyword## \n\n"],

            ['id' => 845, 'template_id' => 24, 'key' => "he-IL", 'value' => "מצא את ההאשטאגים הטובים ביותר לשימוש עבור מילת המפתח הזו באינסטגרם:\n\n ##keyword## \n\n"],

            ['id' => 846, 'template_id' => 24, 'key' => "hi-IN", 'value' => "इस Instagram कीवर्ड के लिए उपयोग करने के लिए सर्वोत्तम हैशटैग खोजें:\n\n ##keyword## \n\n"],

            ['id' => 847, 'template_id' => 24, 'key' => "hu-HU", 'value' => "Keresse meg az ehhez az Instagram-kulcsszóhoz használható legjobb hashtageket:\n\n ##keyword## \n\n"],

            ['id' => 848, 'template_id' => 24, 'key' => "is-IS", 'value' => "Finndu bestu myllumerkin til að nota fyrir þetta Instagram leitarorð:\n\n ##keyword## \n\n"],

            ['id' => 849, 'template_id' => 24, 'key' => "id-ID", 'value' => "Temukan tagar terbaik untuk digunakan untuk kata kunci Instagram ini:\n\n ##keyword## \n\n"],

            ['id' => 850, 'template_id' => 24, 'key' => "it-IT", 'value' => "Trova i migliori hashtag da utilizzare per questa parola chiave di Instagram:\n\n ##keyword## \n\n"],

            ['id' => 851, 'template_id' => 24, 'key' => "ja-JP", 'value' => "この Instagram キーワードに使用するのに最適なハッシュタグを見つけてください:\n\n ##keyword## \n\n"],

            ['id' => 852, 'template_id' => 24, 'key' => "ko-KR", 'value' => "이 Instagram 키워드에 사용할 최고의 해시태그 찾기:\n\n ##keyword## \n\n"],

            ['id' => 853, 'template_id' => 24, 'key' => "ms-MY", 'value' => "Cari hashtag terbaik untuk digunakan untuk kata kunci Instagram ini:\n\n ##keyword## \n\n"],

            ['id' => 854, 'template_id' => 24, 'key' => "nb-NO", 'value' => "Finn de beste hashtaggene du kan bruke for dette Instagram-søkeordet:\n\n ##keyword## \n\n"],

            ['id' => 855, 'template_id' => 24, 'key' => "pl-PL", 'value' => "Znajdź najlepsze hashtagi do użycia dla tego słowa kluczowego na Instagramie:\n\n ##keyword## \n\n"],

            ['id' => 856, 'template_id' => 24, 'key' => "pt-PT", 'value' => "Encontre as melhores hashtags para usar com esta palavra-chave do Instagram:\n\n ##keyword## \n\n"],

            ['id' => 857, 'template_id' => 24, 'key' => "ru-RU", 'value' => "Найдите лучшие хэштеги для этого ключевого слова Instagram:\n\n ##keyword## \n\n"],

            ['id' => 858, 'template_id' => 24, 'key' => "es-ES", 'value' => "Encuentra los mejores hashtags para usar con esta palabra clave de Instagram:\n\n ##keyword## \n\n"],

            ['id' => 858, 'template_id' => 24, 'key' => "sv-SE", 'value' => "Hitta de bästa hashtaggarna att använda för detta Instagram-sökord:\n\n ##keyword## \n\n"],

            ['id' => 859, 'template_id' => 24, 'key' => "tr-TR", 'value' => "Bu Instagram anahtar kelimesi için kullanılacak en iyi etiketleri bulun:\n\n ##keyword## \n\n"],

            ['id' => 860, 'template_id' => 24, 'key' => "pt-BR", 'value' => "Encontre as melhores hashtags para usar com esta palavra-chave do Instagram:\n\n ##keyword## \n\n"],

            ['id' => 861, 'template_id' => 24, 'key' => "ro-RO", 'value' => "Găsiți cele mai bune hashtag-uri de folosit pentru acest cuvânt cheie Instagram:\n\n ##keyword## \n\n"],

            ['id' => 862, 'template_id' => 24, 'key' => "vi-VN", 'value' => "Tìm các thẻ bắt đầu bằng # tốt nhất để sử dụng cho từ khóa Instagram này:\n\n ##keyword## \n\n"],

            ['id' => 863, 'template_id' => 24, 'key' => "sw-KE", 'value' => "Tafuta lebo za reli bora za kutumia kwa neno kuu la Instagram:\n\n ##keyword## \n\n"],

            ['id' => 864, 'template_id' => 24, 'key' => "sl-SI", 'value' => "Poiščite najboljše hashtage za to ključno besedo za Instagram:\n\n ##keyword## \n\n"],

            ['id' => 865, 'template_id' => 24, 'key' => "th-TH", 'value' => "ค้นหาแฮชแท็กที่ดีที่สุดเพื่อใช้สำหรับคีย์เวิร์ด Instagram นี้:\n\n ##keyword## \n\n"],

            ['id' => 866, 'template_id' => 24, 'key' => "uk-UA", 'value' => "Знайдіть найкращі хештеги для цього ключового слова Instagram:\n\n ##keyword## \n\n"],

            ['id' => 867, 'template_id' => 24, 'key' => "lt-LT", 'value' => "Rasti geriausias žymas su grotelėmis, kurias galima naudoti šiam „Instagram“ raktiniam žodžiui:\n\n ##keyword## \n\n"],

            ['id' => 868, 'template_id' => 24, 'key' => "bg-BG", 'value' => "Намерете най-добрите хаштагове, които да използвате за тази ключова дума в Instagram:\n\n ##keyword## \n\n"],

            ['id' => 869, 'template_id' => 25, 'key' => "en-US", 'value' => "Write a personal social media post about:\n\n ##description## \n\nTone of voice of the post must be:\n ##tone_language## \n\n"],

            ['id' => 870, 'template_id' => 25, 'key' => "ar-AE", 'value' => "اكتب منشورًا شخصيًا على وسائل التواصل الاجتماعي حول:\n\n ##description## \n\nيجب أن تكون نغمة صوت المشاركة:\n ##tone_language## \n\n"],

            ['id' => 871, 'template_id' => 25, 'key' => "cmn-CN", 'value' => "写一篇个人社交媒体帖子：\n\n ##description## \n\n帖子的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 872, 'template_id' => 25, 'key' => "hr-HR", 'value' => "Napišite osobnu objavu na društvenim mrežama o:\n\n ##description## \n\nTon glasa objave mora biti:\n ##tone_language## \n\n"],

            ['id' => 873, 'template_id' => 25, 'key' => "cs-CZ", 'value' => "Napište osobní příspěvek na sociální média o:\n\n ##description## \n\nTón hlasu příspěvku musí být:\n ##tone_language## \n\n"],

            ['id' => 874, 'template_id' => 25, 'key' => "da-DK", 'value' => "Skriv et personligt opslag på sociale medier om:\n\n ##description## \n\nOplæggets stemme skal være:\n ##tone_language## \n\n"],

            ['id' => 875, 'template_id' => 25, 'key' => "nl-NL", 'value' => "Schrijf een persoonlijk bericht op sociale media over:\n\n ##description## \n\nDe toon van het bericht moet zijn:\n ##tone_language## \n\n"],

            ['id' => 876, 'template_id' => 25, 'key' => "et-EE", 'value' => "Kirjutage isiklik sotsiaalmeedia postitus teemal:\n\n ##description## \n\nPostituse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 877, 'template_id' => 25, 'key' => "fi-FI", 'value' => "Kirjoita henkilökohtainen sosiaalisen median viesti aiheesta:\n\n ##description## \n\nViestin äänensävyn on oltava:\n ##tone_language## \n\n"],

            ['id' => 878, 'template_id' => 25, 'key' => "fr-FR", 'value' => "Rédigez un message personnel sur les réseaux sociaux à propos de :\n\n ##description## \n\nLe ton de la voix du message doit être :\n ##tone_language## \n\n"],

            ['id' => 879, 'template_id' => 25, 'key' => "de-DE", 'value' => "Schreiben Sie einen persönlichen Social-Media-Beitrag über:\n\n ##description## \n\nTonlage des Beitrags muss sein:\n ##tone_language## \n\n"],

            ['id' => 880, 'template_id' => 25, 'key' => "el-GR", 'value' => "Γράψτε μια προσωπική ανάρτηση στα μέσα κοινωνικής δικτύωσης σχετικά με:\n\n ##description## \n\nΟ τόνος της φωνής της ανάρτησης πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 881, 'template_id' => 25, 'key' => "he-IL", 'value' => "כתוב פוסט אישי במדיה החברתית על:\n\n ##description## \n\nטון הדיבור של הפוסט חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 882, 'template_id' => 25, 'key' => "hi-IN", 'value' => "इसके बारे में एक निजी सोशल मीडिया पोस्ट लिखें:\n\n ##description## \n\nपोस्ट की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 883, 'template_id' => 25, 'key' => "hu-HU", 'value' => "Írjon személyes közösségimédia-bejegyzést erről:\n\n ##description## \n\nA bejegyzés hangnemének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 884, 'template_id' => 25, 'key' => "is-IS", 'value' => "Skrifaðu persónulega færslu á samfélagsmiðlum um:\n\n ##description## \n\nTónn færslunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 885, 'template_id' => 25, 'key' => "id-ID", 'value' => "Tulis postingan media sosial pribadi tentang:\n\n ##description## \n\nNada suara postingan harus:\n ##tone_language## \n\n"],

            ['id' => 886, 'template_id' => 25, 'key' => "it-IT", 'value' => "Scrivi un post personale sui social media su:\n\n ##description## \n\nIl tono di voce del post deve essere:\n ##tone_language## \n\n"],

            ['id' => 887, 'template_id' => 25, 'key' => "ja-JP", 'value' => "個人的なソーシャル メディアの投稿について書く:\n\n ##description## \n\n投稿のトーンは次のようにする必要があります:\n ##tone_language## \n\n"],

            ['id' => 888, 'template_id' => 25, 'key' => "ko-KR", 'value' => "다음에 대한 개인 소셜 미디어 게시물 작성:\n\n ##description## \n\n포스트의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 889, 'template_id' => 25, 'key' => "ms-MY", 'value' => "Tulis siaran media sosial peribadi tentang:\n\n ##description## \n\nNada suara siaran mestilah:\n ##tone_language## \n\n"],

            ['id' => 890, 'template_id' => 25, 'key' => "nb-NO", 'value' => "Skriv et personlig innlegg på sosiale medier om:\n\n ##description## \n\nTone i innlegget må være:\n ##tone_language## \n\n"],

            ['id' => 891, 'template_id' => 25, 'key' => "pl-PL", 'value' => "Napisz osobisty post w mediach społecznościowych na temat:\n\n ##description## \n\nTon wypowiedzi w poście musi być:\n ##tone_language## \n\n"],

            ['id' => 892, 'template_id' => 25, 'key' => "pt-PT", 'value' => "Escreva uma postagem de mídia social pessoal sobre:\n\n ##description## \n\nTom de voz da postagem deve ser:\n ##tone_language## \n\n"],

            ['id' => 893, 'template_id' => 25, 'key' => "ru-RU", 'value' => "Напишите личный пост в социальной сети о:\n\n ##description## \n\nТон сообщения должен быть:\n ##tone_language## \n\n"],

            ['id' => 894, 'template_id' => 25, 'key' => "es-ES", 'value' => "Escribe una publicación personal en las redes sociales sobre:\n\n ##description## \n\nEl tono de voz de la publicación debe ser:\n ##tone_language## \n\n"],

            ['id' => 895, 'template_id' => 25, 'key' => "sv-SE", 'value' => "Skriv ett personligt inlägg på sociala medier om:\n\n ##description## \n\nTonfallet i inlägget måste vara:\n ##tone_language## \n\n"],

            ['id' => 896, 'template_id' => 25, 'key' => "tr-TR", 'value' => "Şununla ilgili kişisel bir sosyal medya gönderisi yaz:\n\n ##description## \n\nGönderinin ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 897, 'template_id' => 25, 'key' => "pt-BR", 'value' => "Escreva uma postagem de mídia social pessoal sobre:\n\n ##description## \n\nTom de voz da postagem deve ser:\n ##tone_language## \n\n"],

            ['id' => 898, 'template_id' => 25, 'key' => "ro-RO", 'value' => "Scrieți o postare personală pe rețelele sociale despre:\n\n ##description## \n\nTonul vocii postării trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 899, 'template_id' => 25, 'key' => "vi-VN", 'value' => "Viết một bài đăng cá nhân trên mạng xã hội về:\n\n ##description## \n\nGiọng điệu của bài đăng phải là:\n ##tone_language## \n\n"],

            ['id' => 900, 'template_id' => 25, 'key' => "sw-KE", 'value' => "Andika chapisho la kibinafsi la mtandao wa kijamii kuhusu:\n\n ##description## \n\nToni ya sauti ya chapisho lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 901, 'template_id' => 25, 'key' => "sl-SI", 'value' => "Napišite osebno objavo v družabnem omrežju o:\n\n ##description## \n\nTon objave mora biti:\n ##tone_language## \n\n"],

            ['id' => 902, 'template_id' => 25, 'key' => "th-TH", 'value' => "เขียนโพสต์โซเชียลมีเดียส่วนตัวเกี่ยวกับ:\n\n ##description## \n\nน้ำเสียงของโพสต์ต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 903, 'template_id' => 25, 'key' => "uk-UA", 'value' => "Напишіть особисту публікацію в соціальних мережах про:\n\n ##description## \n\nТон допису має бути:\n ##tone_language## \n\n"],

            ['id' => 904, 'template_id' => 25, 'key' => "lt-LT", 'value' => "Parašykite asmeninį socialinių tinklų įrašą apie:\n\n ##description## \n\nĮrašo balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 905, 'template_id' => 25, 'key' => "bg-BG", 'value' => "Напишете лична публикация в социалните медии за:\n\n ##description## \n\nТонът на публикацията трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 906, 'template_id' => 26, 'key' => "en-US", 'value' => "Create a large professional social media post for my company. Post description:\n\n ##post## \n\nCompany description:\n ##description## \n\nCompany name:\n ##title## \n\n"],

            ['id' => 907, 'template_id' => 26, 'key' => "ar-AE", 'value' => "أنشئ منشورًا احترافيًا كبيرًا على الوسائط الاجتماعية لشركتي. وصف المشاركة:\n\n ##post## \n\nوصف الشركة:\n ##description## \n\nاسم الشركة:\n ##title## \n\n"],

            ['id' => 908, 'template_id' => 26, 'key' => "cmn-CN", 'value' => "为我的公司创建大型专业社交媒体帖子。帖子描述：\n\n ##post## \n\n公司描述：\n ##description## \n\n公司名称：\n ##title## \n\n"],

            ['id' => 909, 'template_id' => 26, 'key' => "hr-HR", 'value' => "Stvorite veliku profesionalnu objavu na društvenim mrežama za moju tvrtku. Opis objave:\n\n ##post## \n\nOpis tvrtke:\n ##description## \n\nNaziv tvrtke:\n ##title## \n\n"],

            ['id' => 910, 'template_id' => 26, 'key' => "cs-CZ", 'value' => "Vytvořte pro mou společnost velký profesionální příspěvek na sociálních sítích. Popis příspěvku:\n\n ##post## \n\nPopis společnosti:\n ##description## \n\nNázev společnosti:\n ##title## \n\n"],

            ['id' => 911, 'template_id' => 26, 'key' => "da-DK", 'value' => "Opret et stort professionelt opslag på sociale medier til min virksomhed. Indlægsbeskrivelse:\n\n ##post## \n\nVirksomhedsbeskrivelse:\n ##description## \n\nVirksomhedsnavn:\n ##title## \n\n"],
            ['id' => 912, 'template_id' => 26, 'key' => "nl-NL", 'value' => "Maak een groot professioneel bericht op sociale media voor mijn bedrijf. Berichtbeschrijving:\n\n ##post## \n\nBedrijfsomschrijving:\n ##description## \n\nBedrijfsnaam:\n ##title## \n\n"],

            ['id' => 913, 'template_id' => 26, 'key' => "et-EE", 'value' => "Loo minu ettevõtte jaoks suur professionaalne sotsiaalmeediapostitus. Postituse kirjeldus:\n\n ##post## \n\nEttevõtte kirjeldus:\n ##description## \n\nEttevõtte nimi:\n ##title## \n\n"],

            ['id' => 914, 'template_id' => 26, 'key' => "fi-FI", 'value' => "Luo suuri ammattimainen sosiaalisen median viesti yritykselleni. Viestin kuvaus:\n\n ##post## \n\nYrityksen kuvaus:\n ##description## \n\nYrityksen nimi:\n ##title## \n\n"],

            ['id' => 915, 'template_id' => 26, 'key' => "fr-FR", 'value' => "Créer une grande publication professionnelle sur les réseaux sociaux pour mon entreprise. Description de la publication :\n\n ##post## \n\nDescription de l'entreprise :\n ##description## \n\nNom de l'entreprise :\n ##title## \n\n"],

            ['id' => 916, 'template_id' => 26, 'key' => "de-DE", 'value' => "Erstelle einen großen professionellen Social-Media-Beitrag für mein Unternehmen. Beitragsbeschreibung:\n\n ##post## \n\nUnternehmensbeschreibung:\n ##description## \n\nFirmenname:\n ##title## \n\n"],

            ['id' => 917, 'template_id' => 26, 'key' => "el-GR", 'value' => "Δημιουργήστε μια μεγάλη επαγγελματική ανάρτηση στα μέσα κοινωνικής δικτύωσης για την εταιρεία μου. Περιγραφή ανάρτησης:\n\n ##post## \n\nΠεριγραφή εταιρείας:\n ##description## \n\nΌνομα εταιρείας:\n ##title## \n\n"],

            ['id' => 918, 'template_id' => 26, 'key' => "he-IL", 'value' => "צור פוסט מקצועי גדול במדיה החברתית עבור החברה שלי. תיאור הפוסט:\n\n ##post## \n\nתיאור החברה:\n ##description## \n\nשם החברה:\n ##title## \n\n"],

            ['id' => 919, 'template_id' => 26, 'key' => "hi-IN", 'value' => "मेरी कंपनी के लिए एक बड़ी पेशेवर सोशल मीडिया पोस्ट बनाएं। पोस्ट विवरण:\n\n ##post## \n\nकंपनी विवरण:\n ##description## \n\nकंपनी का नाम:\n ##title## \n\n"],

            ['id' => 920, 'template_id' => 26, 'key' => "hu-HU", 'value' => "Hozzon létre egy nagy, professzionális közösségi média bejegyzést a cégem számára. Bejegyzés leírása:\n\n ##post## \n\nCég leírása:\n ##description## \n\nCég neve:\n ##title## \n\n"],

            ['id' => 921, 'template_id' => 26, 'key' => "is-IS", 'value' => "Búðu til 10 grípandi bloggtitla fyrir:\n\n ##description## \n\n'Búa til stóra faglega samfélagsmiðlafærslu fyrir fyrirtækið mitt. Lýsing færslu:\n\n ##post## \n\nFyrirtækislýsing:\n ##description## \n\nNafn fyrirtækis:\n ##title## \n\n"],

            ['id' => 922, 'template_id' => 26, 'key' => "id-ID", 'value' => "Buat postingan media sosial profesional yang besar untuk perusahaan saya. Deskripsi postingan:\n\n ##post## \n\nDeskripsi perusahaan:\n ##description## \n\nNama perusahaan:\n ##title## \n\n"],

            ['id' => 923, 'template_id' => 26, 'key' => "it-IT", 'value' => "Crea un grande post professionale sui social media per la mia azienda. Descrizione del post:\n\n ##post## \n\nDescrizione azienda:\n ##description## \n\nNome azienda:\n ##title## \n\n"],

            ['id' => 924, 'template_id' => 26, 'key' => "ja-JP", 'value' => "私の会社のために大規模なプロフェッショナル ソーシャル メディア投稿を作成します。投稿の説明:\n\n ##post## \n\n会社説明:\n ##description## \n\n会社名:\n ##title## \n\n"],

            ['id' => 925, 'template_id' => 26, 'key' => "ko-KR", 'value' => "우리 회사를 위한 대규모 전문 소셜 미디어 게시물을 작성합니다. 게시물 설명:\n\n ##post## \n\n회사 설명:\n ##description## \n\n회사 이름:\n ##title## \n\n"],

            ['id' => 926, 'template_id' => 26, 'key' => "ms-MY", 'value' => "Buat siaran media sosial profesional yang besar untuk syarikat saya. Perihalan siaran:\n\n ##post## \n\nPerihalan syarikat:\n ##description## \n\nNama syarikat:\n ##title## \n\n"],

            ['id' => 927, 'template_id' => 26, 'key' => "nb-NO", 'value' => "Opprett et stort profesjonelt innlegg på sosiale medier for firmaet mitt. Innleggsbeskrivelse:\n\n ##post## \n\nBedriftsbeskrivelse:\n ##description## \n\nBedriftsnavn:\n ##title## \n\n"],

            ['id' => 928, 'template_id' => 26, 'key' => "pl-PL", 'value' => "Utwórz obszerny, profesjonalny post mojej firmy w mediach społecznościowych. Opis wpisu:\n\n ##post## \n\nOpis firmy:\n ##description## \n\nNazwa firmy:\n ##title## \n\n"],

            ['id' => 929, 'template_id' => 26, 'key' => "pt-PT", 'value' => "Criar uma grande postagem de mídia social profissional para minha empresa. Descrição da postagem:\n\n ##post## \n\nDescrição da empresa:\n ##description## \n\nNome da empresa:\n ##title## \n\n"],

            ['id' => 930, 'template_id' => 26, 'key' => "ru-RU", 'value' => "Создайте большую профессиональную публикацию в социальных сетях для моей компании. Описание публикации:\n\n ##post## \n\nОписание компании:\n ##description## \n\nНазвание компании:\n ##title## \n\n"],

            ['id' => 931, 'template_id' => 26, 'key' => "es-ES", 'value' => "Crear una gran publicación profesional en las redes sociales para mi empresa. Descripción de la publicación:\n\n ##post## \n\nDescripción de la empresa:\n ##description## \n\nNombre de la empresa:\n ##title## \n\n"],

            ['id' => 932, 'template_id' => 26, 'key' => "sv-SE", 'value' => "Skapa ett stort professionellt inlägg på sociala medier för mitt företag. Inläggsbeskrivning:\n\n ##post## \n\nFöretagsbeskrivning:\n ##description## \n\nFöretagsnamn:\n ##title## \n\n"],

            ['id' => 933, 'template_id' => 26, 'key' => "tr-TR", 'value' => "Şirketim için büyük bir profesyonel sosyal medya gönderisi oluştur. Gönderi açıklaması:\n\n ##post## \n\nŞirket açıklaması:\n ##description## \n\nŞirket adı:\n ##title## \n\n"],

            ['id' => 934, 'template_id' => 26, 'key' => "pt-BR", 'value' => "Criar uma grande postagem de mídia social profissional para minha empresa. Descrição da postagem:\n\n ##post## \n\nDescrição da empresa:\n ##description## \n\nNome da empresa:\n ##title## \n\n"],

            ['id' => 935, 'template_id' => 26, 'key' => "ro-RO", 'value' => "Creează o postare mare profesională pe rețelele sociale pentru compania mea. Descrierea postării:\n\n ##post## \n\nDescrierea companiei:\n ##description## \n\nNumele companiei:\n ##title## \n\n"],

            ['id' => 936, 'template_id' => 26, 'key' => "vi-VN", 'value' => "Tạo một bài đăng lớn chuyên nghiệp trên mạng xã hội cho công ty của tôi. Mô tả bài đăng:\n\n ##post## \n\nMô tả công ty:\n ##description## \n\nTên công ty:\n ##title## \n\n"],

            ['id' => 937, 'template_id' => 26, 'key' => "sw-KE", 'value' => "Unda chapisho kubwa la kitaalamu la mitandao ya kijamii kwa ajili ya kampuni yangu. Chapisha maelezo:\n\n ##post## \n\nMaelezo ya kampuni:\n ##description## \n\nJina la kampuni:\n ##title## \n\n"],

            ['id' => 938, 'template_id' => 26, 'key' => "sl-SI", 'value' => "Ustvari veliko profesionalno objavo v družbenih medijih za moje podjetje. Opis objave:\n\n ##post## \n\nOpis podjetja:\n ##description## \n\nIme podjetja:\n ##title## \n\n"],

            ['id' => 939, 'template_id' => 26, 'key' => "th-TH", 'value' => "สร้างโพสต์โซเชียลมีเดียระดับมืออาชีพขนาดใหญ่สำหรับบริษัทของฉัน คำอธิบายโพสต์:\n\n ##post## \n\nคำอธิบายบริษัท:\n ##description## \n\nชื่อบริษัท:\n ##title## \n\n"],

            ['id' => 940, 'template_id' => 26, 'key' => "uk-UA", 'value' => "Створіть великий професійний допис у соціальних мережах для моєї компанії. Опис допису:\n\n ##post## \n\nОпис компанії:\n ##description## \n\nНазва компанії:\n ##title## \n\n"],

            ['id' => 941, 'template_id' => 26, 'key' => "lt-LT", 'value' => "Sukurkite didelį profesionalų mano įmonės socialinės žiniasklaidos įrašą. Įrašo aprašas:   \n\n ##post## \n\nĮmonės aprašymas:\n ##description## \n\nĮmonės pavadinimas:\n ##title## \n\n"],

            ['id' => 942, 'template_id' => 26, 'key' => "bg-BG", 'value' => "Създайте голяма професионална публикация в социалните медии за моята компания. Описание на публикацията:\n\n ##post## \n\nОписание на фирмата:\n ##description## \n\nИме на фирмата:\n ##title## \n\n"],

            ['id' => 943, 'template_id' => 27, 'key' => "en-US", 'value' => "Write a long creative headline for the following product to run on Facebook aimed at:\n\n ##audience## \n\n Product name:\n ##title## \n\n Product description:\n ##description## \n\n Tone of voice of the headline must be:\n ##tone_language## \n\n"],

            ['id' => 944, 'template_id' => 27, 'key' => "ar-AE", 'value' => "اكتب عنوانًا إبداعيًا طويلاً للمنتج التالي ليتم تشغيله على Facebook بهدف:\n\n ##audience## \n\nاسم المنتج:\n ##title## \n\nوصف المنتج:\n ##description## \n\nيجب أن تكون نبرة صوت العنوان:\n ##tone_language## \n\n"],

            ['id' => 945, 'template_id' => 27, 'key' => "cmn-CN", 'value' => "为以下产品写一个长创意标题以在 Facebook 上运行，旨在：\n\n ##audience## \n\n 产品名称：\n ##title## \n\n 产品描述：\n ##description## \n\n 标题的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 946, 'template_id' => 27, 'key' => "hr-HR", 'value' => "Napišite dugačak kreativni naslov za sljedeći proizvod koji će se prikazivati na Facebooku s ciljem:\n\n ##audience## \n\n Naziv proizvoda:\n ##title## \n\n Opis proizvoda:\n ##description## \n\n Ton glasa naslova mora biti:\n ##tone_language## \n\n"],

            ['id' => 947, 'template_id' => 27, 'key' => "cs-CZ", 'value' => "Napište dlouhý kreativní nadpis pro následující produkt, který bude spuštěn na Facebooku:\n\n ##audience## \n\n Název produktu:\n ##title## \n\n Popis produktu:\n ##description## \n\n Tón hlasu titulku musí být:\n ##tone_language## \n\n"],

            ['id' => 948, 'template_id' => 27, 'key' => "da-DK", 'value' => "Skriv en lang kreativ overskrift til følgende produkt, der skal køre på Facebook, rettet mod:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen i overskriften skal være:\n ##tone_language## \n\n"],

            ['id' => 949, 'template_id' => 27, 'key' => "nl-NL", 'value' => "Skriv en lang kreativ overskrift til følgende produkt, der skal køre på Facebook, rettet mod:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen i overskriften skal være:\n ##tone_language## \n\n"],

            ['id' => 950, 'template_id' => 27, 'key' => "et-EE", 'value' => "Kirjutage Facebookis käivitamiseks järgmise toote jaoks pikk loominguline pealkiri, mille eesmärk on:\n\n ##audience## \n\n Toote nimi:\n ##title## \n\n Toote kirjeldus:\n ##description## \n\n Pealkirja hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 951, 'template_id' => 27, 'key' => "fi-FI", 'value' =>"Kirjoita pitkä luova otsikko seuraavalle tuotteelle Facebookissa käytettäväksi:\n\n ##audience## \n\n Tuotteen nimi:\n ##title## \n\n Tuotteen kuvaus:\n ##description## \n\n Otsikon äänensävyn on oltava:\n ##tone_language## \n\n"],

            ['id' => 952, 'template_id' => 27, 'key' => "fr-FR", 'value' => "Écrivez un long titre créatif pour le produit suivant à diffuser sur Facebook destiné à :\n\n ##audience## \n\n Nom du produit :\n ##title## \n\n Description du produit :\n ##description## \n\n Le ton de la voix du titre doit être :\n ##tone_language## \n\n"],

            ['id' => 953, 'template_id' => 27, 'key' => "de-DE", 'value' =>"Schreiben Sie eine lange kreative Überschrift für das folgende Produkt, das auf Facebook laufen soll:\n\n ##audience## \n\n Produktname:\n ##title## \n\n Produktbeschreibung:\n ##description## \n\n Tonfall der Überschrift muss sein:\n ##tone_language## \n\n"],

            ['id' => 954, 'template_id' => 27, 'key' => "el-GR", 'value' => "Γράψτε μια μακρά δημιουργική επικεφαλίδα για το ακόλουθο προϊόν για προβολή στο Facebook με στόχο:\n\n ##audience## \n\n Όνομα προϊόντος:\n ##title## \n\n Περιγραφή προϊόντος:\n ##description## \n\n Ο τόνος της φωνής της επικεφαλίδας πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 955, 'template_id' => 27, 'key' => "he-IL", 'value' => "כתוב כותרת יצירתית ארוכה למוצר הבא שיוצג בפייסבוק שמטרתה:\n\n ##audience## \n\n שם המוצר:\n ##title## \n\n תיאור המוצר:\n ##description## \n\n גוון הקול של הכותרת חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 956, 'template_id' => 27, 'key' => "hi-IN", 'value' => "Facebook पर चलने के लिए निम्न उत्पाद के लिए एक लंबी क्रिएटिव हेडलाइन लिखें:\n\n ##audience## \n\n उत्पाद का नाम:\n ##title## \n\n उत्पाद विवरण:\n ##description## \n\n शीर्षक का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 957, 'template_id' => 27, 'key' => "hu-HU", 'value' => "Írjon egy hosszú kreatív címsort a következő termékhez a Facebookon való futtatáshoz, amelynek célja:\n\n ##audience## \n\n Terméknév:\n ##title## \n\n Termékleírás:\n ##description## \n\n A címsor hangszínének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 958, 'template_id' => 27, 'key' => "is-IS", 'value' => "Skrifaðu langa skapandi fyrirsögn fyrir eftirfarandi vöru til að birtast á Facebook sem miðar að:\n\n ##audience## \n\n Vöruheiti:\n ##title## \n\n Vörulýsing:\n ##description## \n\n Rödd í fyrirsögninni verður að vera:\n ##tone_language## \n\n"],

            ['id' => 959, 'template_id' => 27, 'key' => "id-ID", 'value' => "Tulis judul kreatif yang panjang untuk produk berikut agar berjalan di Facebook yang ditujukan untuk:\n\n ##audience## \n\n Nama produk:\n ##title## \n\n Deskripsi produk:\n ##description## \n\n Nada suara judul harus:\n ##tone_language## \n\n"],

            ['id' => 960, 'template_id' => 27, 'key' => "it-IT", 'value' => "Scrivi un lungo titolo creativo per il seguente prodotto da pubblicare su Facebook destinato a:\n\n ##audience## \n\n Nome prodotto:\n ##title## \n\n Descrizione del prodotto:\n ##description## \n\n Il tono di voce del titolo deve essere:\n ##tone_language## \n\n"],

            ['id' => 961, 'template_id' => 27, 'key' => "ja-JP", 'value' => "次の製品の長いクリエイティブな見出しを書いて、Facebook で実行することを目的としています:\n\n ##audience## \n\n 製品名:\n ##title## \n\n 製品説明:\n ##description## \n\n 見出しの口調は次のようにする必要があります:\n ##tone_language## \n\n"],

            ['id' => 962, 'template_id' => 27, 'key' => "ko-KR", 'value' => "Facebook에서 실행할 다음 제품에 대한 길고 창의적인 헤드라인을 작성하세요.\n\n ##audience## \n\n 제품 이름:\n ##title## \n\n 제품 설명:\n ##description## \n\n 헤드라인의 어조는 다음과 같아야 합니다.\n ##tone_language## \n\n"],

            ['id' => 963, 'template_id' => 27, 'key' => "ms-MY", 'value' => "Tulis tajuk kreatif yang panjang untuk produk berikut disiarkan di Facebook bertujuan:\n\n ##audience## \n\n Nama produk:\n ##title## \n\n Penerangan produk:\n ##description## \n\n Nada suara tajuk mestilah:\n ##tone_language## \n\n"],

            ['id' => 964, 'template_id' => 27, 'key' => "nb-NO", 'value' => "Skriv en lang kreativ overskrift for følgende produkt å kjøre på Facebook rettet mot:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen i overskriften må være:\n ##tone_language## \n\n"],

            ['id' => 965, 'template_id' => 27, 'key' => "pl-PL", 'value' => "Napisz długi kreatywny nagłówek dla następującego produktu do wyświetlania na Facebooku, którego celem jest:\n\n ##audience## \n\n Nazwa produktu:\n ##title## \n\n Opis produktu:\n ##description## \n\n Ton nagłówka musi być:\n ##tone_language## \n\n"],

            ['id' => 966, 'template_id' => 27, 'key' => "pt-PT", 'value' => "Escreva um longo título criativo para o seguinte produto a ser executado no Facebook destinado a:\n\n ##audience## \n\n Nome do produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do título deve ser:\n ##tone_language## \n\n"],

            ['id' => 967, 'template_id' => 27, 'key' => "ru-RU", 'value' => "Напишите длинный креативный заголовок для следующего продукта, который будет запущен на Facebook и нацелен на:\n\n ##audience## \n\n Название продукта:\n ##title## \n\n Описание товара:\n ##description## \n\n Тон голоса заголовка должен быть:\n ##tone_language## \n\n"],

            ['id' => 968, 'template_id' => 27, 'key' => "es-ES", 'value' => "Escribe un título creativo largo para que el siguiente producto se ejecute en Facebook dirigido a:\n\n ##audience## \n\n Nombre del producto:\n ##title## \n\n Descripción del producto:\n ##description## \n\n El tono de voz del titular debe ser:\n ##tone_language## \n\n"],

            ['id' => 969, 'template_id' => 27, 'key' => "sv-SE", 'value' => "Skriv en lång kreativ rubrik för följande produkt att köra på Facebook som syftar till:\n\n ##audience## \n\n Produktnamn:\n ##title## \n\n Produktbeskrivning:\n ##description## \n\n Tonen i rubriken måste vara:\n ##tone_language## \n\n"],

            ['id' => 970, 'template_id' => 27, 'key' => "tr-TR", 'value' => "Aşağıdaki ürünün Facebook'ta yayınlanması için uzun bir yaratıcı başlık yazın:\n\n ##audience## \n\n Ürün adı:\n ##title## \n\n Ürün açıklaması:\n ##description## \n\n Başlığın ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 971, 'template_id' => 27, 'key' => "pt-BR", 'value' => "Escreva um longo título criativo para o seguinte produto a ser executado no Facebook destinado a:\n\n ##audience## \n\n Nome do produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do título deve ser:\n ##tone_language## \n\n"],

            ['id' => 972, 'template_id' => 27, 'key' => "ro-RO", 'value' => "Scrieți un titlu creativ lung pentru următorul produs care să fie difuzat pe Facebook, care vizează:\n\n ##audience## \n\n Nume produs:\n ##title## \n\n Descrierea produsului:\n ##description## \n\n Tonul vocii titlului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 973, 'template_id' => 27, 'key' => "vi-VN", 'value' => "Viết một dòng tiêu đề sáng tạo dài cho sản phẩm sau để chạy trên Facebook nhằm mục đích:\n\n ##audience## \n\n Tên sản phẩm:\n ##title## \n\n Mô tả sản phẩm:\n ##description## \n\n Giọng điệu của tiêu đề phải là:\n ##tone_language## \n\n"],

            ['id' => 974, 'template_id' => 27, 'key' => "sw-KE", 'value' => "Andika kichwa kirefu cha ubunifu ili bidhaa ifuatayo iendeshwe kwenye Facebook inayolenga:\n\n ##audience## \n\n Jina la bidhaa:\n ##title## \n\n Maelezo ya bidhaa:\n ##description## \n\n Toni ya sauti ya kichwa lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 975, 'template_id' => 27, 'key' => "sl-SI", 'value' => "Napišite dolg kreativni naslov za naslednji izdelek, ki bo deloval na Facebooku in bo namenjen:\n\n ##audience## \n\n Ime izdelka:\n ##title## \n\n Opis izdelka:\n ##description## \n\n Ton glasu naslova mora biti:\n ##tone_language## \n\n"],

            ['id' => 976, 'template_id' => 27, 'key' => "th-TH", 'value' => "เขียนพาดหัวโฆษณาแบบยาวเพื่อให้ผลิตภัณฑ์ต่อไปนี้ทำงานบน Facebook โดยมุ่งเป้าไปที่:\n\n ##audience## \n\n ชื่อผลิตภัณฑ์:\n ##title## \n\n รายละเอียดสินค้า:\n ##description## \n\n น้ำเสียงพาดหัวต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 977, 'template_id' => 27, 'key' => "uk-UA", 'value' => "Напишіть довгий креативний заголовок для наступного продукту для показу на Facebook, спрямованого на:\n\n ##audience## \n\n Назва продукту:\n ##title## \n\n Опис продукту:\n ##description## \n\n Тон заголовка має бути:\n ##tone_language## \n\n"],

            ['id' => 978, 'template_id' => 27, 'key' => "lt-LT", 'value' => "Parašykite ilgą kūrybinę antraštę, kad šis produktas būtų paleistas „Facebook“ ir skirtas:\n\n ##audience## \n\n Produkto pavadinimas:\n ##title## \n\n Produkto aprašymas:\n ##description## \n\n Antraštės balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 979, 'template_id' => 27, 'key' => "bg-BG", 'value' => "Напишете дълго творческо заглавие за следния продукт, който да се пусне във Facebook и е насочен към:\n\n ##audience## \n\n Име на продукта:\n ##title## \n\n Описание на продукта:\n ##description## \n\n Тонът на гласа на заглавието трябва да бъде:\n ##tone_language## \n\n"],
                
            ['id' => 980, 'template_id' => 28, 'key' => "en-US", 'value' => "Write catchy 30-character headlines to promote your product with Google Ads. Product name:\n\n ##title## \n\nProduct description:\n ##description## \n\nTarget audience for ad:\n ##audience## \n\nTone of voice of the headline must be:\n ##tone_language## \n\n"],

            ['id' => 981, 'template_id' => 28, 'key' => "ar-AE", 'value' => "اكتب عناوين جذابة مكونة من 30 حرفًا للترويج لمنتجك باستخدام إعلانات Google. اسم المنتج:\n\n ##title## \n\nوصف المنتج:\n ##description## \n\nالجمهور المستهدف للإعلان:\n ##audience## \n\nيجب أن تكون نغمة الصوت في العنوان:\n ##tone_language## \n\n"],

            ['id' => 982, 'template_id' => 28, 'key' => "cmn-CN", 'value' => "撰写醒目的 30 个字符的标题，以使用 Google Ads 宣传您的产品。产品名称：\n\n ##title## \n\n产品描述：\n ##description## \n\n广告的目标受众：\n ##audience## \n\n标题的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 983, 'template_id' => 28, 'key' => "hr-HR", 'value' => "Napišite upečatljive naslove od 30 znakova kako biste promovirali svoj proizvod uz Google Ads. Naziv proizvoda:\n\n ##title## \n\nOpis proizvoda:\n ##description## \n\nCiljana publika za oglas:\n ##audience## \n\nTon glasa naslova mora biti:\n ##tone_language## \n\n"],

            ['id' => 984, 'template_id' => 28, 'key' => "cs-CZ", 'value' => "Napište chytlavé nadpisy o délce 30 znaků a propagujte svůj produkt pomocí Google Ads. Název produktu:\n\n ##title## \n\nPopis produktu:\n ##description## \n\nCílové publikum pro reklamu:\n ##audience## \n\nTón hlasu titulku musí být:\n ##tone_language## \n\n"],

            ['id' => 985, 'template_id' => 28, 'key' => "da-DK", 'value' => "Skriv fængende overskrifter på 30 tegn for at promovere dit produkt med Google Ads. Produktnavn:\n\n ##title## \n\nProduktbeskrivelse:\n ##description## \n\nMålgruppe for annonce:\n ##audience## \n\nTone i overskriften skal være:\n ##tone_language## \n\n"],

            ['id' => 986, 'template_id' => 28, 'key' => "nl-NL", 'value' => "Schrijf pakkende koppen van 30 tekens om uw product te promoten met Google Ads. Productnaam:\n\n ##title## \n\nProductbeschrijving:\n ##description## \n\nDoelgroep voor advertentie:\n ##audience## \n\nDe toon van de kop moet zijn:\n ##tone_language## \n\n"],

            ['id' => 987, 'template_id' => 28, 'key' => "et-EE", 'value' => "Kirjutage meeldejäävaid 30-märgilisi pealkirju, et reklaamida oma toodet Google Adsiga. Toote nimi:\n\n ##title## \n\nTootekirjeldus:\n ##description## \n\nReklaami sihtrühm:\n ##audience## \n\nPealkirja hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 988, 'template_id' => 28, 'key' => "fi-FI", 'value' => "Kirjoita tarttuvia 30 merkin pituisia otsikoita mainostaaksesi tuotettasi Google Adsin avulla. Tuotteen nimi:\n\n ##title## \n\nTuotteen kuvaus:\n ##description## \n\nMainoksen kohdeyleisö:\n ##audience## \n\nOtsikon äänensävyn tulee olla:\n ##tone_language## \n\n"],

            ['id' => 989, 'template_id' => 28, 'key' => "fr-FR", 'value' => "Rédigez des titres accrocheurs de 30 caractères pour promouvoir votre produit avec Google Ads. Nom du produit :\n\n ##title## \n\nDescription du produit :\n ##description## \n\nAudience cible pour l'annonce :\n ##audience## \n\nLe ton de la voix du titre doit être :\n ##tone_language## \n\n"],

            ['id' => 990, 'template_id' => 28, 'key' => "de-DE", 'value' => "Schreiben Sie ansprechende Überschriften mit 30 Zeichen, um Ihr Produkt mit Google Ads zu bewerben. Produktname:\n\n ##title## \n\nProduktbeschreibung:\n ##description## \n\nZielpublikum für Anzeige:\n ##audience## \n\nTonlage der Überschrift muss sein:\n ##tone_language## \n\n"],

            ['id' => 991, 'template_id' => 28, 'key' => "el-GR", 'value' => "Γράψτε εντυπωσιακούς τίτλους 30 χαρακτήρων για να προωθήσετε το προϊόν σας με το Google Ads. Όνομα προϊόντος:\n\n ##title## \n\nΠεριγραφή προϊόντος:\n ##description## \n\nΣτόχευση κοινού για διαφήμιση:\n ##audience## \n\nΟ τόνος της φωνής της επικεφαλίδας πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 992, 'template_id' => 28, 'key' => "he-IL", 'value' => "צור 10 כותרות בלוג קליטות עבור:\n\n ##description## \n\n'כתוב כותרות קליטות של 30 תווים כדי לקדם את המוצר שלך עם Google Ads. שם המוצר:\n\n ##title## \n\nתיאור המוצר:\n ##description## \n\nקהל יעד למודעה:\n ##audience## \n\nטון הדיבור של הכותרת חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 993, 'template_id' => 28, 'key' => "hi-IN", 'value' => "Google Ads के साथ अपने उत्पाद का प्रचार करने के लिए आकर्षक 30-वर्ण वाली सुर्खियाँ लिखें। उत्पाद का नाम:\n\n ##title## \n\nउत्पाद विवरण:\n ##description## \n\nविज्ञापन के लिए लक्षित दर्शक:\n ##audience## \n\nशीर्षक की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 994, 'template_id' => 28, 'key' => "hu-HU", 'value' => "Írjon fülbemászó, 30 karakterből álló címsorokat, hogy reklámozza termékét a Google Ads szolgáltatással. Termék neve:\n\n ##title## \n\nTermékleírás:\n ##description## \n\nHirdetés célközönsége:\n ##audience## \n\nA címsor hangszínének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 995, 'template_id' => 28, 'key' => "is-IS", 'value' => "Skrifaðu grípandi 30 stafa fyrirsagnir til að kynna vöruna þína með Google Ads. Vöruheiti:\n\n ##title## \n\nVörulýsing:\n ##description## \n\nMarkhópur auglýsingar:\n ##audience## \n\nTónn í fyrirsögninni verður að vera:\n ##tone_language## \n\n"],

            ['id' => 996, 'template_id' => 28, 'key' => "id-ID", 'value' => "Tulis judul 30 karakter yang menarik untuk mempromosikan produk Anda dengan Google Ads. Nama produk:\n\n ##title## \n\nDeskripsi produk:\n ##description## \n\nTarget pemirsa untuk iklan:\n ##audience## \n\nNada suara judul harus:\n ##tone_language## \n\n"],

            ['id' => 997, 'template_id' => 28, 'key' => "it-IT", 'value' => "Scrivi titoli accattivanti di 30 caratteri per promuovere il tuo prodotto con Google Ads. Nome del prodotto:\n\n ##title## \n\nDescrizione del prodotto:\n ##description## \n\nPubblico di destinazione dell'annuncio:\n ##audience## \n\nIl tono di voce del titolo deve essere:\n ##tone_language## \n\n"],

            ['id' => 998, 'template_id' => 28, 'key' => "ja-JP", 'value' => "キャッチーな 30 文字の見出しを書いて、Google 広告で商品を宣伝しましょう。商品名:\n\n ##title## \n\n商品説明:\n ##description## \n\n広告のターゲット ユーザー:\n ##audience## \n\n見出しの声のトーンは次のようにする必要があります:\n ##tone_language## \n\n"],

            ['id' => 999, 'template_id' => 28, 'key' => "ko-KR", 'value' => "Google Ads로 제품을 홍보하려면 눈길을 끄는 30자의 제목을 작성하세요. 제품 이름:\n\n ##title## \n\n제품 설명:\n ##description## \n\n광고 대상:\n ##audience## \n\n제목의 어조는 다음과 같아야 합니다.\n ##tone_language## \n\n"],

            ['id' => 1000, 'template_id' => 28, 'key' => "ms-MY", 'value' => "Tulis tajuk 30 aksara yang menarik untuk mempromosikan produk anda dengan Google Ads. Nama produk:\n\n ##title## \n\nPerihalan produk:\n ##description## \n\nSasarkan khalayak untuk iklan:\n ##audience## \n\nNada suara tajuk mestilah:\n ##tone_language## \n\n"],

            ['id' => 1001, 'template_id' => 28, 'key' => "nb-NO", 'value' => "Skriv fengende overskrifter på 30 tegn for å markedsføre produktet ditt med Google Ads. Produktnavn:\n\n ##title## \n\nProduktbeskrivelse:\n ##description## \n\nMålgruppe for annonse:\n ##audience## \n\nTone i overskriften må være:\n ##tone_language## \n\n"],

            ['id' => 1002, 'template_id' => 28, 'key' => "pl-PL", 'value' => "Pisz chwytliwe 30-znakowe nagłówki, aby promować swój produkt w Google Ads. Nazwa produktu:\n\n ##title## \n\nOpis produktu:\n ##description## \n\nDocelowi odbiorcy reklamy:\n ##audience## \n\nTon nagłówka musi być następujący:\n ##tone_language## \n\n"],

            ['id' => 1003, 'template_id' => 28, 'key' => "pt-PT", 'value' => "Escreva títulos atraentes de 30 caracteres para promover seu produto com o Google Ads. Nome do produto:\n\n ##title## \n\nDescrição do produto:\n ##description## \n\nPúblico-alvo do anúncio:\n ##audience## \n\nTom de voz do título deve ser:\n ##tone_language## \n\n"],

            ['id' => 1004, 'template_id' => 28, 'key' => "ru-RU", 'value' => "Напишите броские 30-символьные заголовки, чтобы продвигать свой продукт с помощью Google Реклама. Название продукта:\n\n ##title## \n\nОписание продукта:\n ##description## \n\nЦелевая аудитория для рекламы:\n ##audience## \n\nТон заголовка должен быть:\n ##tone_language## \n\n"],

            ['id' => 1005, 'template_id' => 28, 'key' => "es-ES", 'value' => "Escriba títulos llamativos de 30 caracteres para promocionar su producto con Google Ads. Nombre del producto:\n\n ##title## \n\nDescripción del producto:\n ##description## \n\nAudiencia objetivo para el anuncio:\n ##audience## \n\nEl tono de voz del titular debe ser:\n ##tone_language## \n\n"],

            ['id' => 1006, 'template_id' => 28, 'key' => "sv-SE", 'value' => "Skriv medryckande rubriker med 30 tecken för att marknadsföra din produkt med Google Ads. Produktnamn:\n\n ##title## \n\nProduktbeskrivning:\n ##description## \n\nMålgrupp för annons:\n ##audience## \n\nTonfallet i rubriken måste vara:\n ##tone_language## \n\n"],

            ['id' => 1007, 'template_id' => 28, 'key' => "tr-TR", 'value' => "Ürününüzü Google Ads ile tanıtmak için akılda kalıcı 30 karakterlik başlıklar yazın. Ürün adı:\n\n ##title## \n\nÜrün açıklaması:\n ##description## \n\nReklamın hedef kitlesi:\n ##audience## \n\nBaşlığın ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 1008, 'template_id' => 28, 'key' => "pt-BR", 'value' => "Escreva títulos atraentes de 30 caracteres para promover seu produto com o Google Ads. Nome do produto:\n\n ##title## \n\nDescrição do produto:\n ##description## \n\nPúblico-alvo do anúncio:\n ##audience## \n\nTom de voz do título deve ser:\n ##tone_language## \n\n"],

            ['id' => 1009, 'template_id' => 28, 'key' => "ro-RO", 'value' => "Scrieți titluri atractive de 30 de caractere pentru a vă promova produsul cu Google Ads. Nume produs:\n\n ##title## \n\nDescrierea produsului:\n ##description## \n\nPublic țintă pentru anunț:\n ##audience## \n\nTonul vocii titlului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1010, 'template_id' => 28, 'key' => "vi-VN", 'value' => "Viết dòng tiêu đề hấp dẫn gồm 30 ký tự để quảng cáo sản phẩm của bạn với Google Ads. Tên sản phẩm:\n\n ##title## \n\nMô tả sản phẩm:\n ##description## \n\nĐối tượng mục tiêu cho quảng cáo:\n ##audience## \n\nGiọng điệu của tiêu đề phải là:\n ##tone_language## \n\n"],

            ['id' => 1011, 'template_id' => 28, 'key' => "sw-KE", 'value' => "Andika vichwa vya habari vya kuvutia vya herufi 30 ili kutangaza bidhaa yako ukitumia Google Ads. Jina la bidhaa:\n\n ##title## \n\nMaelezo ya bidhaa:\n ##description## \n\nHadhira lengwa ya tangazo:\n ##audience## \n\nToni ya sauti ya kichwa lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1012, 'template_id' => 28, 'key' => "sl-SI", 'value' => "Napišite privlačne naslove s 30 znaki za promocijo svojega izdelka z Google Ads. Ime izdelka:\n\n ##title## \n\nOpis izdelka:\n ##description## \n\nCiljna publika za oglas:\n ##audience## \n\nTon glasu naslova mora biti:\n ##tone_language## \n\n"],

            ['id' => 1013, 'template_id' => 28, 'key' => "th-TH", 'value' => "เขียนบรรทัดแรก 30 อักขระที่ดึงดูดใจเพื่อโปรโมตผลิตภัณฑ์ของคุณด้วย Google Ads ชื่อผลิตภัณฑ์:\n\n ##title## \n\nรายละเอียดสินค้า:\n ##description## \n\nกลุ่มเป้าหมายสำหรับโฆษณา:\n ##audience## \n\nน้ำเสียงของพาดหัวต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1014, 'template_id' => 28, 'key' => "uk-UA", 'value' => "Напишіть привабливі заголовки з 30 символів, щоб рекламувати свій продукт за допомогою Google Ads. Назва продукту:\n\n ##title## \n\nОпис товару:\n ##description## \n\nЦільова аудиторія для реклами:\n ##audience## \n\nТон заголовка має бути:\n ##tone_language## \n\n"],

            ['id' => 1015, 'template_id' => 28, 'key' => "lt-LT", 'value' => "Parašykite patrauklias 30 simbolių antraštes, kad reklamuotumėte savo produktą naudodami Google Ads. Produkto pavadinimas:\n\n ##title## \n\nProdukto aprašymas:\n ##description## \n\nSkelbimo tikslinė auditorija:\n ##audience## \n\nAntraštės balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 1016, 'template_id' => 28, 'key' => "bg-BG", 'value' => "Напишете закачливи заглавия от 30 знака, за да популяризирате продукта си с Google Ads. Име на продукта:\n\n ##title## \n\nОписание на продукта:\n ##description## \n\nЦелева аудитория за реклама:\n ##audience## \n\nТонът на заглавието трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1017, 'template_id' => 29, 'key' => "en-US", 'value' => "Write a Google Ads description that makes your ad stand out and generates leads. Target audience:\n\n ##audience## \n\n Product name:\n ##title## \n\n Product description:\n ##description## \n\n Tone of voice of the ad must be:\n ##tone_language## \n\n"],

            ['id' => 1018, 'template_id' => 29, 'key' => "ar-AE", 'value' => "اكتب وصفًا لبرنامج إعلانات Google يجعل إعلانك متميزًا ويكتسب عملاء محتملين. الجمهور المستهدف:\n\n ##audience## \n\nاسم المنتج:\n ##title## \n\nوصف المنتج:\n ##description## \n\nيجب أن تكون نغمة صوت الإعلان:\n ##tone_language## \n\n"],

            ['id' => 1019, 'template_id' => 29, 'key' => "cmn-CN", 'value' => "撰写 Google Ads 说明，使您的广告脱颖而出并带来潜在客户。目标受众：\n\n ##audience## \n\n 产品名称：\n ##title## \n\n 产品描述：\n ##description## \n\n 广告语调必须是：\n ##tone_language## \n\n'为以下内容生成 10 个吸引人的博客标题：\n\n ##description## \n\n"],

            ['id' => 1020, 'template_id' => 29, 'key' => "hr-HR", 'value' => "Napišite Google Ads opis koji ističe vaš oglas i generira potencijalne kupce. Ciljana publika:\n\n ##audience## \n\n Naziv proizvoda:\n ##title## \n\n Opis proizvoda:\n ##description## \n\n Ton glasa oglasa mora biti:\n ##tone_language## \n\n"],

            ['id' => 1021, 'template_id' => 29, 'key' => "cs-CZ", 'value' => "Napište popis Google Ads, díky kterému vaše reklama vynikne a generuje potenciální zákazníky. Cílové publikum:\n\n ##audience## \n\n Název produktu:\n ##title## \n\n Popis produktu:\n ##description## \n\n Tón hlasu reklamy musí být:\n ##tone_language## \n\n"],

            ['id' => 1022, 'template_id' => 29, 'key' => "da-DK", 'value' => "Skriv en Google Ads-beskrivelse, der får din annonce til at skille sig ud og genererer kundeemner. Målgruppe:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen i annoncen skal være:\n ##tone_language## \n\n"],

            ['id' => 1023, 'template_id' => 29, 'key' => "nl-NL", 'value' => "Schrijf een Google Ads-beschrijving waardoor uw advertentie opvalt en leads genereert. Doelgroep:\n\n ##audience## \n\n Productnaam:\n ##title## \n\n Productbeschrijving:\n ##description## \n\n Tone of voice van de advertentie moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1024, 'template_id' => 29, 'key' => "et-EE", 'value' => "Kirjutage Google Adsi kirjeldus, mis muudab teie reklaami silmapaistvaks ja loob müügivihjeid. Sihtpublik:\n\n ##audience## \n\n Toote nimi:\n ##title## \n\n Toote kirjeldus:\n ##description## \n\n Reklaami hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1025, 'template_id' => 29, 'key' => "fi-FI", 'value' => "Kirjoita Google Ads -kuvaus, joka tekee mainoksestasi erottuvan ja luo liidejä. Kohdeyleisö:\n\n ##audience## \n\n Tuotteen nimi:\n ##title## \n\n Tuotteen kuvaus:\n ##description## \n\n Mainoksen äänensävyn on oltava:\n ##tone_language## \n\n"],

            ['id' => 1026, 'template_id' => 29, 'key' => "fr-FR", 'value' => "Rédigez une description Google Ads qui permet à votre annonce de se démarquer et de générer des prospects. Public cible :\n\n ##audience## \n\n Nom du produit :\n ##title## \n\n Description du produit :\n ##description## \n\n Le ton de la voix de l'annonce doit être :\n ##tone_language## \n\n"],

            ['id' => 1027, 'template_id' => 29, 'key' => "de-DE", 'value' => "Schreiben Sie eine Google Ads-Beschreibung, die Ihre Anzeige hervorhebt und Leads generiert. Zielgruppe:\n\n ##audience## \n\n Produktname:\n ##title## \n\n Produktbeschreibung:\n ##description## \n\n Tonfall der Anzeige muss sein:\n ##tone_language## \n\n"],

            ['id' => 1028, 'template_id' => 29, 'key' => "el-GR", 'value' => "Γράψτε μια περιγραφή του Google Ads που κάνει τη διαφήμισή σας να ξεχωρίζει και να δημιουργεί δυνητικούς πελάτες. Στοχευόμενο κοινό:\n\n ##audience## \n\n Όνομα προϊόντος:\n ##title## \n\n Περιγραφή προϊόντος:\n ##description## \n\n Ο τόνος της διαφήμισης πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 1029, 'template_id' => 29, 'key' => "he-IL", 'value' => "כתוב תיאור של Google Ads שיבלוט את המודעה שלך ויוצר לידים. קהל יעד:\n\n ##audience## \n\n שם המוצר:\n ##title## \n\n תיאור המוצר:\n ##description## \n\n טון הדיבור של המודעה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 1030, 'template_id' => 29, 'key' => "hi-IN", 'value' => "एक Google Ads विवरण लिखें जो आपके विज्ञापन को सबसे अलग बनाता है और लीड उत्पन्न करता है। लक्षित ऑडियंस:\n\n ##audience## \n\n उत्पाद का नाम:\n ##title## \n\n उत्पाद विवरण:\n ##description## \n\n विज्ञापन का स्वर ऐसा होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 1031, 'template_id' => 29, 'key' => "hu-HU", 'value' => "Írjon olyan Google Ads-leírást, amely kiemeli hirdetését, és potenciális ügyfeleket generál. Célközönség:\n\n ##audience## \n\n Terméknév:\n ##title## \n\n Termékleírás:\n ##description## \n\n A hirdetés hangnemének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1032, 'template_id' => 29, 'key' => "is-IS", 'value' => "Skrifaðu Google Ads lýsingu sem gerir auglýsinguna þína áberandi og gefur af sér leiðir. Markhópur:\n\n ##audience## \n\n Vöruheiti:\n ##title## \n\n Vörulýsing:\n ##description## \n\n Röddtónn auglýsingarinnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1033, 'template_id' => 29, 'key' => "id-ID", 'value' => "Tulis deskripsi Google Ads yang menonjolkan iklan Anda dan menghasilkan prospek. Audiens target:\n\n ##audience## \n\n Nama produk:\n ##title## \n\n Deskripsi produk:\n ##description## \n\n Nada suara iklan harus:\n ##tone_language## \n\n"],

            ['id' => 1034, 'template_id' => 29, 'key' => "it-IT", 'value' => "Scrivi una descrizione di Google Ads che faccia risaltare il tuo annuncio e generi lead. Pubblico di destinazione:\n\n ##audience## \n\n Nome prodotto:\n ##title## \n\n Descrizione del prodotto:\n ##description## \n\n Il tono di voce dell'annuncio deve essere:\n ##tone_language## \n\n"],

            ['id' => 1035, 'template_id' => 29, 'key' => "ja-JP", 'value' => "広告を目立たせ、リードを生み出す Google 広告の説明を書いてください。対象ユーザー:\n\n ##audience## \n\n 製品名:\n ##title## \n\n 製品説明:\n ##description## \n\n 広告のトーンは次のようにする必要があります:\n ##tone_language## \n\n"],

            ['id' => 1036, 'template_id' => 29, 'key' => "ko-KR", 'value' => "광고를 돋보이게 하고 리드를 생성하는 Google Ads 설명을 작성하세요. 타겟층:\n\n ##audience## \n\n 제품 이름:\n ##title## \n\n 제품 설명:\n ##description## \n\n 광고의 어조는 다음과 같아야 합니다.\n ##tone_language## \n\n"],

            ['id' => 1037, 'template_id' => 29, 'key' => "ms-MY", 'value' => "Tulis perihalan Google Ads yang menjadikan iklan anda menonjol dan menjana petunjuk. Khalayak sasaran:\n\n ##audience## \n\n Nama produk:\n ##title## \n\n Penerangan produk:\n ##description## \n\n Nada suara iklan mestilah:\n ##tone_language## \n\n"],

            ['id' => 1038, 'template_id' => 29, 'key' => "nb-NO", 'value' => "Skriv en Google Ads-beskrivelse som gjør at annonsen din skiller seg ut og genererer potensielle kunder. Målgruppe:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen i annonsen må være:\n ##tone_language## \n\n"],

            ['id' => 1039, 'template_id' => 29, 'key' => "pl-PL", 'value' => "Napisz opis Google Ads, który wyróżni Twoją reklamę i przyciągnie potencjalnych klientów. Grupa docelowa:\n\n ##audience## \n\n Nazwa produktu:\n ##title## \n\n Opis produktu:\n ##description## \n\n Ton reklamy musi być:\n ##tone_language## \n\n"],

            ['id' => 1040, 'template_id' => 29, 'key' => "pt-PT", 'value' => "Escreva uma descrição do Google Ads que destaque seu anúncio e gere leads. Público-alvo:\n\n ##audience## \n\n Nome do produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do anúncio deve ser:\n ##tone_language## \n\n"],

            ['id' => 1041, 'template_id' => 29, 'key' => "ru-RU", 'value' => "Напишите описание Google Реклама, которое выделит вашу рекламу и привлечет потенциальных клиентов. Целевая аудитория:\n\n ##audience## \n\n Название продукта:\n ##title## \n\n Описание товара:\n ##description## \n\n Тон объявления должен быть:\n ##tone_language## \n\n"],

            ['id' => 1042, 'template_id' => 29, 'key' => "es-ES", 'value' => "Escriba una descripción de Google Ads que haga que su anuncio se destaque y genere clientes potenciales. Público objetivo:\n\n ##audience## \n\n Nombre del producto:\n ##title## \n\n Descripción del producto:\n ##description## \n\n El tono de voz del anuncio debe ser:\n ##tone_language## \n\n"],

            ['id' => 1043, 'template_id' => 29, 'key' => "sv-SE", 'value' => "Skriv en Google Ads-beskrivning som får din annons att sticka ut och genererar potentiella kunder. Målgrupp:\n\n ##audience## \n\n Produktnamn:\n ##title## \n\n Produktbeskrivning:\n ##description## \n\n Tonen i annonsen måste vara:\n ##tone_language## \n\n"],

            ['id' => 1044, 'template_id' => 29, 'key' => "tr-TR", 'value' => "Reklamınızı öne çıkaran ve olası satışlar sağlayan bir Google Ads açıklaması yazın. Hedef kitle:\n\n ##audience## \n\n Ürün adı:\n ##title## \n\n Ürün açıklaması:\n ##description## \n\n Reklamın ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 1045, 'template_id' => 29, 'key' => "pt-BR", 'value' => "Escreva uma descrição do Google Ads que destaque seu anúncio e gere leads. Público-alvo:\n\n ##audience## \n\n Nome do produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do anúncio deve ser:\n ##tone_language## \n\n"],

            ['id' => 1046, 'template_id' => 29, 'key' => "ro-RO", 'value' => "Scrieți o descriere Google Ads care să vă facă anunțul în evidență și să genereze clienți potențiali. Publicul țintă:\n\n ##audience## \n\n Nume produs:\n ##title## \n\n Descrierea produsului:\n ##description## \n\n Tonul vocii al anunțului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1047, 'template_id' => 29, 'key' => "vi-VN", 'value' => "Viết mô tả Google Ads giúp quảng cáo của bạn nổi bật và tạo khách hàng tiềm năng. Đối tượng mục tiêu:\n\n ##audience## \n\n Tên sản phẩm:\n ##title## \n\n Mô tả sản phẩm:\n ##description## \n\n Giọng điệu của quảng cáo phải là:\n ##tone_language## \n\n"],

            ['id' => 1048, 'template_id' => 29, 'key' => "sw-KE", 'value' => "Andika maelezo ya Google Ads ambayo yanafanya tangazo lako liwe bora zaidi na kuzalisha watu wanaoongoza. Hadhira inayolengwa:\n\n ##audience## \n\n Jina la bidhaa:\n ##title## \n\n Maelezo ya bidhaa:\n ##description## \n\n Toni ya sauti ya tangazo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1049, 'template_id' => 29, 'key' => "sl-SI", 'value' => "Napišite opis Google Ads, s katerim bo vaš oglas izstopal in pritegnil potencialne stranke. Ciljna publika:\n\n ##audience## \n\n Ime izdelka:\n ##title## \n\n Opis izdelka:\n ##description## \n\n Ton glasu oglasa mora biti:\n ##tone_language## \n\n"],

            ['id' => 1050, 'template_id' => 29, 'key' => "th-TH", 'value' => "เขียนคำอธิบาย Google Ads ที่ทำให้โฆษณาของคุณโดดเด่นและสร้างโอกาสในการขาย ผู้ชมเป้าหมาย:\n\n ##audience## \n\n ชื่อผลิตภัณฑ์:\n ##title## \n\n รายละเอียดสินค้า:\n ##description## \n\n น้ำเสียงของโฆษณาต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1051, 'template_id' => 29, 'key' => "uk-UA", 'value' => "Напишіть опис Google Ads, який виділятиме вашу рекламу та залучатиме потенційних клієнтів. Цільова аудиторія:\n\n ##audience## \n\n Назва продукту:\n ##title## \n\n Опис продукту:\n ##description## \n\n Тон голосу оголошення має бути:\n ##tone_language## \n\n"],

            ['id' => 1052, 'template_id' => 29, 'key' => "lt-LT", 'value' => "Parašykite Google Ads aprašą, kad jūsų skelbimas išsiskirtų ir pritrauktų potencialių klientų. Tikslinė auditorija:\n\n ##audience## \n\n Produkto pavadinimas:\n ##title## \n\n Produkto aprašymas:\n ##description## \n\n Skelbimo balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 1053, 'template_id' => 29, 'key' => "bg-BG", 'value' => "Напишете описание в Google Ads, което прави рекламата ви да се откроява и генерира потенциални клиенти. Целева аудитория:\n\n ##audience## \n\n Име на продукта:\n ##title## \n\n Описание на продукта:\n ##description## \n\n Тонът на гласа на рекламата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1054, 'template_id' => 30, 'key' => "en-US", 'value' => "Write problem-agitate-solution for the following product description:\n\n ##description## \n\nProduct name:\n ##title## \n\nTarget audience:\n ##audience## \n\n"],

            ['id' => 1055, 'template_id' => 30, 'key' => "ar-AE", 'value' => "اكتب حل المشكلات لوصف المنتج التالي:\n\n ##description## \n\n اسم المنتج و\n ##title## \n\n الجمهور المستهدف: \n ##audience## \n\n"],

            ['id' => 1056, 'template_id' => 30, 'key' => "cmn-CN", 'value' => "为以下产品描述编写问题解决方案：\n\n ##description## \nProduct name:\n ##title## \n\n 目标受众：\n ##audience## \n\n"],

            ['id' => 1057, 'template_id' => 30, 'key' => "hr-HR", 'value' => "Napišite problem-agitate-solution za sljedeći opis proizvoda:\n\n ##description## \n\nNaziv proizvoda:\n ##title## \n\nCiljana publika:\n ##audience## \n\n"],

            ['id' => 1058, 'template_id' => 30, 'key' => "cs-CZ", 'value' => "Napište problém-agitovat-řešení pro následující popis produktu:\n\n ##description## \n\n\Název produktu:\n ##title## \n\nCílové publikum:\n ##audience## \n\n"],

            ['id' => 1059, 'template_id' => 30, 'key' => "da-DK", 'value' => "Skriv problem-agitere-løsning til følgende produktbeskrivelse:\n\n ##description## \n\nProduktnavn:\n ##title## \n\nMålgruppe:\n ##audience## \n\n"],

            ['id' => 1060, 'template_id' => 30, 'key' => "nl-NL", 'value' => "Schrijf probleem-agitatie-oplossing voor de volgende productbeschrijving:\n\n ##description## \n\nProductnaam:\n ##title## \n\nDoelgroep:\n ##audience## \n\n"],

            ['id' => 1061, 'template_id' => 30, 'key' => "et-EE", 'value' => "Kirjutage probleem-agitate-lahendus järgmisele tootekirjeldusele:\n\n ##description## \n\nToote nimi:\n ##title## \n\nSihtpublik:\n ##audience## \n\n"],

            ['id' => 1062, 'template_id' => 30, 'key' => "fi-FI", 'value' => "Kirjoita ongelma-agitate-ratkaisu seuraavaan tuotekuvaukseen:\n\n ##description## \n\nTuotteen nimi:\n ##title## \n\nKohdeyleisö:\n ##audience## \n\n"],

            ['id' => 1063, 'template_id' => 30, 'key' => "fr-FR", 'value' => "Écrivez problem-agitate-solution pour la description de produit suivante :\n\n ##description## \n\nNom du produit :\n ##title## \n\nPublic cible :\n ##audience## \n\n"],

            ['id' => 1064, 'template_id' => 30, 'key' => "de-DE", 'value' => "Problem-agitate-solution für die folgende Produktbeschreibung schreiben:\n\n ##description## \n\nProduktname:\n ##title## \n\nZielgruppe:\n ##audience## \n\n"],

            ['id' => 1065, 'template_id' => 30, 'key' => "el-GR", 'value' => "Γράψτε πρόβλημα-ανακίνηση-λύση για την ακόλουθη περιγραφή προϊόντος:\n\n ##description## \n\nProduct name:\n ##title## \n\nΣτοχευόμενο κοινό:\n ##audience## \n\n"],

            ['id' => 1066, 'template_id' => 30, 'key' => "he-IL", 'value' => "כתוב פתרון לבעיה-ערעור עבור תיאור המוצר הבא:\n\n ##description## \n\שם המוצר:\n ##title## \n\nקהל יעד:\n ##audience## \n\n"],

            ['id' => 1067, 'template_id' => 30, 'key' => "hi-IN", 'value' => "निम्नलिखित उत्पाद विवरण के लिए समस्या-आंदोलन-समाधान लिखें:\n\n ##description## \n\nप्रोडक्ट का नाम:\n ##title## \n\nलक्षित दर्शक:\n ##audience## \n\n"],

            ['id' => 1068, 'template_id' => 30, 'key' => "hu-HU", 'value' => "Írjon probléma-agitáció-megoldást a következő termékleíráshoz:\n\n ##description## \n\nTerméknév:\n ##title## \n\nCélközönség:\n ##audience## \n\n"],

            ['id' => 1069, 'template_id' => 30, 'key' => "is-IS", 'value' => "Skrifaðu vandamál-agitate-lausn fyrir eftirfarandi vörulýsingu:\n\n ##description## \n\nVöruheiti:\n ##title## \n\nMarkhópur:\n ##audience## \n\n"],

            ['id' => 1070, 'template_id' => 30, 'key' => "id-ID", 'value' => "Tulis problem-agitate-solution untuk deskripsi produk berikut:\n\n ##description## \n\nNama produk:\n ##title## \n\nAudiens target:\n ##audience## \n\n"],

            ['id' => 1071, 'template_id' => 30, 'key' => "it-IT", 'value' => "Scrivi problem-agitate-solution per la seguente descrizione del prodotto:\n\n ##description## \n\nNome prodotto:\n ##title## \n\nPubblico di destinazione:\n ##audience## \n\n"],

            ['id' => 1072, 'template_id' => 30, 'key' => "ja-JP", 'value' => "次の製品説明について、問題 - 扇動 - 解決策を書いてください:\n\n ##description##\n\n商品名:\n ##title## \n\n対象視聴者:\n ##audience## \n\n"],

            ['id' => 1073, 'template_id' => 30, 'key' => "ko-KR", 'value' => "다음 제품 설명에 대한 문제-동요-해결책 쓰기:\n\n ##description## \n제품 이름:\및 \n##title## \n\n대상:\n ##audience## \n\n"],

            ['id' => 1074, 'template_id' => 30, 'key' => "ms-MY", 'value' => "Tulis problem-agitate-solution untuk penerangan produk berikut:\n\n ##description## \n\nNama produk:\n ##title## \n\nKhalayak sasaran:\n ##audience## \n\n"],

            ['id' => 1075, 'template_id' => 30, 'key' => "nb-NO", 'value' => "Skriv problem-agitere-løsning for følgende produktbeskrivelse:\n\n ##description## \n\nProduktnavn:\n ##title## \n\nMålgruppe:\n ##audience## \n\n"],

            ['id' => 1076, 'template_id' => 30, 'key' => "pl-PL", 'value' => "Napisz problem-agitate-solution dla następującego opisu produktu:\n\n ##description## \n\nNazwa produktu:\n ##title## \n\nDocelowi odbiorcy:\n ##audience## \n\n"],

            ['id' => 1077, 'template_id' => 30, 'key' => "pt-PT", 'value' => "Escrever problema-agitar-solução para a seguinte descrição do produto:\n\n ##description## \n\nNome do produto:\n ##title## \n\nPúblico-alvo:\n ##audience## \n\n"],

            ['id' => 1078, 'template_id' => 30, 'key' => "ru-RU", 'value' => "Напишите проблему-агитация-решение для следующего описания продукта:\n\n ##description## \n\nНазвание продукта:\n ##title## \n\nЦелевая аудитория:\n ##audience## \n\n"],

            ['id' => 1079, 'template_id' => 30, 'key' => "es-ES", 'value' => "Escriba problema-agitación-solución para la siguiente descripción del producto:\n\n ##description## \n\nNombre del producto:\n ##title## \n\nAudiencia objetivo:\n ##audience## \n\n"],

            ['id' => 1080, 'template_id' => 30, 'key' => "sv-SE", 'value' => "Skriv problem-agitera-lösning för följande produktbeskrivning:\n\n ##description## \n\nProduktnamn:\n ##title## \n\nMålgrupp:\n ##audience## \n\n"],

            ['id' => 1081, 'template_id' => 30, 'key' => "tr-TR", 'value' => "Aşağıdaki ürün açıklaması için problem-ajitasyon-çözüm yazın:\n\n ##description## \n\nÜrün adı:\n ##title## \n\nHedef kitle:\n ##audience## \n\n"],

            ['id' => 1082, 'template_id' => 30, 'key' => "pt-BR", 'value' => "Escrever problema-agitar-solução para a seguinte descrição do produto:\n\n ##description## \n\nNome do produto:\n ##title## \n\nPúblico-alvo:\n ##audience## \n\n"],

            ['id' => 1083, 'template_id' => 30, 'key' => "ro-RO", 'value' => "Scrieți problem-agitate-solution pentru următoarea descriere a produsului:\n\n ##description## \n\nNume produs:\n ##title## \n\nPublic țintă:\n ##audience## \n\n"],

            ['id' => 1084, 'template_id' => 30, 'key' => "vi-VN", 'value' => "Viết giải pháp kích động vấn đề cho phần mô tả sản phẩm sau:\n\n ##description## \n\nTên sản phẩm:\n ##title## \n\nĐối tượng mục tiêu:\n ##audience## \n\n"],

            ['id' => 1085, 'template_id' => 30, 'key' => "sw-KE", 'value' => "Andika utatuzi wa tatizo kwa maelezo yafuatayo ya bidhaa:\n\n ##description## \n\nJina la bidhaa:\n ##title## \n\nHadhira lengwa:\n ##audience## \n\n"],

            ['id' => 1086, 'template_id' => 30, 'key' => "sl-SI", 'value' => "Napišite problem-agitate-solution za naslednji opis izdelka:\n\n ##description## \n\nIme izdelka:\n##title## \n\nCiljna publika:\n ##audience## \n\n"],

            ['id' => 1087, 'template_id' => 30, 'key' => "th-TH", 'value' => "เขียนปัญหา-ปั่นป่วน-แก้ปัญหาสำหรับคำอธิบายผลิตภัณฑ์ต่อไปนี้:\n\n ##description## \n\nชื่อผลิตภัณฑ์:\n ##title## \n\nกลุ่มเป้าหมาย:\n ##audience## \n\n"],

            ['id' => 1088, 'template_id' => 30, 'key' => "uk-UA", 'value' => "Напишіть problem-agitate-solution для такого опису продукту:\n\n ##description## \n\nНазва продукту:\n ##title## \n\nЦільова аудиторія:\n ##audience## \n\n"],

            ['id' => 1089, 'template_id' => 30, 'key' => "lt-LT", 'value' => "Rašykite problemą-agituokite-sprendimą šiam gaminio aprašymui:\n\n ##description## \n\nProdukto pavadinimas:\n ##title## \n\nTikslinė auditorija:\n ##audience## \n\n"],

            ['id' => 1090, 'template_id' => 30, 'key' => "bg-BG", 'value' => "Напишете проблем-раздвижване-решение за следното описание на продукта:\n\n ##description## \nИме на продукта:\n ##title## \n\nЦелева аудитория:\n ##audience## \n\n"],

            ['id' => 1091, 'template_id' => 31, 'key' => "en-US", 'value' => "Write an academic essay about:\n\n ##title## \n\nUse following keywords in the essay:\n ##keywords## \n\nTone of voice of the essay must be:\n ##tone_language## \n\n"],

            ['id' => 1092, 'template_id' => 31, 'key' => "ar-AE", 'value' => "اكتب مقالًا أكاديميًا حول:\n\n ##title## \n\nاستخدم الكلمات الأساسية التالية في المقال:\n ##keywords## \n\nيجب أن تكون نغمة صوت المقال:\n ##tone_language## \n\n"],

            ['id' => 1093, 'template_id' => 31, 'key' => "cmn-CN", 'value' => "写一篇关于：\n\n”的学术论文 ##title## \n\n在文章中使用以下关键词：\n ##keywords## \n\n文章的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 1094, 'template_id' => 31, 'key' => "hr-HR", 'value' => "Napišite akademski esej o:\n\n ##title## \n\nKoristite sljedeće ključne riječi u eseju:\n ##keywords## \n\nTon glasa eseja mora biti:\n ##tone_language## \n\n"],

            ['id' => 1095, 'template_id' => 31, 'key' => "cs-CZ", 'value' =>"Napište akademickou esej o:\n\n ##title## \n\nV eseji použijte následující klíčová slova:\n ##keywords## \n\nTón hlasu eseje musí být:\n ##tone_language## \n\n"],

            ['id' => 1096, 'template_id' => 31, 'key' => "da-DK", 'value' => "Skriv et akademisk essay om:\n\n ##title## \n\nBrug følgende nøgleord i essayet:\n ##keywords## \n\nStemmetonen i essayet skal være:\n ##tone_language## \n\n"],

            ['id' => 1097, 'template_id' => 31, 'key' => "nl-NL", 'value' => "Schrijf een academisch essay over:\n\n ##title## \n\nGebruik de volgende trefwoorden in het essay:\n ##keywords## \n\nDe toon van het essay moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1098, 'template_id' => 31, 'key' => "et-EE", 'value' => "Kirjutage akadeemiline essee teemal:\n\n ##title## \n\nKasutage essees järgmisi märksõnu:\n ##keywords## \n\nEssee hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1099, 'template_id' => 31, 'key' => "fi-FI", 'value' => "Kirjoita akateeminen essee aiheesta:\n\n ##title## \n\nKäytä esseessä seuraavia avainsanoja:\n ##keywords## \n\nEsseen äänensävyn on oltava:\n ##tone_language## \n\n"],

            ['id' => 1100, 'template_id' => 31, 'key' => "fr-FR", 'value' => "Rédigez une dissertation académique sur :\n\n ##title## \n\nUtilisez les mots clés suivants dans lessai :\n ##keywords## \n\nLe ton de la dissertation doit être :\n ##tone_language## \n\n"],

            ['id' => 1101, 'template_id' => 31, 'key' => "de-DE", 'value' => "Schreiben Sie einen akademischen Aufsatz über:\n\n ##title## \n\nVerwenden Sie folgende Schlüsselwörter im Aufsatz:\n ##keywords## \n\nTonlage des Aufsatzes muss sein:\n ##tone_language## \n\n"],

            ['id' => 1102, 'template_id' => 31, 'key' => "el-GR", 'value' => "Γράψτε ένα ακαδημαϊκό δοκίμιο για:\n\n ##title## \n\nΧρησιμοποιήστε τις ακόλουθες λέξεις-κλειδιά στο δοκίμιο:\n ##keywords## \n\nΟ τόνος της φωνής του δοκιμίου πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 1103, 'template_id' => 31, 'key' => "he-IL", 'value' => "כתוב חיבור אקדמי על:\n\n ##title## \n\nהשתמש במילות המפתח הבאות במאמר:\n ##keywords## \n\nטון הדיבור של החיבור חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 1104, 'template_id' => 31, 'key' => "hi-IN", 'value' => "के बारे में एक अकादमिक निबंध लिखें:\n\n ##title## \n\nनिबंध में निम्नलिखित कीवर्ड का प्रयोग करें:\n ##keywords## \n\nनिबंध का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 1105, 'template_id' => 31, 'key' => "hu-HU", 'value' => "Írjon tudományos esszét erről:\n\n ##title## \n\nHasználja a következő kulcsszavakat az esszében:\n ##keywords## \n\nAz esszé hangszínének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1106, 'template_id' => 31, 'key' => "is-IS", 'value' => "Skrifaðu fræðilega ritgerð um:\n\n ##title## \n\nNotaðu eftirfarandi lykilorð í ritgerðinni:\n ##keywords## \n\nTónn í ritgerðinni verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1107, 'template_id' => 31, 'key' => "id-ID", 'value' => "Tulis esai akademik tentang:\n\n ##title## \n\nGunakan kata kunci berikut dalam esai:\n ##keywords## \n\nNada suara esai harus:\n ##tone_language## \n\n"],

            ['id' => 1108, 'template_id' => 31, 'key' => "it-IT", 'value' => "Scrivi un saggio accademico su:\n\n ##title## \n\nUsa le seguenti parole chiave nel saggio:\n ##keywords## \n\nIl tono di voce del saggio deve essere:\n ##tone_language## \n\n"],

            ['id' => 1109, 'template_id' => 31, 'key' => "ja-JP", 'value' => "以下について学術論文を書きます:\n\n ##title## \n\nエッセイでは次のキーワードを使用してください:\n ##keywords## \n\nエッセイの口調は:\n ##tone_language## \n\n"],

            ['id' => 1110, 'template_id' => 31, 'key' => "ko-KR", 'value' => "다음에 대한 학술 에세이 쓰기:\n\n ##title## \n\n에세이에서 다음 키워드를 사용하십시오:\n ##keywords## \n\n에세이의 목소리 톤은 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 1111, 'template_id' => 31, 'key' => "ms-MY", 'value' => "Tulis esei akademik tentang:\n\n ##title## \n\nGunakan kata kunci berikut dalam esei:\n ##keywords## \n\nNada suara esei mestilah:\n ##tone_language## \n\n"],

            ['id' => 1112, 'template_id' => 31, 'key' => "nb-NO", 'value' => "Skriv et akademisk essay om:\n\n ##title## \n\nBruk følgende nøkkelord i essayet:\n ##keywords## \n\nTone i essayet må være:\n ##tone_language## \n\n"],

            ['id' => 1113, 'template_id' => 31, 'key' => "pl-PL", 'value' => "Napisz esej akademicki na temat:\n\n ##title## \n\nUżyj w eseju następujących słów kluczowych:\n ##keywords## \n\nTon wypowiedzi w eseju musi być:\n ##tone_language## \n\n"],

            ['id' => 1114, 'template_id' => 31, 'key' => "pt-PT", 'value' => "Escreva um ensaio acadêmico sobre:\n\n ##title## \n\nUse as seguintes palavras-chave no ensaio:\n ##keywords## \n\nTom de voz da redação deve ser:\n ##tone_language## \n\n"],

            ['id' => 1115, 'template_id' => 31, 'key' => "ru-RU", 'value' => "Напишите академическое эссе о:\n\n ##title## \n\nИспользуйте следующие ключевые слова в эссе:\n ##keywords## \n\nТон голоса эссе должен быть:\n ##tone_language## \n\n"],

            ['id' => 1116, 'template_id' => 31, 'key' => "es-ES", 'value' => "Escribe un ensayo académico sobre:\n\n ##title## \n\nUtilice las siguientes palabras clave en el ensayo:\n ##keywords## \n\nEl tono de voz del ensayo debe ser:\n ##tone_language## \n\n"],

            ['id' => 1117, 'template_id' => 31, 'key' => "sv-SE", 'value' => "Skriv en akademisk uppsats om:\n\n ##title## \n\nAnvänd följande nyckelord i uppsatsen:\n ##keywords## \n\nRösten i uppsatsen måste vara:\n ##tone_language## \n\n"],

            ['id' => 1118, 'template_id' => 31, 'key' => "tr-TR", 'value' => "Şunun hakkında akademik bir makale yazın:\n\n ##title## \n\nMakalede şu anahtar kelimeleri kullanın:\n ##keywords## \n\nYazının ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 1119, 'template_id' => 31, 'key' => "pt-BR", 'value' => "Escreva um ensaio acadêmico sobre:\n\n ##title## \n\nUse as seguintes palavras-chave no ensaio:\n ##keywords## \n\nTom de voz da redação deve ser:\n ##tone_language## \n\n"],

            ['id' => 1120, 'template_id' => 31, 'key' => "ro-RO", 'value' => "Scrieți un eseu academic despre:\n\n ##title## \n\nFolosiți următoarele cuvinte cheie în eseu:\n ##keywords## \n\nTonul vocii al eseului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1121, 'template_id' => 31, 'key' => "vi-VN", 'value' => "Viết một bài luận học thuật về:\n\n ##title## \n\nSử dụng các từ khóa sau trong bài luận:\n ##keywords## \n\nGiọng điệu của bài luận phải:\n ##tone_language## \n\n"],

            ['id' => 1122, 'template_id' => 31, 'key' => "sw-KE", 'value' => "Andika insha ya kitaaluma kuhusu:\n\n ##title## \n\nTumia manenomsingi yafuatayo katika insha:\n ##keywords## \n\nToni ya sauti ya insha lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1123, 'template_id' => 31, 'key' => "sl-SI", 'value' =>  "Napišite akademski esej o:\n\n ##title## \n\nV eseju uporabite naslednje ključne besede:\n ##keywords## \n\nTon glasu eseja mora biti:\n ##tone_language## \n\n"],

            ['id' => 1124, 'template_id' => 31, 'key' => "th-TH", 'value' => "เขียนเรียงความเชิงวิชาการเกี่ยวกับ:\n\n ##title## \n\nใช้คำหลักต่อไปนี้ในเรียงความ:\n ##keywords## \n\nน้ำเสียงของเรียงความต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1125, 'template_id' => 31, 'key' => "uk-UA", 'value' => "Напишіть науковий твір про:\n\n ##title## \n\nВикористовуйте такі ключові слова в есе:\n ##keywords## \n\nТон есе має бути:\n ##tone_language## \n\n"],

            ['id' => 1126, 'template_id' => 31, 'key' => "lt-LT", 'value' => "Parašykite akademinį rašinį apie:\n\n ##title## \n\nRašinyje naudokite šiuos raktinius žodžius:\n ##keywords## \n\nEsė balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 1127, 'template_id' => 31, 'key' => "bg-BG", 'value' => "Напишете академично есе за:\n\n ##title## \n\nИзползвайте следните ключови думи в есето:\n ##keywords## \n\nТонът на есето трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1128, 'template_id' => 32, 'key' => "en-US", 'value' => "Write a welcome email about:\n\n ##description## \n\nOur company or product name is:\n ##title## \n\nTarget audience is:\n ##keywords## \n\nTone of voice of the welcome email must be:\n ##tone_language## \n\n"],

            ['id' => 1129, 'template_id' => 32, 'key' => "ar-AE", 'value' => "اكتب بريدًا إلكترونيًا ترحيبيًا عن:\n\n ##description## \n\nاسم الشركة أو المنتج هو:\n ##title## \n\nالجمهور المستهدف هو:\n ##keywords## \n\n يجب أن تكون نغمة صوت البريد الإلكتروني الترحيبي:\n ##tone_language## \n\n"],

            ['id' => 1130, 'template_id' => 32, 'key' => "cmn-CN", 'value' => "写一封关于以下内容的欢迎电子邮件：\n\n ##description## \n\n我们的公司或产品名称是：\n ##title## \n\n目标受众是：\n ##keywords## \n\n欢迎邮件的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 1131, 'template_id' => 32, 'key' => "hr-HR", 'value' => "Napišite poruku dobrodošlice o:\n\n ##description## \n\nIme naše tvrtke ili proizvoda je:\n ##title## \n\nCiljana publika je:\n ##keywords## \n\nTon glasa e-pošte dobrodošlice mora biti:\n ##tone_language## \n\n"],

            ['id' => 1132, 'template_id' => 32, 'key' => "cs-CZ", 'value' => "Napišite poruku dobrodošlice o:\n\n ##description## \n\nIme naše tvrtke ili proizvoda je:\n ##title## \n\nCiljana publika je:\n ##keywords## \n\nTon glasa e-pošte dobrodošlice mora biti:\n ##tone_language## \n\n"],

            ['id' => 1133, 'template_id' => 32, 'key' => "da-DK", 'value' => "Skriv en velkomstmail om:\n\n ##description## \n\nVores firma- eller produktnavn er:\n ##title## \n\nMålgruppe er:\n ##keywords## \n\nTone i velkomstmailen skal være:\n ##tone_language## \n\n"],

            ['id' => 1134, 'template_id' => 32, 'key' => "nl-NL", 'value' => "Schrijf een welkomstmail over:\n\n ##description## \n\nOnze bedrijfs- of productnaam is:\n ##title## \n\nDoelgroep is:\n ##keywords## \n\nDe toon van de welkomstmail moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1135, 'template_id' => 32, 'key' => "et-EE", 'value' => "Kirjutage tervitusmeil teemal:\n\n ##description## \n\nMeie ettevõtte või toote nimi on:\n ##title## \n\nSihtpublik on:\n ##keywords## \n\nTervitusmeili hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1136, 'template_id' => 32, 'key' => "fi-FI", 'value' => "Kirjoita tervetuloa sähköposti aiheesta:\n\n ##description## \n\nYrityksemme tai tuotteemme nimi on:\n ##title## \n\nKohdeyleisö on:\n ##keywords## \n\nTervetuloviestin äänensävyn on oltava:\n ##tone_language## \n\n"],

            ['id' => 1137, 'template_id' => 32, 'key' => "fr-FR", 'value' => "Écrivez un e-mail de bienvenue à propos de :\n\n ##description## \n\nLe nom de notre entreprise ou de notre produit est :\n ##title## \n\nLe public cible est :\n ##keywords## \n\nLe ton de la voix de l'e-mail de bienvenue doit être :\n ##tone_language## \n\n"],

            ['id' => 1138, 'template_id' => 32, 'key' => "de-DE", 'value' => "Willkommens-E-Mail schreiben über:\n\n ##description## \n\nUnser Firmen- oder Produktname lautet:\n ##title## \n\nZielpublikum ist:\n ##keywords## \n\nTonfall der Willkommens-E-Mail muss sein:\n ##tone_language## \n\n"],

            ['id' => 1139, 'template_id' => 32, 'key' => "el-GR", 'value' => "Γράψτε ένα email καλωσορίσματος σχετικά με:\n\n ##description## \n\nΗ εταιρεία ή το όνομα του προϊόντος μας είναι:\n ##title## \n\nΤο κοινό-στόχος είναι:\n ##keywords## \n\nΟ τόνος της φωνής του email καλωσορίσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 1140, 'template_id' => 32, 'key' => "he-IL", 'value' => "כתוב הודעת קבלת פנים על:\n\n ##description## \n\nשם החברה או המוצר שלנו הוא:\n ##title## \n\nקהל היעד הוא:\n ##keywords## \n\nנימת הקול של הודעת קבלת הפנים חייבת להיות:\n ##tone_language## \n\n"],

            ['id' => 1141, 'template_id' => 32, 'key' => "hi-IN", 'value' => "इस बारे में एक स्वागत योग्य ईमेल लिखें:\n\n ##description## \n\nहमारी कंपनी या उत्पाद का नाम है:\n ##title## \n\nलक्षित दर्शक हैं:\n ##keywords## \n\nस्वागत ईमेल का स्वर इस प्रकार होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 1142, 'template_id' => 32, 'key' => "hu-HU", 'value' => "Írjon üdvözlő e-mailt erről:\n\n ##description## \n\nCégünk vagy termékünk neve:\n ##title## \n\nA célközönség:\n ##keywords## \n\nAz üdvözlő e-mail hangjának a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1143, 'template_id' => 32, 'key' => "is-IS", 'value' => "Skrifaðu velkominn tölvupóst um:\n\n ##description## \n\nFyrirtækis eða vöruheiti okkar er:\n ##title## \n\nMarkhópur er:\n ##keywords## \n\nTónn í móttökupóstinum verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1144, 'template_id' => 32, 'key' => "id-ID", 'value' => "Tulis email selamat datang tentang:\n\n ##description## \n\nNama perusahaan atau produk kami adalah:\n ##title## \n\nTarget audiens adalah:\n ##keywords## \n\nNada suara email selamat datang harus:\n ##tone_language## \n\n"],

            ['id' => 1145, 'template_id' => 32, 'key' => "it-IT", 'value' => "Scrivi un'e-mail di benvenuto su:\n\n ##description## \n\nIl nome della nostra azienda o prodotto è:\n ##title## \n\nIl pubblico di destinazione è:\n ##keywords## \n\nIl tono della mail di benvenuto deve essere:\n ##tone_language## \n\n"],

            ['id' => 1146, 'template_id' => 32, 'key' => "ja-JP", 'value' => "ウェルカム メールを書いてください:\n\n ##description## \n\n当社または製品名は次のとおりです:\n ##title## \n\n対象者:\n ##keywords## \n\nウェルカム メールのトーンは次のようにする必要があります:\n ##tone_language## \n\n"],

            ['id' => 1147, 'template_id' => 32, 'key' => "ko-KR", 'value' => "다음에 대한 환영 이메일 작성:\n\n ##description## \n\n저희 회사 또는 제품 이름은:\n ##title## \n\n대상은:\n ##keywords## \n\n환영 이메일의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 1148, 'template_id' => 32, 'key' => "ms-MY", 'value' => "Tulis e-mel alu-aluan tentang:\n\n ##description## \n\nNama syarikat atau produk kami ialah:\n ##title## \n\nKhalayak sasaran ialah:\n ##keywords## \n\nNada suara e-mel alu-aluan mestilah:\n ##tone_language## \n\n"],

            ['id' => 1149, 'template_id' => 32, 'key' => "nb-NO", 'value' => "Skriv en velkomst-e-post om:\n\n ##description## \n\nVårt firma eller produktnavn er:\n ##title## \n\nMålgruppen er:\n ##keywords## \n\nStemmetonen i velkomst-e-posten må være:\n ##tone_language## \n\n"],

            ['id' => 1150, 'template_id' => 32, 'key' => "pl-PL", 'value' => "Napisz powitalną wiadomość e-mail na temat:\n\n ##description## \n\nNazwa naszej firmy lub produktu to:\n ##title## \n\nDocelowi odbiorcy to:\n ##keywords## \n\nTon powitalnego e-maila musi być:\n ##tone_language## \n\n"],

            ['id' => 1151, 'template_id' => 32, 'key' => "pt-PT", 'value' => "Escreva um e-mail de boas-vindas sobre:\n\n ##description## \n\nO nome da nossa empresa ou produto é:\n ##title## \n\nO público-alvo é:\n ##keywords## \n\nTom de voz do e-mail de boas-vindas deve ser:\n ##tone_language## \n\n"],

            ['id' => 1152, 'template_id' => 32, 'key' => "ru-RU", 'value' => "Напишите приветственное письмо о:\n\n ##description## \n\nНазвание нашей компании или продукта:\n ##title## \n\nЦелевая аудитория:\n ##keywords## \n\nТон приветственного письма должен быть:\n ##tone_language## \n\n"],

            ['id' => 1153, 'template_id' => 32, 'key' => "es-ES", 'value' => "Escribe un correo electrónico de bienvenida sobre:\n\n ##description## \n\nEl nombre de nuestra empresa o producto es:\n ##title## \n\nEl público objetivo es:\n ##keywords## \n\nEl tono de voz del email de bienvenida debe ser:\n ##tone_language## \n\n"],

            ['id' => 1154, 'template_id' => 32, 'key' => "sv-SE", 'value' => "Skriv ett välkomstmail om:\n\n ##description## \n\nVårt företag eller produktnamn är:\n ##title## \n\nMålgruppen är:\n ##keywords## \n\nRösten i välkomstmeddelandet måste vara:\n ##tone_language## \n\n"],

            ['id' => 1155, 'template_id' => 32, 'key' => "tr-TR", 'value' => "Şunun hakkında bir karşılama e-postası yazın:\n\n ##description## \n\nŞirketimizin veya ürünümüzün adı:\n ##title## \n\nHedef kitle:\n ##keywords## \n\nKarşılama e-postasının ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 1156, 'template_id' => 32, 'key' => "pt-BR", 'value' => "Escreva um e-mail de boas-vindas sobre:\n\n ##description## \n\nO nome da nossa empresa ou produto é:\n ##title## \n\nO público-alvo é:\n ##keywords## \n\nTom de voz do e-mail de boas-vindas deve ser:\n ##tone_language## \n\n"],

            ['id' => 1157, 'template_id' => 32, 'key' => "ro-RO", 'value' => "Scrieți un e-mail de bun venit despre:\n\n ##description## \n\nNumele companiei sau al produsului nostru este:\n ##title## \n\nPublicul țintă este:\n ##keywords## \n\nTonul vocii al e-mailului de bun venit trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1158, 'template_id' => 32, 'key' => "vi-VN", 'value' => "Viết email chào mừng về:\n\n ##description## \n\nTên sản phẩm hoặc công ty của chúng tôi là:\n ##title## \n\nĐối tượng mục tiêu là:\n ##keywords## \n\nGiọng điệu của email chào mừng phải là:\n ##tone_language## \n\n"],

            ['id' => 1159, 'template_id' => 32, 'key' => "sw-KE", 'value' => "Andika barua pepe ya kukaribisha kuhusu:\n\n ##description## \n\nJina la kampuni au bidhaa yetu ni:\n ##title## \n\nHadhira inayolengwa ni:\n ##keywords## \n\nToni ya sauti ya barua pepe ya kukaribisha lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1160, 'template_id' => 32, 'key' => "sl-SI", 'value' => "Napišite pozdravno e-pošto o:\n\n ##description## \n\nIme našega podjetja ali izdelka je:\n ##title## \n\nCiljna publika je:\n ##keywords## \n\nTon glasu pozdravnega e-poštnega sporočila mora biti:\n ##tone_language## \n\n"],

            ['id' => 1161, 'template_id' => 32, 'key' => "th-TH", 'value' => "เขียนอีเมลต้อนรับเกี่ยวกับ:\n\n ##description## \n\nชื่อบริษัทหรือผลิตภัณฑ์ของเราคือ:\n ##title## \n\nกลุ่มเป้าหมายคือ:\n ##keywords## \n\nเสียงของอีเมลต้อนรับต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1162, 'template_id' => 32, 'key' => "uk-UA", 'value' => "Напишіть вітальний лист про:\n\n ##description## \n\nНазва нашої компанії або продукту:\n ##title## \n\nЦільова аудиторія:\n ##keywords## \n\nТон привітального листа має бути:\n ##tone_language## \n\n"],

            ['id' => 1163, 'template_id' => 32, 'key' => "lt-LT", 'value' => "Parašykite sveikinimo laišką apie:\n\n ##description## \n\nMūsų įmonės arba produkto pavadinimas yra:\n ##title## \n\nTikslinė auditorija yra:\n ##keywords## \n\nPasveikinimo el. laiško balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 1164, 'template_id' => 32, 'key' => "bg-BG", 'value' => "Напишете приветствен имейл за:\n\n ##description## \n\nИмето на нашата компания или продукт е:\n ##title## \n\nЦелевата аудитория е:\n ##keywords## \n\nТонът на приветствения имейл трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1165, 'template_id' => 33, 'key' => "en-US", 'value' => "Write a cold email about:\n\n ##description## \n\nOur company or product name is:\n ##title## \n\nContext to include in the cold email:\n ##keywords## \n\nTone of voice of the cold email must be:\n ##tone_language## \n\n"],

            ['id' => 1166, 'template_id' => 33, 'key' => "ar-AE", 'value' => "اكتب بريدًا إلكترونيًا باردًا حول:\n\n ##description## \n\nاسم الشركة أو المنتج هو:\n ##title## \n\nالسياق المراد تضمينه في البريد الإلكتروني البارد:\n ##keywords## \n\nيجب أن تكون نغمة صوت البريد الإلكتروني البارد:\n ##tone_language## \n\n"],

            ['id' => 1167, 'template_id' => 33, 'key' => "cmn-CN", 'value' => "写一封冷电子邮件：\n\n ##description## \n\n我们的公司或产品名称是：\n ##title## \n\n要包含在冷电子邮件中的上下文：\n ##keywords## \n\n冷邮件的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 1168, 'template_id' => 33, 'key' => "hr-HR", 'value' => "Napišite hladnu e-poštu o:\n\n ##description## \n\nIme naše tvrtke ili proizvoda je:\n ##title## \n\nKontekst za uključivanje u hladnu e-poštu:\n ##keywords## \n\nTon glasa hladne e-pošte mora biti:\n ##tone_language## \n\n"],

            ['id' => 1169, 'template_id' => 33, 'key' => "cs-CZ", 'value' => "Napište chladný e-mail o:\n\n ##description## \n\nNázev naší společnosti nebo produktu je:\n ##title## \n\nKontext, který se má zahrnout do studeného e-mailu:\n ##keywords## \n\nTón hlasu chladného e-mailu musí být:\n ##tone_language## \n\n"],

            ['id' => 1170, 'template_id' => 33, 'key' => "da-DK", 'value' => "Skriv en kold e-mail om:\n\n ##description## \n\nVores firma- eller produktnavn er:\n ##title## \n\nKontekst, der skal inkluderes i den kolde e-mail:\n ##keywords## \n\nTone i den kolde e-mail skal være:\n ##tone_language## \n\n"],

            ['id' => 1171, 'template_id' => 33, 'key' => "nl-NL", 'value' => "Schrijf een koude e-mail over:\n\n ##description## \n\nOnze bedrijfs- of productnaam is:\n ##title## \n\nContext om op te nemen in de koude e-mail:\n ##keywords## \n\nDe toon van de koude e-mail moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1172, 'template_id' => 33, 'key' => "et-EE", 'value' => "Kirjutage külma meili teemal:\n\n ##description## \n\nMeie ettevõtte või toote nimi on:\n ##title## \n\nKülmasse meili lisatav kontekst:\n ##keywords## \n\nKülma meili hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1173, 'template_id' => 33, 'key' => "fi-FI", 'value' => "Kirjoita kylmä sähköposti aiheesta:\n\n ##description## \n\nYrityksemme tai tuotteemme nimi on:\n ##title## \n\nKonteksti sisällytettäväksi kylmään sähköpostiin:\n ##keywords## \n\nKylmän sähköpostin äänensävyn on oltava:\n ##tone_language## \n\n"],

            ['id' => 1174, 'template_id' => 33, 'key' => "fr-FR", 'value' => "Écrivez un e-mail froid à propos de :\n\n ##description## \n\nLe nom de notre entreprise ou de notre produit est :\n ##title## \n\nContexte à inclure dans le cold email :\n ##keywords## \n\nLe ton de la voix de l'e-mail froid doit être :\n ##tone_language## \n\n"],

            ['id' => 1175, 'template_id' => 33, 'key' => "de-DE", 'value' => "Schreiben Sie eine kalte E-Mail über:\n\n ##description## \n\nUnser Firmen- oder Produktname lautet:\n ##title## \n\nIn die Cold-E-Mail aufzunehmender Kontext:\n ##keywords## \n\nTonfall der kalten E-Mail muss sein:\n ##tone_language## \n\n"],

            ['id' => 1176, 'template_id' => 33, 'key' => "el-GR", 'value' => "Γράψτε ένα κρύο email για:\n\n ##description## \n\nΗ εταιρεία ή το όνομα του προϊόντος μας είναι:\n 117title## \n\nΠλαίσιο που θα συμπεριληφθεί στο κρύο email:\n ##keywords## \n\nΟ τόνος της φωνής του κρύου email πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 1177, 'template_id' => 33, 'key' => "he-IL", 'value' => "כתוב אימייל קר על:\n\n ##description## \n\nשם החברה או המוצר שלנו הוא:\n ##title## \n\nהקשר לכלול בדוא הקר:\n ##keywords## \n\nטון הדיבור של האימייל הקר חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 1178, 'template_id' => 33, 'key' => "hi-IN", 'value' => "इस बारे में एक ठंडा ईमेल लिखें:\n\n ##description## \n\nहमारी कंपनी या उत्पाद का नाम है:\n ##title## \n\nकोल्ड ईमेल में शामिल करने के लिए प्रसंग:\n ##keywords## \n\nठंडे ईमेल की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 1179, 'template_id' => 33, 'key' => "hu-HU", 'value' => "Írjon hideg e-mailt erről:\n\n ##description## \n\nCégünk vagy termékünk neve:\n ##title## \n\nA hideg e-mailben szereplő kontextus:\n ##keywords## \n\nA hideg e-mail hangszínének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1180, 'template_id' => 33, 'key' => "is-IS", 'value' => "Skrifaðu kalt tölvupóst um:\n\n ##description## \n\nFyrirtækis eða vöruheiti okkar er:\n ##title## \n\nSamhengi til að hafa með í kalda tölvupóstinum:\n ##keywords## \n\nTónn í kalda tölvupóstinum verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1181, 'template_id' => 33, 'key' => "id-ID", 'value' => "Tulis email dingin tentang:\n\n ##description## \n\nNama perusahaan atau produk kami adalah:\n ##title## \n\nKonteks untuk disertakan dalam email dingin:\n ##keywords## \n\nNada suara email dingin harus:\n ##tone_language## \n\n"],

            ['id' => 1182, 'template_id' => 33, 'key' => "it-IT", 'value' => "Scrivi una fredda email su:\n\n ##description## \n\nIl nome della nostra azienda o prodotto è:\n ##title## \n\nContesto da includere nell'email fredda:\n ##keywords## \n\nIl tono di voce dell'email fredda deve essere:\n ##tone_language## \n\n"],

            ['id' => 1183, 'template_id' => 33, 'key' => "ja-JP", 'value' => "以下についての冷たいメールを書いてください:\n\n ##description## \n\n当社または製品名は次のとおりです:\n ##title## \n\nコールド メールに含めるコンテキスト:\n ##keywords## \n\nコールド メールのトーンは次のとおりです:\n ##tone_language## \n\n"],

            ['id' => 1184, 'template_id' => 33, 'key' => "ko-KR", 'value' => "다음에 대한 콜드 이메일 작성:\n\n ##description## \n\n저희 회사 또는 제품 이름은:\n ##title## \n\n콜드 이메일에 포함할 컨텍스트:\n ##keywords## \n\n콜드 이메일의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 1185, 'template_id' => 33, 'key' => "ms-MY", 'value' => "Tulis e-mel sejuk tentang:\n\n ##description## \n\nNama syarikat atau produk kami ialah:\n ##title## \n\nKonteks untuk disertakan dalam e-mel sejuk:\n ##keywords## \n\nNada suara e-mel sejuk mestilah:\n ##tone_language## \n\n"],

            ['id' => 1186, 'template_id' => 33, 'key' => "nb-NO", 'value' => "Skriv en kald e-post om:\n\n ##description## \n\nVårt firma eller produktnavn er:\n ##title## \n\nKontekst som skal inkluderes i den kalde e-posten:\n ##keywords## \n\nStemmetonen til den kalde e-posten må være:\n ##tone_language## \n\n"],

            ['id' => 1187, 'template_id' => 33, 'key' => "pl-PL", 'value' => "Napisz zimny e-mail na temat:\n\n ##description## \n\nNazwa naszej firmy lub produktu to:\n ##title## \n\nKontekst do uwzględnienia w zimnej wiadomości e-mail:\n ##keywords## \n\nTon zimnej wiadomości e-mail musi być:\n ##tone_language## \n\n"],

            ['id' => 1188, 'template_id' => 33, 'key' => "pt-PT", 'value' => "Escreva um e-mail frio sobre:\n\n ##description## \n\nO nome da nossa empresa ou produto é:\n ##title## \n\nContexto para incluir no e-mail frio:\n ##keywords## \n\nTom de voz do e-mail frio deve ser:\n ##tone_language## \n\n"],

            ['id' => 1189, 'template_id' => 33, 'key' => "ru-RU", 'value' => "Напишите холодное электронное письмо о:\n\n ##description## \n\nНазвание нашей компании или продукта:\n ##title## \n\nКонтекст для включения в холодное электронное письмо:\n ##keywords## \n\nТон голоса холодного письма должен быть:\n ##tone_language## \n\n"],

            ['id' => 1190, 'template_id' => 33, 'key' => "es-ES", 'value' => "Escribe un correo electrónico frío sobre:\n\n ##description## \n\nEl nombre de nuestra empresa o producto es:\n ##title## \n\nContexto para incluir en el correo electrónico frío:\n ##keywords## \n\nEl tono de voz del correo frío debe ser:\n ##tone_language## \n\n"],

            ['id' => 1191, 'template_id' => 33, 'key' => "sv-SE", 'value' => "Skriv ett kallt e-postmeddelande om:\n\n ##description## \n\nVårt företag eller produktnamn är:\n ##title## \n\nKontext att inkludera i det kalla e-postmeddelandet:\n ##keywords## \n\nTonfallet för det kalla e-postmeddelandet måste vara:\n ##tone_language## \n\n"],

            ['id' => 1192, 'template_id' => 33, 'key' => "tr-TR", 'value' => "10 akılda kalıcı blog başlığı oluşturun:\n\n ##description## \n\n'Şirketimizin veya ürünümüzün adı:\n ##title## \n\nSoğuk e-postaya eklenecek içerik:\n ##keywords## \n\nSoğuk e-postanın ses tonu şöyle olmalı:\n ##tone_language## \n\n"],

            ['id' => 1193, 'template_id' => 33, 'key' => "pt-BR", 'value' => "Escreva um e-mail frio sobre:\n\n ##description## \n\nO nome da nossa empresa ou produto é:\n ##title## \n\nContexto para incluir no e-mail frio:\n ##keywords## \n\nTom de voz do e-mail frio deve ser:\n ##tone_language## \n\n"],

            ['id' => 1194, 'template_id' => 33, 'key' => "ro-RO", 'value' => "Scrieți un e-mail rece despre:\n\n ##description## \n\nNumele companiei sau al produsului nostru este:\n ##title## \n\nContext de inclus în e-mailul rece:\n ##keywords## \n\nTonul de voce al e-mailului rece trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1195, 'template_id' => 33, 'key' => "vi-VN", 'value' => "Viết một email lạnh nhạt về:\n\n ##description## \n\nTên sản phẩm hoặc công ty của chúng tôi là:\n ##title## \n\nBối cảnh bao gồm trong email lạnh:\n ##keywords## \n\nGiọng nói của email lạnh phải là:\n ##tone_language## \n\n"],

            ['id' => 1196, 'template_id' => 33, 'key' => "sw-KE", 'value' => "Andika barua pepe baridi kuhusu:\n\n ##description## \n\nJina la kampuni au bidhaa yetu ni:\n ##title## \n\nMuktadha wa kujumuisha katika barua pepe baridi:\n ##keywords## \n\nToni ya sauti ya barua pepe baridi lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1197, 'template_id' => 33, 'key' => "sl-SI", 'value' => "Napišite hladno e-pošto o:\n\n ##description## \n\nIme našega podjetja ali izdelka je:\n ##title## \n\nKontekst za vključitev v hladno e-pošto:\n ##keywords## \n\nTon glasu hladnega e-poštnega sporočila mora biti:\n ##tone_language## \n\n"],

            ['id' => 1198, 'template_id' => 33, 'key' => "th-TH", 'value' => "เขียนอีเมลเกี่ยวกับ:\n\n ##description## \n\nชื่อบริษัทหรือผลิตภัณฑ์ของเราคือ:\n ##title## \n\nบริบทที่จะรวมไว้ในอีเมลเย็น:\n ##keywords## \n\nน้ำเสียงของอีเมลเย็นต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1199, 'template_id' => 33, 'key' => "uk-UA", 'value' => "Напишіть холодний електронний лист про:\n\n ##description## \n\nНазва нашої компанії або продукту:\n ##title## \n\nКонтекст для включення в холодний електронний лист:\n ##keywords## \n\nТон холодного електронного листа має бути:\n ##tone_language## \n\n"],

            ['id' => 1200, 'template_id' => 33, 'key' => "lt-LT", 'value' => "Parašykite šaltą el. laišką apie:\n\n ##description## \n\nMūsų įmonės arba produkto pavadinimas yra:\n ##title## \n\nKontekstas, kurį reikia įtraukti į šaltą el. laišką:\n ##keywords## \n\nŠalto el. laiško balso tonas turi būti:\n ##tone_language## \n\n"  ],

            ['id' => 1201, 'template_id' => 33, 'key' => "bg-BG", 'value' => "Напишете студен имейл за:\n\n ##description## \n\nИмето на нашата компания или продукт е:\n ##title## \n\nКонтекст за включване в студения имейл:\n ##keywords## \n\nТонът на гласа на студения имейл трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1202, 'template_id' => 34, 'key' => "en-US", 'value' => "Write a follow up email about:\n\n ##description## \n\nOur company or product name is:\n ##title## \n\nFollowing up after:\n ##event## \n\nTarget audience is:\n ##keywords## \n\nTone of voice of the follow up email must be:\n ##tone_language## \n\n"],

            ['id' => 1203, 'template_id' => 34, 'key' => "ar-AE", 'value' => "اكتب رسالة متابعة بالبريد الإلكتروني حول:\n\n ##description## \n\nاسم الشركة أو المنتج هو:\n ##title## \n\nالمتابعة بعد:\n##event## \n\nالجمهور المستهدف هو:\n ##keywords## \n\nيجب أن تكون نغمة الصوت في رسالة البريد الإلكتروني للمتابعة:\n ##tone_language## \n\n"],

            ['id' => 1204, 'template_id' => 34, 'key' => "cmn-CN", 'value' => "写一封跟进电子邮件：\n\n ##description## \n\n我们的公司或产品名称是：\n ##title## \n\n跟进之后：\n ##event## \n\n目标受众是：\n ##keywords## \n\n跟进邮件的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 1205, 'template_id' => 34, 'key' => "hr-HR", 'value' => "Napišite naknadnu e-poruku o:\n\n ##description## \n\nIme naše tvrtke ili proizvoda je:\n ##title## \n\nSljedeće nakon:\n ##event## \n\nCiljana publika je:\n ##keywords## \n\nTon glasa dodatne e-pošte mora biti:\n ##tone_language## \n\n"],

            ['id' => 1206, 'template_id' => 34, 'key' => "cs-CZ", 'value' => "Napište následný e-mail o:\n\n ##description## \n\nNázev naší společnosti nebo produktu je:\n ##title## \n\nSledování po:\n ##event## \n\nCílové publikum je:\n ##keywords## \n\nTón hlasu následného e-mailu musí být:\n ##tone_language## \n\n"],

            ['id' => 1207, 'template_id' => 34, 'key' => "da-DK", 'value' => "Skriv en opfølgningsmail om:\n\n ##description## \n\nVores firma- eller produktnavn er:\n ##title## \n\nOpfølgning efter:\n ##event## \n\nMålgruppe er:\n ##keywords## \n\nTone i opfølgnings-e-mailen skal være:\n ##tone_language## \n\n"],

            ['id' => 1208, 'template_id' => 34, 'key' => "nl-NL", 'value' => "Schrijf een vervolgmail over:\n\n ##description## \n\nOnze bedrijfs- of productnaam is:\n ##title## \n\nOpvolging na:\n ##event## \n\nDoelgroep is:\n ##keywords## \n\nDe toon van de vervolgmail moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1209, 'template_id' => 34, 'key' => "et-EE", 'value' => "Kirjutage järelmeil teemal:\n\n ##description## \n\nMeie ettevõtte või toote nimi on:\n ##title## \n\nJälgimine pärast:\n ##event## \n\nSihtpublik on:\n ##keywords## \n\nJärelmeili hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1210, 'template_id' => 34, 'key' => "fi-FI", 'value' => "Kirjoita seurantasähköposti aiheesta:\n\n ##description## \n\nYrityksemme tai tuotteemme nimi on:\n ##title## \n\nSeurataan tämän jälkeen:\n ##event## \n\nKohdeyleisö on:\n ##keywords## \n\nSeurantaviestin äänensävyn on oltava:\n ##tone_language## \n\n"],

            ['id' => 1211, 'template_id' => 34, 'key' => "fr-FR", 'value' => "Rédigez un e-mail de suivi concernant :\n\n ##description## \n\nLe nom de notre entreprise ou de notre produit est :\n ##title## \n\nSuivi après :\n ##event## \n\nLe public cible est :\n ##keywords## \n\nLe ton de la voix de l'e-mail de suivi doit être :\n ##tone_language## \n\n"],

            ['id' => 1212, 'template_id' => 34, 'key' => "de-DE", 'value' => "Schreiben Sie eine Folge-E-Mail zu:\n\n ##description## \n\nUnser Firmen- oder Produktname lautet:\n ##title## \n\nNachverfolgung nach:\n ##event## \n\nZielpublikum ist:\n ##keywords## \n\nTonfall der Folge-E-Mail muss sein:\n ##tone_language## \n\n"],

            ['id' => 1213, 'template_id' => 34, 'key' => "el-GR", 'value' => "Γράψτε ένα επόμενο μήνυμα ηλεκτρονικού ταχυδρομείου σχετικά με:\n\n ##description## \n\nΗ εταιρεία ή το όνομα του προϊόντος μας είναι:\n ##title## \n\nΣυνέχεια μετά:\n ##event## \n\nΤο κοινό-στόχος είναι:\n ##keywords## \n\nΟ τόνος της φωνής του επόμενου email πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 1214, 'template_id' => 34, 'key' => "he-IL", 'value' => "כתוב אימייל מעקב על:\n\n ##description## \n\nשם החברה או המוצר שלנו הוא:\n ##title## \n\nמעקב אחרי:\n ##event## \n\nקהל היעד הוא:\n ##keywords## \n\nטון הדיבור של דואל המעקב חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 1215, 'template_id' => 34, 'key' => "hi-IN", 'value' => "इस बारे में एक अनुवर्ती ईमेल लिखें:\n\n ##description## \n\nहमारी कंपनी या उत्पाद का नाम है:\n ##title## \n\nइसके बाद:\n ##event## \n\nलक्षित दर्शक हैं:\n ##keywords## \n\nफ़ॉलो अप ईमेल का स्वर इस प्रकार होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 1216, 'template_id' => 34, 'key' => "hu-HU", 'value' => "Írjon e-mailt a következőről:\n\n ##description## \n\nCégünk vagy termékünk neve:\n ##title## \n\nKövetés a következő után:\n ##event## \n\nA célközönség:\n ##keywords## \n\nA követő e-mail hangszínének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1217, 'template_id' => 34, 'key' => "is-IS", 'value' => "Skrifaðu eftirfylgnipóst um:\n\n ##description## \n\nFyrirtækis eða vöruheiti okkar er:\n ##title## \n\nFylgst með eftir:\n ##event## \n\nMarkhópur er:\n ##keywords## \n\nTónn í eftirfylgnipóstinum verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1218, 'template_id' => 34, 'key' => "id-ID", 'value' => "Tulis email tindak lanjut tentang:\n\n ##description## \n\nNama perusahaan atau produk kami adalah:\n ##title## \n\nMenindaklanjuti setelah:\n ##event## \n\nTarget audiens adalah:\n ##keywords## \n\nNada suara email tindak lanjut harus:\n ##tone_language## \n\n"],

            ['id' => 1219, 'template_id' => 34, 'key' => "it-IT", 'value' => "Scrivi un'email di follow-up su:\n\n ##description## \n\nIl nome della nostra azienda o prodotto è:\n ##title## \n\nFollow up dopo:\n ##event## \n\nIl pubblico di destinazione è:\n ##keywords## \n\nIl tono di voce dell'email di follow-up deve essere:\n ##tone_language## \n\n"],

            ['id' => 1220, 'template_id' => 34, 'key' => "ja-JP", 'value' => "フォローアップのメールを書いてください:\n\n ##description## \n\n当社または製品名は次のとおりです:\n ##title## \n\nフォローアップ:\n ##event## \n\n対象者:\n ##keywords## \n\nフォローアップ メールのトーンは次のとおりです:\n ##tone_language## \n\n"],

            ['id' => 1221, 'template_id' => 34, 'key' => "ko-KR", 'value' => "다음에 대한 후속 이메일 작성:\n\n ##description## \n\n저희 회사 또는 제품 이름은:\n ##title## \n\n다음 이후:\n ##event## \n\n대상은:\n ##keywords## \n\n후속 이메일의 어조는 다음과 같아야 합니다.\n ##tone_language## \n\n"],

            ['id' => 1222, 'template_id' => 34, 'key' => "ms-MY", 'value' => "Tulis e-mel susulan tentang:\n\n ##description## \n\nNama syarikat atau produk kami ialah:\n ##title## \n\nMenyusul selepas:\n ##event## \n\nKhalayak sasaran ialah:\n ##keywords## \n\nNada suara e-mel susulan mestilah:\n ##tone_language## \n\n"],

            ['id' => 1223, 'template_id' => 34, 'key' => "nb-NO", 'value' => "Skriv en oppfølgings-e-post om:\n\n ##description## \n\nVårt firma eller produktnavn er:\n ##title## \n\nOppfølging etter:\n ##event## \n\nMålgruppen er:\n ##keywords## \n\nTone i oppfølgings-e-posten må være:\n ##tone_language## \n\n"],

            ['id' => 1224, 'template_id' => 34, 'key' => "pl-PL", 'value' => "Napisz e-mail uzupełniający na temat:\n\n ##description## \n\nNazwa naszej firmy lub produktu to:\n ##title## \n\nKontynuacja po:\n ##event## \n\nDocelowi odbiorcy to:\n ##keywords## \n\nTon w e-mailu uzupełniającym musi być:\n ##tone_language## \n\n"],

            ['id' => 1225, 'template_id' => 34, 'key' => "pt-PT", 'value' => "Escreva um e-mail de acompanhamento sobre:\n\n ##description## \n\nO nome da nossa empresa ou produto é:\n ##title## \n\nAcompanhamento após:\n ##event## \n\nO público-alvo é:\n ##keywords## \n\nTom de voz do e-mail de acompanhamento deve ser:\n ##tone_language## \n\n"],

            ['id' => 1226, 'template_id' => 34, 'key' => "ru-RU", 'value' => "Напишите последующее электронное письмо о:\n\n ##description## \n\nНазвание нашей компании или продукта:\n ##title## \n\nПоследующие действия после:\n ##event## \n\nЦелевая аудитория:\n ##keywords## \n\nТон голоса в последующем электронном письме должен быть:\n ##tone_language## \n\n"],

            ['id' => 1227, 'template_id' => 34, 'key' => "es-ES", 'value' => "Escribe un correo electrónico de seguimiento sobre:\n\n ##description## \n\nEl nombre de nuestra empresa o producto es:\n ##title## \n\nSeguimiento después de:\n ##event## \n\nEl público objetivo es:\n ##keywords## \n\nEl tono de voz del correo electrónico de seguimiento debe ser:\n ##tone_language## \n\n"],

            ['id' => 1228, 'template_id' => 34, 'key' => "sv-SE", 'value' => "Skriv ett uppföljningsmeddelande om:\n\n ##description## \n\nVårt företag eller produktnamn är:\n ##title## \n\nFöljer upp efter:\n ##event## \n\nMålgruppen är:\n ##keywords## \n\nRösten i uppföljningsmeddelandet måste vara:\n ##tone_language## \n\n"],

            ['id' => 1229, 'template_id' => 34, 'key' => "tr-TR", 'value' => "Şu konuda bir takip e-postası yazın:\n\n ##description## \n\nŞirketimizin veya ürünümüzün adı:\n ##title## \n\nSonra takip:\n ##event## \n\nHedef kitle:\n ##keywords## \n\nTakip e-postasının ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 1230, 'template_id' => 34, 'key' => "pt-BR", 'value' => "Escreva um e-mail de acompanhamento sobre:\n\n ##description## \n\nO nome da nossa empresa ou produto é:\n ##title## \n\nAcompanhamento após:\n ##event## \n\nO público-alvo é:\n ##keywords## \n\nTom de voz do e-mail de acompanhamento deve ser:\n ##tone_language## \n\n"],

            ['id' => 1231, 'template_id' => 34, 'key' => "ro-RO", 'value' => "Scrieți un e-mail de continuare despre:\n\n ##description## \n\nNumele companiei sau al produsului nostru este:\n ##title## \n\nUrmărire după:\n ##event## \n\nPublicul țintă este:\n ##keywords## \n\nTonul de voce al e-mailului de urmărire trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1232, 'template_id' => 34, 'key' => "vi-VN", 'value' => "Viết email tiếp theo về:\n\n ##description## \n\nTên sản phẩm hoặc công ty của chúng tôi là:\n ##title## \n\nTiếp tục sau:\n ##event## \n\nĐối tượng mục tiêu là:\n ##keywords## \n\nGiọng điệu của email tiếp theo phải là:\n ##tone_language## \n\n"],

            ['id' => 1233, 'template_id' => 34, 'key' => "sw-KE", 'value' => "Andika barua pepe ya ufuatiliaji kuhusu:\n\n ##description## \n\nJina la kampuni au bidhaa yetu ni:\n ##title## \n\nInafuata baada ya:\n ##event## \n\nHadhira inayolengwa ni:\n ##keywords## \n\nToni ya sauti ya barua pepe ya ufuatiliaji lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1234, 'template_id' => 34, 'key' => "sl-SI", 'value' => "Napišite nadaljnje e-poštno sporočilo o:\n\n ##description## \n\nIme našega podjetja ali izdelka je:\n ##title## \n\nNadaljevanje po:\n ##event## \n\nCiljna publika je:\n ##keywords## \n\nTon glasu nadaljnjega e-poštnega sporočila mora biti:\n ##tone_language## \n\n"],

            ['id' => 1235, 'template_id' => 34, 'key' => "th-TH", 'value' => "เขียนอีเมลติดตามผลเกี่ยวกับ:\n\n ##description## \n\nชื่อบริษัทหรือผลิตภัณฑ์ของเราคือ:\n ##title## \n\nติดตามผลหลังจากนี้:\n ##event## \n\nกลุ่มเป้าหมายคือ:\n ##keywords## \n\nเสียงของอีเมลติดตามจะต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1236, 'template_id' => 34, 'key' => "uk-UA", 'value' => "Напишіть додатковий електронний лист про:\n\n ##description## \n\nНазва нашої компанії або продукту:\n ##title## \n\nПодальші дії після:\n ##event## \n\nЦільова аудиторія:\n ##keywords## \n\nТон голосу в електронному листі має бути:\n ##tone_language## \n\n"],

            ['id' => 1237, 'template_id' => 34, 'key' => "lt-LT", 'value' => "Parašykite tolesnius el. laišką apie:\n\n ##description## \n\nMūsų įmonės arba produkto pavadinimas yra:\n ##title## \n\nStebimas po:\n ##event## \n\nTikslinė auditorija yra:\n ##keywords## \n\nTolesnio el. laiško balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 1238, 'template_id' => 34, 'key' => "bg-BG", 'value' => "Напишете последващ имейл за:\n\n ##description## \n\nИмето на нашата компания или продукт е:\n ##title## \n\nПоследващи действия след:\n ##event## \n\nЦелевата аудитория е:\n ##keywords## \n\nТонът на последващия имейл трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1239, 'template_id' => 35, 'key' => "en-US", 'value' => "Write a creative story about:\n\n ##description## \n\nTone of voice of the story must be:\n ##tone_language## \n\n"],

            ['id' => 1240, 'template_id' => 35, 'key' => "ar-AE", 'value' => "اكتب قصة إبداعية عن:\n\n ##description## \n\nيجب أن تكون نغمة الصوت في القصة:\n ##tone_language## \n\n"],

            ['id' => 1241, 'template_id' => 35, 'key' => "cmn-CN", 'value' => "写一个有创意的故事：\n\n ##description## \n\n故事的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 1242, 'template_id' => 35, 'key' => "hr-HR", 'value' => "Napišite kreativnu priču o:\n\n ##description## \n\nTon glasa priče mora biti:\n ##tone_language## \n\n"],

            ['id' => 1243, 'template_id' => 35, 'key' => "cs-CZ", 'value' => "Napište kreativní příběh o:\n\n ##description## \n\nTón hlasu příběhu musí být:\n ##tone_language## \n\n"],

            ['id' => 1244, 'template_id' => 35, 'key' => "da-DK", 'value' => "Skriv en kreativ historie om:\n\n ##description## \n\nTone i historien skal være:\n ##tone_language## \n\n"],

            ['id' => 1245, 'template_id' => 35, 'key' => "nl-NL", 'value' => "Schrijf een creatief verhaal over:\n\n ##description## \n\nDe toon van het verhaal moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1246, 'template_id' => 35, 'key' => "et-EE", 'value' => "Kirjutage loov lugu teemal:\n\n ##description## \n\nLoo hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1247, 'template_id' => 35, 'key' => "fi-FI", 'value' => "Kirjoita luova tarina aiheesta:\n\n ##description## \n\nTarinan äänensävyn on oltava:\n ##tone_language## \n\n"],

            ['id' => 1248, 'template_id' => 35, 'key' => "fr-FR", 'value' => "Ecrire une histoire créative sur :\n\n ##description## \n\nLe ton de la voix de l'histoire doit être :\n ##tone_language## \n\n"],

            ['id' => 1249, 'template_id' => 35, 'key' => "de-DE", 'value' => "Schreibe eine kreative Geschichte über:\n\n ##description## \n\nTonfall der Geschichte muss sein:\n ##tone_language## \n\n"],

            ['id' => 1250, 'template_id' => 35, 'key' => "el-GR", 'value' => "Γράψτε μια δημιουργική ιστορία για:\n\n ##description## \n\nΟ τόνος της φωνής της ιστορίας πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 1251, 'template_id' => 35, 'key' => "he-IL", 'value' => "כתוב סיפור יצירתי על:\n\n ##description## \n\nטון הדיבור של הסיפור חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 1252, 'template_id' => 35, 'key' => "hi-IN", 'value' => "के बारे में एक रचनात्मक कहानी लिखें:\n\n ##description## \n\nकहानी का स्वर ऐसा होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 1253, 'template_id' => 35, 'key' => "hu-HU", 'value' => "Írjon kreatív történetet erről:\n\n ##description## \n\nA történet hangnemének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1254, 'template_id' => 35, 'key' => "is-IS", 'value' => "Skrifaðu skapandi sögu um:\n\n ##description## \n\nTónn í sögunni verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1255, 'template_id' => 35, 'key' => "id-ID", 'value' => "Tulis cerita kreatif tentang:\n\n ##description## \n\nNada suara cerita harus:\n ##tone_language## \n\n"],

            ['id' => 1256, 'template_id' => 35, 'key' => "it-IT", 'value' => "Scrivi una storia creativa su:\n\n ##description## \n\nIl tono di voce della storia deve essere:\n ##tone_language## \n\n"],

            ['id' => 1257, 'template_id' => 35, 'key' => "ja-JP", 'value' => "次のようなクリエイティブなストーリーを書きましょう:\n\n ##description## \n\nストーリーの声のトーンは次のとおりでなければなりません:\n ##tone_language## \n\n"],

            ['id' => 1258, 'template_id' => 35, 'key' => "ko-KR", 'value' => "다음에 대한 창의적인 이야기 쓰기:\n\n ##description## \n\n이야기의 목소리 톤은 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 1259, 'template_id' => 35, 'key' => "ms-MY", 'value' => "Tulis cerita kreatif tentang:\n\n ##description## \n\nNada suara cerita mestilah:\n ##tone_language## \n\n"],

            ['id' => 1260, 'template_id' => 35, 'key' => "nb-NO", 'value' => "Skriv en kreativ historie om:\n\n ##description## \n\nTone i historien må være:\n ##tone_language## \n\n"],

            ['id' => 1261, 'template_id' => 35, 'key' => "pl-PL", 'value' => "Napisz kreatywną historię na temat:\n\n ##description## \n\nTon opowieści musi być:\n ##tone_language## \n\n"],

            ['id' => 1262, 'template_id' => 35, 'key' => "pt-PT", 'value' => "Escreva uma história criativa sobre:\n\n ##description## \n\nTom de voz da história deve ser:\n ##tone_language## \n\n"],

            ['id' => 1263, 'template_id' => 35, 'key' => "ru-RU", 'value' => "Напишите творческую историю о:\n\n ##description## \n\nТон голоса истории должен быть:\n ##tone_language## \n\n"],

            ['id' => 1264, 'template_id' => 35, 'key' => "es-ES", 'value' => "Escribe una historia creativa sobre:\n\n ##description## \n\nEl tono de voz de la historia debe ser:\n ##tone_language## \n\n"],

            ['id' => 1265, 'template_id' => 35, 'key' => "sv-SE", 'value' => "Skriv en kreativ berättelse om:\n\n ##description## \n\nTonfallet i berättelsen måste vara:\n ##tone_language## \n\n"],

            ['id' => 1266, 'template_id' => 35, 'key' => "tr-TR", 'value' => "Şunun hakkında yaratıcı bir hikaye yaz:\n\n ##description## \n\nHikayenin ses tonu şöyle olmalı:\n ##tone_language## \n\n"],

            ['id' => 1267, 'template_id' => 35, 'key' => "pt-BR", 'value' => "Escreva uma história criativa sobre:\n\n ##description## \n\nTom de voz da história deve ser:\n ##tone_language## \n\n"],

            ['id' => 1268, 'template_id' => 35, 'key' => "ro-RO", 'value' => "Scrieți o poveste creativă despre:\n\n ##description## \n\nTonul vocii al poveștii trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1269, 'template_id' => 35, 'key' => "vi-VN", 'value' => "Viết một câu chuyện sáng tạo về:\n\n ##description## \n\nGiọng điệu của câu chuyện phải:\n ##tone_language## \n\n"],

            ['id' => 1270, 'template_id' => 35, 'key' => "sw-KE", 'value' => "Andika hadithi ya ubunifu kuhusu:\n\n ##description## \n\nToni ya sauti ya hadithi lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1271, 'template_id' => 35, 'key' => "sl-SI", 'value' => "Napišite ustvarjalno zgodbo o:\n\n ##description## \n\nTon glasu zgodbe mora biti:\n ##tone_language## \n\n"],

            ['id' => 1272, 'template_id' => 35, 'key' => "th-TH", 'value' => "เขียนเรื่องราวที่สร้างสรรค์เกี่ยวกับ:\n\n ##description## \n\nน้ำเสียงของเรื่องต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1273, 'template_id' => 35, 'key' => "uk-UA", 'value' => "Напишіть творчу розповідь про:\n\n ##description## \n\nТон розповіді має бути:\n ##tone_language## \n\n"],

            ['id' => 1274, 'template_id' => 35, 'key' => "lt-LT", 'value' => "Parašykite kūrybinę istoriją apie:\n\n ##description## \n\nIstorijos balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 1275, 'template_id' => 35, 'key' => "bg-BG", 'value' => "Напишете творческа история за:\n\n ##description## \n\nТонът на гласа на историята трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1276, 'template_id' => 36, 'key' => "en-US", 'value' => "Check and correct grammar of this text:\n\n ##description## \n\n"],

            ['id' => 1277, 'template_id' => 36, 'key' => "ar-AE", 'value' => "تحقق من القواعد النحوية لهذا النص وصححه:\n\n ##description## \n\n"],

            ['id' => 1278, 'template_id' => 36, 'key' => "cmn-CN", 'value' => "检查并更正此文本的语法：\n\n ##description## \n\n"],

            ['id' => 1279, 'template_id' => 36, 'key' => "hr-HR", 'value' => "Provjeri i ispravi gramatiku ovog teksta:\n\n ##description## \n\n"],

            ['id' => 1280, 'template_id' => 36, 'key' => "cs-CZ", 'value' => "Zkontrolujte a opravte gramatiku tohoto textu:\n\n ##description## \n\n"],

            ['id' => 1281, 'template_id' => 36, 'key' => "da-DK", 'value' => "Tjek og ret grammatik af denne tekst:\n\n ##description## \n\n"],

            ['id' => 1282, 'template_id' => 36, 'key' => "nl-NL", 'value' => "Controleer en corrigeer de grammatica van deze tekst:\n\n ##description## \n\n"],

            ['id' => 1283, 'template_id' => 36, 'key' => "et-EE", 'value' => "Kontrollige ja parandage selle teksti grammatikat:\n\n ##description## \n\n"],

            ['id' => 1284, 'template_id' => 36, 'key' => "fi-FI", 'value' => "Tarkista ja korjaa tämän tekstin kielioppi:\n\n ##description## \n\n"],

            ['id' => 1285, 'template_id' => 36, 'key' => "fr-FR", 'value' => "Vérifiez et corrigez la grammaire de ce texte :\n\n ##description## \n\n"],

            ['id' => 1286, 'template_id' => 36, 'key' => "de-DE", 'value' => "Prüfe und korrigiere die Grammatik dieses Textes:\n\n ##description## \n\n"],

            ['id' => 1287, 'template_id' => 36, 'key' => "el-GR", 'value' => "Ελέγξτε και διορθώστε τη γραμματική αυτού του κειμένου:\n\n ##description## \n\n"],

            ['id' => 1288, 'template_id' => 36, 'key' => "he-IL", 'value' => "בדוק ותקן את הדקדוק של הטקסט הזה:\n\n ##description## \n\n"],

            ['id' => 1289, 'template_id' => 36, 'key' => "hi-IN", 'value' => "इस पाठ का व्याकरण जांचें और सही करें:\n\n ##description## \n\n"],

            ['id' => 1290, 'template_id' => 36, 'key' => "hu-HU", 'value' => "Ellenőrizze és javítsa ki ennek a szövegnek a nyelvtanát:\n\n ##description## \n\n"],

            ['id' => 1291, 'template_id' => 36, 'key' => "is-IS", 'value' => "Athugaðu og leiðréttu málfræði þessa texta:\n\n ##description## \n\n"],

            ['id' => 1292, 'template_id' => 36, 'key' => "id-ID", 'value' => "Periksa dan perbaiki tata bahasa teks ini:\n\n ##description## \n\n"],

            ['id' => 1293, 'template_id' => 36, 'key' => "it-IT", 'value' => "Controlla e correggi la grammatica di questo testo:\n\n ##description## \n\n"],

            ['id' => 1294, 'template_id' => 36, 'key' => "ja-JP", 'value' => "このテキストの文法を確認して修正してください:\n\n ##description## \n\n"],

            ['id' => 1295, 'template_id' => 36, 'key' => "ko-KR", 'value' => "이 텍스트의 문법을 확인하고 수정하십시오:\n\n ##description## \n\n"],

            ['id' => 1296, 'template_id' => 36, 'key' => "ms-MY", 'value' => "Semak dan betulkan tatabahasa teks ini:\n\n ##description## \n\n"],

            ['id' => 1297, 'template_id' => 36, 'key' => "nb-NO", 'value' => "Sjekk og korriger grammatikken til denne teksten:\n\n ##description## \n\n"],

            ['id' => 1298, 'template_id' => 36, 'key' => "pl-PL", 'value' => "Sprawdź i popraw gramatykę tego tekstu:\n\n ##description## \n\n"],

            ['id' => 1299, 'template_id' => 36, 'key' => "pt-PT", 'value' => "Verifique e corrija a gramática deste texto:\n\n ##description## \n\n"],

            ['id' => 1300, 'template_id' => 36, 'key' => "ru-RU", 'value' => "Проверьте и исправьте грамматику этого текста:\n\n ##description## \n\n"],

            ['id' => 1301, 'template_id' => 36, 'key' => "es-ES", 'value' => "Revise y corrija la gramática de este texto:\n\n ##description## \n\n"],

            ['id' => 1302, 'template_id' => 36, 'key' => "sv-SE", 'value' => "Kontrollera och korrigera grammatiken för denna text:\n\n ##description## \n\n"],

            ['id' => 1303, 'template_id' => 36, 'key' => "tr-TR", 'value' => "Bu metnin gramerini kontrol edin ve düzeltin:\n\n ##description## \n\n"],

            ['id' => 1304, 'template_id' => 36, 'key' => "pt-BR", 'value' => "Verifique e corrija a gramática deste texto:\n\n ##description## \n\n"],

            ['id' => 1305, 'template_id' => 36, 'key' => "ro-RO", 'value' => "Verificați și corectați gramatica acestui text:\n\n ##description## \n\n"],

            ['id' => 1306, 'template_id' => 36, 'key' => "vi-VN", 'value' => "Kiểm tra và sửa ngữ pháp của văn bản này:\n\n ##description## \n\n"],

            ['id' => 1307, 'template_id' => 36, 'key' => "sw-KE", 'value' => "Angalia na urekebishe sarufi ya maandishi haya:\n\n ##description## \n\n"],

            ['id' => 1308, 'template_id' => 36, 'key' => "sl-SI", 'value' => "Preveri in popravi slovnico tega besedila:\n\n ##description## \n\n"],

            ['id' => 1309, 'template_id' => 36, 'key' => "th-TH", 'value' => "ตรวจสอบและแก้ไขไวยากรณ์ของข้อความนี้:\n\n ##description## \n\n"],

            ['id' => 1310, 'template_id' => 36, 'key' => "uk-UA", 'value' => "Перевірте та виправте граматику цього тексту:\n\n ##description## \n\n"],

            ['id' => 1311, 'template_id' => 36, 'key' => "lt-LT", 'value' => "Patikrinkite ir pataisykite šio teksto gramatiką:\n\n ##description## \n\n"],

            ['id' => 1312, 'template_id' => 36, 'key' => "bg-BG", 'value' => "Проверете и коригирайте граматиката на този текст:\n\n ##description## \n\n"],

            ['id' => 1313, 'template_id' => 37, 'key' => "en-US", 'value' => "Summarize this text for 2nd grader:\n\n ##description## \n\n"],

            ['id' => 1314, 'template_id' => 37, 'key' => "ar-AE", 'value' => "تلخيص هذا النص لطلاب الصف الثاني:\n\n ##description## \n\n"],

            ['id' => 1315, 'template_id' => 37, 'key' => "cmn-CN", 'value' => "为二年级学生总结这篇课文：\n\n ##description## \n\n"],

            ['id' => 1316, 'template_id' => 37, 'key' => "hr-HR", 'value' => "Sažmi ovaj tekst za učenika 2. razreda:\n\n ##description## \n\n"],

            ['id' => 1317, 'template_id' => 37, 'key' => "cs-CZ", 'value' => "Shrňte tento text pro žáka 2. třídy:\n\n ##description## \n\n"],

            ['id' => 1318, 'template_id' => 37, 'key' => "da-DK", 'value' => "Opsummer denne tekst for 2. klasse:\n\n ##description## \n\n"],

            ['id' => 1319, 'template_id' => 37, 'key' => "nl-NL", 'value' => "Vat deze tekst samen voor groep 2:\n\n ##description## \n\n"],

            ['id' => 1320, 'template_id' => 37, 'key' => "et-EE", 'value' => "Tee sellest tekstist kokkuvõte 2. klassi õpilasele:\n\n ##description## \n\n"],

            ['id' => 1321, 'template_id' => 37, 'key' => "fi-FI", 'value' => "Tee yhteenveto tästä tekstistä 2. luokkalaiselle:\n\n ##description## \n\n"],

            ['id' => 1322, 'template_id' => 37, 'key' => "fr-FR", 'value' => "Résumez ce texte pour un élève de 2e :\n\n ##description## \n\n"],

            ['id' => 1323, 'template_id' => 37, 'key' => "de-DE", 'value' => "Fass diesen Text für die 2. Klasse zusammen:\n\n ##description## \n\n"],

            ['id' => 1324, 'template_id' => 37, 'key' => "el-GR", 'value' => "Σύνοψτε αυτό το κείμενο για μαθητή της Β' δημοτικού:\n\n ##description## \n\n"],

            ['id' => 1325, 'template_id' => 37, 'key' => "he-IL", 'value' => "סכם את הטקסט הזה עבור כיתה ב':\n\n ##description## \n\n"],

            ['id' => 1326, 'template_id' => 37, 'key' => "hi-IN", 'value' => "इस पाठ को दूसरे ग्रेडर के लिए सारांशित करें:\n\n ##description## \n\n"],

            ['id' => 1327, 'template_id' => 37, 'key' => "hu-HU", 'value' => "Összefoglalja ezt a szöveget 2. osztályosnak:\n\n ##description## \n\n"],

            ['id' => 1328, 'template_id' => 37, 'key' => "is-IS", 'value' => "Taktu saman þennan texta fyrir 2. bekk:\n\n ##description## \n\n"],

            ['id' => 1329, 'template_id' => 37, 'key' => "id-ID", 'value' => "Ringkaskan teks ini untuk siswa kelas 2:\n\n ##description## \n\n"],

            ['id' => 1330, 'template_id' => 37, 'key' => "it-IT", 'value' => "Riassumi questo testo per la seconda elementare:\n\n ##description## \n\n"],

            ['id' => 1331, 'template_id' => 37, 'key' => "ja-JP", 'value' => "2 年生向けにこのテキストを要約してください:\n\n ##description## \n\n"],

            ['id' => 1332, 'template_id' => 37, 'key' => "ko-KR", 'value' => "2학년을 위해 이 텍스트 요약:\n\n ##description## \n\n"],

            ['id' => 1333, 'template_id' => 37, 'key' => "ms-MY", 'value' => "Ringkaskan teks ini untuk pelajar gred 2:\n\n ##description## \n\n"],

            ['id' => 1334, 'template_id' => 37, 'key' => "nb-NO", 'value' => "Oppsummer denne teksten for 2. klassing:\n\n ##description## \n\n"],

            ['id' => 1335, 'template_id' => 37, 'key' => "pl-PL", 'value' => "Podsumuj ten tekst dla uczniów drugiej klasy:\n\n ##description## \n\n"],

            ['id' => 1336, 'template_id' => 37, 'key' => "pt-PT", 'value' => "Resuma este texto para aluno da 2ª série:\n\n ##description## \n\n"],

            ['id' => 1337, 'template_id' => 37, 'key' => "ru-RU", 'value' => "Обобщите этот текст для второклассника:\n\n ##description## \n\n"],

            ['id' => 1338, 'template_id' => 37, 'key' => "es-ES", 'value' => 'Generar 10 títulos de blog pegadizos para:\n\n ##description## \n\n'],

            ['id' => 1339, 'template_id' => 37, 'key' => "sv-SE", 'value' => "Sammanfatta den här texten för klass 2:\n\n ##description## \n\n"],

            ['id' => 1340, 'template_id' => 37, 'key' => "tr-TR", 'value' => "2. sınıf öğrencisi için bu metni özetleyin:\n\n ##description## \n\n"],

            ['id' => 1341, 'template_id' => 37, 'key' => "pt-BR", 'value' => "Resuma este texto para aluno da 2ª série:\n\n ##description## \n\n"],

            ['id' => 1342, 'template_id' => 37, 'key' => "ro-RO", 'value' => "Rezumați acest text pentru elevul de clasa a II-a:\n\n ##description## \n\n"],

            ['id' => 1343, 'template_id' => 37, 'key' => "vi-VN", 'value' => "Tóm tắt văn bản này cho học sinh lớp 2:\n\n ##description## \n\n"],

            ['id' => 1344, 'template_id' => 37, 'key' => "sw-KE", 'value' => "Fanya muhtasari wa maandishi haya kwa mwanafunzi wa darasa la 2:\n\n ##description## \n\n"],

            ['id' => 1345, 'template_id' => 37, 'key' => "sl-SI", 'value' => "Povzemite to besedilo za 2. razred:\n\n ##description## \n\n"],

            ['id' => 1346, 'template_id' => 37, 'key' => "th-TH", 'value' => "สรุปข้อความนี้สำหรับนักเรียนชั้นประถมศึกษาปีที่ 2:\n\n ##description## \n\n"],

            ['id' => 1347, 'template_id' => 37, 'key' => "uk-UA", 'value' => "Підсумуйте цей текст для 2-класника:\n\n ##description## \n\n"],

            ['id' => 1348, 'template_id' => 37, 'key' => "lt-LT", 'value' => "Apibendrinkite šį tekstą 2 klasės mokiniui:\n\n ##description## \n\n"],

            ['id' => 1349, 'template_id' => 37, 'key' => "bg-BG", 'value' => "Обобщете този текст за второкласник:\n\n ##description## \n\n"],

            ['id' => 1350, 'template_id' => 38, 'key' => "en-US", 'value' => "Write an interesting video script about:\n\n ##description## \n\nTone of voice of the video script must be:\n ##tone_language## \n\n"],

            ['id' => 1351, 'template_id' => 38, 'key' => "ar-AE", 'value' => "اكتب نص فيديو مثيرًا للاهتمام حول:\n\n ##description## \n\n يجب أن تكون نغمة الصوت في نص الفيديو:\n ##tone_language## \n\n"],

            ['id' => 1352, 'template_id' => 38, 'key' => "cmn-CN", 'value' => "写一个有趣的视频脚本：\n\n ##description## \n\n视频脚本的语调必须是：\n ##tone_language## \n\n"],

            ['id' => 1353, 'template_id' => 38, 'key' => "hr-HR", 'value' => "Napišite zanimljiv video scenarij o:\n\n ##description## \n\nTon glasa video skripte mora biti:\n ##tone_language## \n\n"],

            ['id' => 1354, 'template_id' => 38, 'key' => "cs-CZ", 'value' => "Napište zajímavý video skript o:\n\n ##description## \n\nTón hlasu skriptu videa musí být:\n ##tone_language## \n\n"],

            ['id' => 1355, 'template_id' => 38, 'key' => "da-DK", 'value' => "Skriv et interessant videoscript om:\n\n ##description## \n\nTone i videoscriptet skal være:\n ##tone_language## \n\n"],

            ['id' => 1356, 'template_id' => 38, 'key' => "nl-NL", 'value' => "Schrijf een interessant videoscript over:\n\n ##description## \n\nDe toon van het videoscript moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1357, 'template_id' => 38, 'key' => "et-EE", 'value' => "Kirjutage huvitav videostsenaarium teemal:\n\n ##description## \n\nVideo skripti hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1358, 'template_id' => 38, 'key' => "fi-FI", 'value' => "Kirjoita mielenkiintoinen videokäsikirjoitus aiheesta:\n\n ##description## \n\nVideokäsikirjoituksen äänensävyn on oltava:\n ##tone_language## \n\n"],

            ['id' => 1359, 'template_id' => 38, 'key' => "fr-FR", 'value' => "Écrivez un script vidéo intéressant sur :\n\n ##description## \n\nLe ton de la voix du script vidéo doit être :\n ##tone_language## \n\n"],

            ['id' => 1360, 'template_id' => 38, 'key' => "de-DE", 'value' => "Schreiben Sie ein interessantes Videoskript über:\n\n ##description## \n\nTonlage des Videoskripts muss sein:\n ##tone_language## \n\n"],

            ['id' => 1361, 'template_id' => 38, 'key' => "el-GR", 'value' => "Γράψτε ένα ενδιαφέρον σενάριο βίντεο για:\n\n ##description## \n\nΟ τόνος της φωνής του σεναρίου βίντεο πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 1362, 'template_id' => 38, 'key' => "he-IL", 'value' => "Γράψτε ένα ενδιαφέρον σενάριο βίντεο για:\n\n ##description## \n\nΟ τόνος της φωνής του σεναρίου βίντεο πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 1363, 'template_id' => 38, 'key' => "hi-IN", 'value' => "इस बारे में एक दिलचस्प वीडियो स्क्रिप्ट लिखें:\n\n ##description## \n\nवीडियो स्क्रिप्ट की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 1364, 'template_id' => 38, 'key' => "hu-HU", 'value' => "Írj egy érdekes videó forgatókönyvet erről:\n\n ##description## \n\nA videó forgatókönyvének hangszínének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1365, 'template_id' => 38, 'key' => "is-IS", 'value' => "Skrifaðu áhugavert myndbandshandrit um:\n\n ##description## \n\nRaddónn myndbandshandritsins verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1366, 'template_id' => 38, 'key' => "id-ID", 'value' => "Tulis skrip video yang menarik tentang:\n\n ##description## \n\nNada suara skrip video harus:\n ##tone_language## \n\n"],

            ['id' => 1367, 'template_id' => 38, 'key' => "it-IT", 'value' => "Scrivi un copione video interessante su:\n\n ##description## \n\nIl tono di voce del copione video deve essere:\n ##tone_language## \n\n"],

            ['id' => 1368, 'template_id' => 38, 'key' => "ja-JP", 'value' => "興味深いビデオ スクリプトを作成してください:\n\n ##description## \n\nビデオ スクリプトの声の調子:\n ##tone_language## \n\n"],

            ['id' => 1369, 'template_id' => 38, 'key' => "ko-KR", 'value' => "다음에 대한 흥미로운 비디오 스크립트 작성:\n\n ##description## \n\n비디오 스크립트의 음성 톤은 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 1370, 'template_id' => 38, 'key' => "ms-MY", 'value' => "Tulis skrip video yang menarik tentang:\n\n ##description## \n\nNada suara skrip video mestilah:\n ##tone_language## \n\n"],

            ['id' => 1371, 'template_id' => 38, 'key' => "nb-NO", 'value' => "Skriv et interessant videoskript om:\n\n ##description## \n\nStemmetonen til videoskriptet må være:\n ##tone_language## \n\n"],

            ['id' => 1372, 'template_id' => 38, 'key' => "pl-PL", 'value' => "Napisz ciekawy scenariusz wideo na temat:\n\n ##description## \n\nTon głosu skryptu wideo musi być:\n ##tone_language## \n\n"],

            ['id' => 1373, 'template_id' => 38, 'key' => "pt-PT", 'value' => "Escreva um script de vídeo interessante sobre:\n\n ##description## \n\nTom de voz do roteiro do vídeo deve ser:\n ##tone_language## \n\n"],

            ['id' => 1374, 'template_id' => 38, 'key' => "ru-RU", 'value' => "Напишите интересный сценарий видео о:\n\n ##description## \n\nТон голоса видеосценария должен быть:\n ##tone_language## \n\n"],

            ['id' => 1375, 'template_id' => 38, 'key' => "es-ES", 'value' => "Escribe un guión de video interesante sobre:\n\n ##description## \n\nEl tono de voz del guión del video debe ser:\n ##tone_language## \n\n"],

            ['id' => 1376, 'template_id' => 38, 'key' => "sv-SE", 'value' => "Skriv ett intressant videomanus om:\n\n ##description## \n\nRösten i videoskriptet måste vara:\n ##tone_language## \n\n"],

            ['id' => 1377, 'template_id' => 38, 'key' => "tr-TR", 'value' => "Şununla ilgili ilginç bir video komut dosyası yazın:\n\n ##description## \n\nVideo komut dosyasının ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 1378, 'template_id' => 38, 'key' => "pt-BR", 'value' => "Escreva um script de vídeo interessante sobre:\n\n ##description## \n\nTom de voz do roteiro do vídeo deve ser:\n ##tone_language## \n\n"],

            ['id' => 1379, 'template_id' => 38, 'key' => "ro-RO", 'value' => "Scrieți un script video interesant despre:\n\n ##description## \n\nTonul vocii al scriptului video trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1380, 'template_id' => 38, 'key' => "vi-VN", 'value' => "Viết kịch bản video thú vị về:\n\n ##description## \n\nGiọng nói của kịch bản video phải là:\n ##tone_language## \n\n"],

            ['id' => 1381, 'template_id' => 38, 'key' => "sw-KE", 'value' => "Andika hati ya video ya kuvutia kuhusu:\n\n ##description## \n\nToni ya sauti ya hati ya video lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1382, 'template_id' => 38, 'key' => "sl-SI", 'value' => "Napišite zanimiv video scenarij o:\n\n ##description## \n\nTon glasu video scenarija mora biti:\n ##tone_language## \n\n"],

            ['id' => 1383, 'template_id' => 38, 'key' => "th-TH", 'value' => "เขียนสคริปต์วิดีโอที่น่าสนใจเกี่ยวกับ:\n\n ##description## \n\nน้ำเสียงของสคริปต์วิดีโอต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1384, 'template_id' => 38, 'key' => "uk-UA", 'value' => "Напишіть сценарій цікавого відео про:\n\n ##description## \n\nТон голосу сценарію відео має бути:\n ##tone_language## \n\n"],

            ['id' => 1385, 'template_id' => 38, 'key' => "lt-LT", 'value' => "Parašykite įdomų vaizdo įrašo scenarijų apie:\n\n ##description## \n\nVaizdo įrašo scenarijaus balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 1386, 'template_id' => 38, 'key' => "bg-BG", 'value' => "Напишете интересен видео сценарий за:\n\n ##description## \n\nТонът на гласа на видео сценария трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1387, 'template_id' => 39, 'key' => "en-US", 'value' => "Write attention grabbing Amazon marketplace product description for:\n\n ##title## \n\nUse following keywords in the product description:\n ##keywords## \n\n"],

            ['id' => 1388, 'template_id' => 39, 'key' => "ar-AE", 'value' => "اكتب وصف منتج سوق أمازون الذي يجذب الانتباه لـ:\n\n ##title## \n\n استخدم الكلمات الأساسية التالية في وصف المنتج:\n ##keywords## \n\n"],

            ['id' => 1389, 'template_id' => 39, 'key' => "cmn-CN", 'value' => "为以下内容撰写引人注目的亚马逊市场产品说明：\n\n ##title## \n\n在产品描述中使用以下关键词：\n ##keywords## \n\n"],

            ['id' => 1390, 'template_id' => 39, 'key' => "hr-HR", 'value' => "Napišite opis proizvoda na Amazonovom tržištu koji privlači pažnju za:\n\n ##title## \n\nKoristite sljedeće ključne riječi u opisu proizvoda:\n ##keywords## \n\n"],

            ['id' => 1391, 'template_id' => 39, 'key' => "cs-CZ", 'value' => "Napište popis produktu na tržišti Amazon pro:\n\n ##title## \n\nV popisu produktu použijte následující klíčová slova:\n ##keywords## \n\n"],

            ['id' => 1392, 'template_id' => 39, 'key' => "da-DK", 'value' => "Skriv opmærksomhedsfangende Amazon-markedsplads-produktbeskrivelse for:\n\n ##title## \n\nBrug følgende søgeord i produktbeskrivelsen:\n ##keywords## \n\n"],

            ['id' => 1393, 'template_id' => 39, 'key' => "nl-NL", 'value' => "Schrijf een opvallende productbeschrijving op de Amazon-marktplaats voor:\n\n ##title## \n\nGebruik de volgende trefwoorden in de productbeschrijving:\n ##keywords## \n\n"],

            ['id' => 1394, 'template_id' => 39, 'key' => "et-EE", 'value' => "Kirjutage tähelepanu haarav Amazoni turu tootekirjeldus:\n\n ##title## \n\nKasutage tootekirjelduses järgmisi märksõnu:\n ##keywords## \n\n"],

            ['id' => 1395, 'template_id' => 39, 'key' => "fi-FI", 'value' => "Kirjoita huomiota herättävä Amazon Marketplace -tuotteen kuvaus:\n\n ##title## \n\nKäytä seuraavia avainsanoja tuotekuvauksessa:\n ##keywords## \n\n"],

            ['id' => 1396, 'template_id' => 39, 'key' => "fr-FR", 'value' => "Rédigez une description de produit accrocheuse sur la place de marché Amazon pour :\n\n ##title## \n\nUtilisez les mots clés suivants dans la description du produit :\n ##keywords## \n\n"],

            ['id' => 1397, 'template_id' => 39, 'key' => "de-DE", 'value' => "Schreiben Sie eine aufmerksamkeitsstarke Amazon Marketplace-Produktbeschreibung für:\n\n ##title## \n\nVerwenden Sie folgende Schlüsselwörter in der Produktbeschreibung:\n ##keywords## \n\n"],

            ['id' => 1398, 'template_id' => 39, 'key' => "el-GR", 'value' => "Γράψτε την προσοχή που προσελκύει την περιγραφή προϊόντος της Amazon Marketplace για:\n\n ##title## \n\nΧρησιμοποιήστε τις ακόλουθες λέξεις-κλειδιά στην περιγραφή του προϊόντος:\n ##keywords## \n\n"],

            ['id' => 1399, 'template_id' => 39, 'key' => "he-IL", 'value' => "כתוב תשומת לב מושכת את תיאור המוצר של Amazon Marketplace עבור:\n\n ##title## \n\nהשתמש במילות המפתח הבאות בתיאור המוצר:\n ##keywords## \n\n"],

            ['id' => 1400, 'template_id' => 39, 'key' => "hi-IN", 'value' => "ध्यान आकर्षित करने वाले Amazon मार्केटप्लेस उत्पाद विवरण के लिए लिखें:\n\n ##title## \n\nउत्पाद विवरण में निम्नलिखित कीवर्ड का उपयोग करें:\n ##keywords## \n\n"],

            ['id' => 1401, 'template_id' => 39, 'key' => "hu-HU", 'value' => "Írjon figyelemfelkeltő Amazon Marketplace termékleírást:\n\n ##title## \n\nHasználja a következő kulcsszavakat a termékleírásban:\n ##keywords## \n\n"],

            ['id' => 1402, 'template_id' => 39, 'key' => "is-IS", 'value' => "Skrifaðu athyglisverða vörulýsingu á Amazon markaðstorginu fyrir:\n\n ##title## \n\nNotaðu eftirfarandi leitarorð í vörulýsingunni:\n ##keywords## \n\n"],

            ['id' => 1403, 'template_id' => 39, 'key' => "id-ID", 'value' => "Tulis deskripsi produk pasar Amazon yang menarik perhatian untuk:\n\n ##title## \n\nGunakan kata kunci berikut dalam deskripsi produk:\n ##keywords## \n\n"],

            ['id' => 1404, 'template_id' => 39, 'key' => "it-IT", 'value' => "Scrivi una descrizione del prodotto del marketplace Amazon che attiri l'attenzione per:\n\n ##title## \n\nUsa le seguenti parole chiave nella descrizione del prodotto:\n ##keywords## \n\n"],

            ['id' => 1405, 'template_id' => 39, 'key' => "ja-JP", 'value' => "注目を集める Amazon マーケットプレイスの商品説明を書いてください:\n\n ##title## \n\n製品説明には次のキーワードを使用してください:\n ##keywords## \n\n"],

            ['id' => 1406, 'template_id' => 39, 'key' => "ko-KR", 'value' => "다음에 대한 관심을 끄는 Amazon 마켓플레이스 제품 설명 작성:\n\n ##title## \n\n제품 설명에 다음 키워드를 사용하십시오:\n ##keywords## \n\n"],

            ['id' => 1407, 'template_id' => 39, 'key' => "ms-MY", 'value' => "Tulis penerangan produk pasaran Amazon yang menarik perhatian untuk:\n\n ##title## \n\nGunakan kata kunci berikut dalam penerangan produk:\n ##keywords## \n\n"],

            ['id' => 1408, 'template_id' => 39, 'key' => "nb-NO", 'value' => "Skriv en oppmerksomhetsfangende produktbeskrivelse for Amazon Marketplace for:\n\n ##title## \n\nBruk følgende nøkkelord i produktbeskrivelsen:\n ##keywords## \n\n"],

            ['id' => 1409, 'template_id' => 39, 'key' => "pl-PL", 'value' => "Napisz przyciągający uwagę opis produktu na rynku Amazon dla:\n\n ##title## \n\nUżyj następujących słów kluczowych w opisie produktu:\n ##keywords## \n\n"],

            ['id' => 1410, 'template_id' => 39, 'key' => "pt-PT", 'value' => "Escreva uma descrição atraente do produto Amazon marketplace para:\n\n ##title## \n\nUse as seguintes palavras-chave na descrição do produto:\n ##keywords## \n\n"],

            ['id' => 1411, 'template_id' => 39, 'key' => "ru-RU", 'value' => "Напишите привлекающее внимание описание продукта на торговой площадке Amazon для:\n\n ##title## \n\nИспользуйте следующие ключевые слова в описании продукта:\n ##keywords## \n\n"],

            ['id' => 1412, 'template_id' => 39, 'key' => "es-ES", 'value' => "Escriba la descripción del producto del mercado de Amazon que llame la atención para:\n\n ##title## \n\nUtilice las siguientes palabras clave en la descripción del producto:\n ##keywords## \n\n"],

            ['id' => 1413, 'template_id' => 39, 'key' => "sv-SE", 'value' => "Skriv uppmärksamhet fånga Amazon marknadsplats produktbeskrivning för:\n\n ##title## \n\nAnvänd följande nyckelord i produktbeskrivningen:\n ##keywords## \n\n"],

            ['id' => 1414, 'template_id' => 39, 'key' => "tr-TR", 'value' => "Şunun için dikkat çekici Amazon pazar yeri ürün açıklamasını yazın:\n\n ##title## \n\nÜrün açıklamasında aşağıdaki anahtar kelimeleri kullanın:\n ##keywords## \n\n"],

            ['id' => 1415, 'template_id' => 39, 'key' => "pt-BR", 'value' => "Escreva uma descrição atraente do produto Amazon marketplace para:\n\n ##title## \n\nUse as seguintes palavras-chave na descrição do produto:\n ##keywords## \n\n"],

            ['id' => 1416, 'template_id' => 39, 'key' => "ro-RO", 'value' => "Scrieți atenția captând descrierea produsului Amazon marketplace pentru:\n\n ##title## \n\nFolosiți următoarele cuvinte cheie în descrierea produsului:\n ##keywords## \n\n"],

            ['id' => 1417, 'template_id' => 39, 'key' => "vi-VN", 'value' => "Viết mô tả sản phẩm thu hút sự chú ý trên thị trường Amazon cho:\n\n ##title## \n\nSử dụng các từ khóa sau trong phần mô tả sản phẩm:\n ##keywords## \n\n"],

            ['id' => 1418, 'template_id' => 39, 'key' => "sw-KE", 'value' => "Andika umakini wa kunyakua maelezo ya bidhaa ya soko la Amazon kwa:\n\n ##title## \n\nTumia manenomsingi yafuatayo katika maelezo ya bidhaa:\n ##keywords## \n\n"],

            ['id' => 1419, 'template_id' => 39, 'key' => "sl-SI", 'value' => "Napišite opis izdelka Amazon Marketplace, ki pritegne pozornost za:\n\n ##title## \n\nV opisu izdelka uporabite naslednje ključne besede:\n ##keywords## \n\n"],

            ['id' => 1420, 'template_id' => 39, 'key' => "th-TH", 'value' => "เขียนคำอธิบายผลิตภัณฑ์ในตลาดกลางของ Amazon ที่ดึงดูดใจสำหรับ:\n\n ##title## \n\nใช้คำหลักต่อไปนี้ในรายละเอียดสินค้า:\n ##keywords## \n\n"],

            ['id' => 1421, 'template_id' => 39, 'key' => "uk-UA", 'value' => "Напишіть опис продукту Amazon Marketplace для:\n\n ##title## \n\nВикористовуйте такі ключові слова в описі продукту:\n ##keywords## \n\n"],

            ['id' => 1422, 'template_id' => 39, 'key' => "lt-LT", 'value' => "Parašykite dėmesį patraukiančio Amazon prekyvietės produkto aprašymą:\n\n ##title## \n\nProdukto aprašyme naudokite šiuos raktinius žodžius:\n ##keywords## \n\n"],

            ['id' => 1423, 'template_id' => 39, 'key' => "bg-BG", 'value' => "Напишете грабващо вниманието описание на продукта на пазара на Amazon за:\n\n ##title## \n\nИзползвайте следните ключови думи в описанието на продукта:\n ##keywords## \n\n"],   

            ['id' => 1424, 'template_id' => 40, 'key' => "en-US", 'value' => 'please Improve and rewrite this artical in a creative and smart way:\n\n ##description## \n\n using this keywords ##keywords## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 1425, 'template_id' => 40, 'key' => "ar-AE", 'value' => 'برجاء تحسين واعادة كتابة هذا الشكل من الورق بطريقة مبتكرة وذكية :\n\n ##description## \n\n استخدام هذه الكلمات المرشدة ##keywords## \n\n يجب أن يكون نبرة صوت النتيجة كما يلي :\n ##tone_language## \n\n'],

            ['id' => 1426, 'template_id' => 40, 'key' => "cmn-CN", 'value' => '请以创造性和聪明的方式改进和撰写这篇文章：\n\n ##description## \n\n 使用这个关键字 ##keywords## \n\n 结果的语气必须是：\n ##tone_language## \n\n'],

            ['id' => 1427, 'template_id' => 40, 'key' => "hr-HR", 'value' => 'molimo poboljšajte i napišite ovaj članak na kreativan i pametan način:\n\n ##description## \n\n koristeći ove ključne riječi ##keywords## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 1428, 'template_id' => 40, 'key' => "cs-CZ", 'value' => 'prosím vylepšete a napište tento článek kreativním a chytrým způsobem:\n\n ##description## \n\n pomocí těchto klíčových slov ##keywords## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 1429, 'template_id' => 40, 'key' => "da-DK", 'value' => 'venligst forbedre og skrive denne artikel på en kreativ og smart måde:\n\n ##description## \n\n ved at bruge disse søgeord ##keywords## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 1430, 'template_id' => 40, 'key' => "nl-NL", 'value' => 'verbeter en schrijf dit artikel op een creatieve en slimme manier:\n\n ##description## \n\n met behulp van deze trefwoorden ##keywords## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 1431, 'template_id' => 40, 'key' => "et-EE", 'value' => 'Täiustage ja kirjutage see artikkel loominguliselt ja nutikalt:\n\n ##description## \n\n kasutades neid märksõnu ##keywords## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 1432, 'template_id' => 40, 'key' => "fi-FI", 'value' => 'Paranna ja kirjoita tämä artikkeli luovalla ja älykkäällä tavalla:\n\n ##description## \n\n käyttämällä näitä avainsanoja ##keywords## \n\n Tuloksen äänensävyn tulee olla:\n ##tone_language## \n\n'],

            ['id' => 1433, 'template_id' => 40, 'key' => "fr-FR", 'value' => 'Veuillez améliorer et écrire cet article de manière créative et intelligente :\n\n ##description## \n\n en utilisant ces mots clés ##keywords## \n\n Le ton de voix du résultat doit être :\n ##tone_language## \n\n'],

            ['id' => 1434, 'template_id' => 40, 'key' => "de-DE", 'value' => 'Bitte verbessern Sie diesen Artikel und schreiben Sie ihn auf kreative und intelligente Weise:\n\n ##description## \n\n mit diesen Schlüsselwörtern ##keywords## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n'],

            ['id' => 1435, 'template_id' => 40, 'key' => "el-GR", 'value' => 'Βελτιώστε και γράψτε αυτό το άρθρο με δημιουργικό και έξυπνο τρόπο:\n\n ##description## \n\n χρησιμοποιώντας αυτές τις λέξεις-κλειδιά ##keywords## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 1436, 'template_id' => 40, 'key' => "he-IL", 'value' => 'אנא שפר וכתוב מאמר זה בצורה יצירתית וחכמה:\n\n ##description## \n\n באמצעות מילות מפתח אלו ##keywords## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 1437, 'template_id' => 40, 'key' => "hi-IN", 'value' => 'कृपया इस लेख को रचनात्मक और स्मार्ट तरीके से सुधारें और लिखें:\n\n ##description## \n\n इस खोजशब्दों का उपयोग करना ##keywords## \n\n परिणाम की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n'],

            ['id' => 1438, 'template_id' => 40, 'key' => "hu-HU", 'value' => 'Kérjük, javítsa és írja meg ezt a cikket kreatív és okos módon:\n\n ##description## \n\n ezen kulcsszavak használatával ##keywords## \n\n Az eredmény hangnemének a következőnek kell lennie:\n ##tone_language## \n\n'],

            ['id' => 1439, 'template_id' => 40, 'key' => "is-IS", 'value' => 'vinsamlegast bættu og skrifaðu þessa grein á skapandi og snjallan hátt:\n\n ##description## \n\n nota þessi leitarorð ##keywords## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 1440, 'template_id' => 40, 'key' => "id-ID", 'value' => 'tolong Tingkatkan dan tulis artikel ini dengan cara yang kreatif dan cerdas:\n\n ##description## \n\n menggunakan kata kunci ini ##keywords## \n\n Nada suara hasil harus:\n ##tone_language## \n\n'],

            ['id' => 1441, 'template_id' => 40, 'key' => "it-IT", 'value' => 'per favore Migliora e scrivi questo articolo in modo creativo e intelligente:\n\n ##description## \n\n utilizzando queste parole chiave ##keywords## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n'],

            ['id' => 1442, 'template_id' => 40, 'key' => "ja-JP", 'value' => 'この記事を改善し、創造的かつ賢明な方法で書いてください:\n\n ##description## \n\n このキーワードを使って ##keywords## \n\n 結果の口調は次のようになります:\n ##tone_language## \n\n'],

            ['id' => 1443, 'template_id' => 40, 'key' => "ko-KR", 'value' => '창의적이고 현명한 방법으로 이 문서를 개선하고 작성하십시오:\n\n ##description## \n\n 이 키워드를 사용하여 ##keywords## \n\n 결과의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n'],

            ['id' => 1444, 'template_id' => 40, 'key' => "ms-MY", 'value' => 'sila Perbaiki dan tulis artikel ini dengan cara yang kreatif dan bijak:\n\n ##description## \n\n menggunakan kata kunci ini ##keywords## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n'],

            ['id' => 1445, 'template_id' => 40, 'key' => "nb-NO", 'value' => 'Vennligst forbedre og skriv denne artikkelen på en kreativ og smart måte:\n\n ##description## \n\n ved å bruke disse søkeordene ##keywords## \n\n Stemmetonen for resultatet må være:\n ##tone_language## \n\n'],

            ['id' => 1446, 'template_id' => 40, 'key' => "pl-PL", 'value' => 'proszę Popraw i napisz ten artykuł w kreatywny i inteligentny sposób:\n\n ##description## \n\n używając tych słów kluczowych ##keywords## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n'],

            ['id' => 1447, 'template_id' => 40, 'key' => "pt-PT", 'value' => 'Por favor, melhore e escreva este artigo de forma criativa e inteligente:\n\n ##description## \n\n usando essas palavras-chave ##keywords## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 1448, 'template_id' => 40, 'key' => "ru-RU", 'value' => 'пожалуйста, улучшите и напишите эту статью творчески и умно:\n\n ##description## \n\n используя эти ключевые слова ##keywords## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n'],

            ['id' => 1449, 'template_id' => 40, 'key' => "es-ES", 'value' => 'por favor Mejorar y reescribir esta táctica de una manera creativa e inteligente:\n\n ##description## \n\n utilizando estas palabras clave ##keywords## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n'],

            ['id' => 1450, 'template_id' => 40, 'key' => "sv-SE", 'value' => 'snälla förbättra och skriv den här artikeln på ett kreativt och smart sätt:\n\n ##description## \n\n använder dessa nyckelord ##keywords## \n\n Tonen i resultatet måste vara:\n ##tone_language## \n\n'],

            ['id' => 1451, 'template_id' => 40, 'key' => "tr-TR", 'value' => 'lütfen bu makaleyi geliştirin ve yaratıcı ve akıllı bir şekilde yazın:\n\n ##description## \n\n bu anahtar kelimeleri kullanarak ##keywords## \n\n Sonucun ses tonu şöyle olmalıdır:\n ##tone_language## \n\n'],

            ['id' => 1452, 'template_id' => 40, 'key' => "pt-BR", 'value' => 'Por favor, melhore e reescreva esse artigo de forma criativa e inteligente:\n\n ##description## \n\n usando essas palavras-chave ##keywords## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 1453, 'template_id' => 40, 'key' => "ro-RO", 'value' => 'Vă rugăm să îmbunătățiți și să scrieți acest articol într-un mod creativ și inteligent:\n\n ##description## \n\n folosind aceste cuvinte cheie ##keywords## \n\n Tonul de voce al rezultatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 1454, 'template_id' => 40, 'key' => "vi-VN", 'value' => 'vui lòng Cải thiện và viết bài viết này một cách sáng tạo và thông minh:\n\n ##description## \n\n Sử dụng từ khóa này ##keywords## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n'],

            ['id' => 1455, 'template_id' => 40, 'key' => "sw-KE", 'value' => 'tafadhali Boresha na uandike nakala hii kwa njia ya ubunifu na busara:\n\n ##description## \n\n kwa kutumia maneno haya ##keywords## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 1456, 'template_id' => 40, 'key' => "sl-SI", 'value' => 'prosimo, izboljšajte in napišite ta članek na kreativen in pameten način:\n\n ##description## \n\n z uporabo teh ključnih besed ##keywords## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n'],

            ['id' => 1457, 'template_id' => 40, 'key' => "th-TH", 'value' => 'โปรดปรับปรุงและเขียนบทความนี้ใหม่ด้วยวิธีที่สร้างสรรค์และชาญฉลาด:\n\n ##description## \n\n โดยใช้คีย์เวิร์ดนี้ ##keywords## \n\n โทนเสียงของผลลัพธ์จะต้อง:\n ##tone_language## \n\n'],

            ['id' => 1458, 'template_id' => 40, 'key' => "uk-UA", 'value' => 'Будь ласка, вдосконаліть і перепишіть цю статтю креативно та розумно:\n\n ##description## \n\n використовуючи ці ключові слова ##keywords## \n\n Тон голосу результату повинен бути таким:\n ##tone_language## \n\n'],

            ['id' => 1459, 'template_id' => 40, 'key' => "lt-LT", 'value' => 'Patobulinkite ir perrašykite šį straipsnį kūrybiškai ir sumaniai:\n\n ##description## \n\n naudojant šiuos raktinius žodžius ##keywords## \n\n Rezultato balso tonas turi būti:\n ##tone_language## \n\n'],

            ['id' => 1460, 'template_id' => 40, 'key' => "bg-BG", 'value' => 'моля, подобрете и пренапишете тази статия по креативен и интелигентен начин:\n\n ##description## \n\n използвайки тези ключови думи ##keywords## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 1461, 'template_id' => 41, 'key' => "en-US", 'value' => "Rephrase this content:\n\n ##description## in a different voice and style to appeal to different readers \n\n using this keywords ##keywords## \n\n \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 1462, 'template_id' => 41, 'key' => "ar-AE", 'value' => "اعادة مسح هذه المحتويات:\n\n ##description## بصوت وأسلوب مختلف للاستئناف للقراء المختلفين \n\n استخدام هذه الكلمات المرشدة ##keywords## \n\n \n\n Tالوحيدة من صوت النتيجة يجب أن تكون:\n ##tone_language## \n\n"],

            ['id' => 1463, 'template_id' => 41, 'key' => "cmn-CN", 'value' => "重新詞組此內容:\n\n ##description## 用不同的聲音和風格來吸引不同的讀者 \n\n 使用此關鍵字 ##keywords## \n\n \n\n 必須要有聲音的聲音:\n ##tone_language## \n\n"],

            ['id' => 1464, 'template_id' => 41, 'key' => "hr-HR", 'value' => "Preformulirati ovaj sadržaj:\n\n ##description## u drukčjoj glasu i stilu da se priziva na različite čitatelje. \n\n koristeći ove ključne riječi ##keywords## \n\n \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n"],

            ['id' => 1465, 'template_id' => 41, 'key' => "cs-CZ", 'value' => "Přesousloví tento obsah:\n\n ##description## v jiném hlasu a stylu pro odvolání k různým čtenářům \n\n použití těchto klíčových slov ##keywords## \n\n \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 1466, 'template_id' => 41, 'key' => "da-DK", 'value' => "Omsætning af dette indhold:\n\n ##description## i en anden stemme og typografi for at appellere til forskellige læsere \n\n bruge disse nøgleord ##keywords## \n\n \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 1467, 'template_id' => 41, 'key' => "nl-NL", 'value' => "Deze content herformuleren:\n\n ##description## in een andere stem en stijl om beroep te doen op verschillende lezers \n\n met deze trefwoorden ##keywords## \n\n \n\n Toon van de stem van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1468, 'template_id' => 41, 'key' => "et-EE", 'value' => "Väljenda seda sisu uuesti:\n\n ##description## teise hääle ja stiili, et pöörduda erinevate lugejate \n\n Kasuta sõnu ##keywords## \n\n \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1469, 'template_id' => 41, 'key' => "fi-FI", 'value' => "Korjaa tämä sisältö:\n\n ##description## Eri ääni ja tyyli valittaa eri lukijoille \n\n Käyttämällä tätä avainsanaa ##keywords## \n\n \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n"],

            ['id' => 1470, 'template_id' => 41, 'key' => "fr-FR", 'value' => "Rephrase ce contenu:\n\n ##description## D'une voix et d'un style différents pour faire appel à différents lecteurs \n\n Utilisation de ces mots clés ##keywords## \n\n \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 1471, 'template_id' => 41, 'key' => "de-DE", 'value' => "Umschreibung dieses Inhalts:\n\n ##description## in einer anderen Stimme und einem anderen Stil, um an verschiedene Leser zu appellieren \n\n Verwendung dieser Schlüsselwörter ##keywords## \n\n \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n"],

            ['id' => 1472, 'template_id' => 41, 'key' => "el-GR", 'value' => "Αναδιατύπωση αυτού του περιεχομένου.:\n\n ##description## με διαφορετική φωνή και στυλ να απευθυνόταν σε διαφορετικούς αναγνώστες \n\n χρησιμοποιώντας αυτές τις λέξεις-κλειδιά ##keywords## \n\n \n\n Η φωνή του αποτελέσματος πρέπει να είναι ...:\n ##tone_language## \n\n"],

            ['id' => 1473, 'template_id' => 41, 'key' => "he-IL", 'value' => "משפט חוזר של תוכן זה:\n\n ##description## בקול וסגנון שונים כדי לפנות לקוראים שונים. \n\n שימוש במילות מפתח אלה ##keywords## \n\n \n\n טון הדיבור של הפסקאות חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 1474, 'template_id' => 41, 'key' => "hi-IN", 'value' => "इस सामग्री को पुनः वाक्यांश करें:\n\n ##description## विभिन्न पाठकों से अपील करने के लिए एक अलग आवाज और शैली में \n\n इस कीवर्ड का उपयोग कर ##keywords## \n\n \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n"],

            ['id' => 1475, 'template_id' => 41, 'key' => "hu-HU", 'value' => "A tartalom javítása:\n\n ##description## Eltérő hangon és stílusban a különböző olvasók számára \n\n E kulcsszavak használata ##keywords## \n\n \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1476, 'template_id' => 41, 'key' => "is-IS", 'value' => "Umorðaðu þetta efni:\n\n ##description## í annarri rödd og stíl til að höfða til ólíkra lesenda \n\n nota þessi leitarorð ##keywords## \n\n \n\n Rödd í niðurstöðunni verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1477, 'template_id' => 41, 'key' => "id-ID", 'value' => "Ulangi konten ini:\n\n ##description## dengan suara dan gaya yang berbeda untuk menarik pembaca yang berbeda \n\n menggunakan kata kunci ini ##keywords## \n\n \n\n Nada suara hasilnya harus:\n ##tone_language## \n\n"],

            ['id' => 1478, 'template_id' => 41, 'key' => "it-IT", 'value' => "Riformula questo contenuto:\n\n ##description## con una voce e uno stile diversi per attrarre lettori diversi \n\n utilizzando queste parole chiave ##keywords## \n\n \n\n Tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 1479, 'template_id' => 41, 'key' => "ja-JP", 'value' => "この内容を言い換える:\n\n ##description## さまざまな読者にアピールするために、さまざまな声とスタイルで \n\n このキーワードを使って ##keywords## \n\n \n\n 結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n"],

            ['id' => 1480, 'template_id' => 41, 'key' => "ko-KR", 'value' => "이 콘텐츠를 다른 말로 표현:\n\n ##description## 다른 독자들에게 어필하기 위해 다른 목소리와 스타일로 \n\n 이 키워드를 사용하여 ##keywords## \n\n \n\n 결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 1481, 'template_id' => 41, 'key' => "ms-MY", 'value' => "Ungkapkan semula kandungan ini:\n\n ##description## dengan suara dan gaya yang berbeza untuk menarik minat pembaca yang berbeza \n\n menggunakan kata kunci ini ##keywords## \n\n \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 1482, 'template_id' => 41, 'key' => "nb-NO", 'value' => "Omformuler dette innholdet:\n\n ##description## i en annen stemme og stil for å appellere til forskjellige lesere \n\n ved å bruke disse søkeordene ##keywords## \n\n \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 1483, 'template_id' => 41, 'key' => "pl-PL", 'value' => "Przeformułuj tę treść:\n\n ##description## innym głosem i stylem, aby przemawiać do różnych czytelników \n\n używając tych słów kluczowych ##keywords## \n\n \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 1484, 'template_id' => 41, 'key' => "pt-PT", 'value' => "Reformule este conteúdo:\n\n ##description## em uma voz e estilo diferentes para atrair diferentes leitores \n\n usando essas palavras-chave ##keywords## \n\n \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1485, 'template_id' => 41, 'key' => "ru-RU", 'value' => "Перефразируйте этот контент:\n\n ##description## в другом голосе и стиле, чтобы обратиться к разным читателям \n\n используя эти ключевые слова ##keywords## \n\n \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 1486, 'template_id' => 41, 'key' => "es-ES", 'value' => "Reformular este contenido:\n\n ##description## en una voz y estilo diferente para atraer a diferentes lectores \n\n usando estas palabras clave ##keywords## \n\n \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 1487, 'template_id' => 41, 'key' => "sv-SE", 'value' => "Omformulera detta innehåll:\n\n ##description## med en annan röst och stil för att tilltala olika läsare \n\n använder dessa nyckelord ##keywords## \n\n \n\n Tonen i rösten för resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 1488, 'template_id' => 41, 'key' => "tr-TR", 'value' => "Bu içeriği yeniden ifade et:\n\n ##description## farklı okuyuculara hitap etmek için farklı bir ses ve tarzda \n\n bu anahtar kelimeleri kullanarak ##keywords## \n\n \n\n Sonucun ses tonu şöyle olmalıdır::\n ##tone_language## \n\n"],

            ['id' => 1489, 'template_id' => 41, 'key' => "pt-BR", 'value' => "Reformule este conteúdo:\n\n ##description## em uma voz e estilo diferentes para atrair diferentes leitores \n\n usando essas palavras-chave ##keywords## \n\n \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1490, 'template_id' => 41, 'key' => "ro-RO", 'value' => "Reformulați acest conținut:\n\n ##description## într-o voce și stil diferit pentru a atrage diferiți cititori \n\n folosind aceste cuvinte cheie ##keywords## \n\n \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1491, 'template_id' => 41, 'key' => "vi-VN", 'value' => "Viết lại nội dung này:\n\n ##description## bằng một giọng điệu và phong cách khác để thu hút những độc giả khác nhau \n\n sử dụng từ khóa này ##keywords## \n\n \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 1492, 'template_id' => 41, 'key' => "sw-KE", 'value' => "Andika upya maudhui haya:\n\n ##description## kwa sauti na mtindo tofauti ili kuwavutia wasomaji mbalimbali \n\n kwa kutumia maneno haya ##keywords## \n\n \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1493, 'template_id' => 41, 'key' => "sl-SI", 'value' => "Preoblikujte to vsebino:\n\n ##description## z drugačnim glasom in slogom, da pritegne različne bralce \n\n z uporabo teh ključnih besed ##keywords## \n\n \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 1494, 'template_id' => 41, 'key' => "th-TH", 'value' => "ใช้ถ้อยคำเนื้อหานี้ใหม่:\n\n ##description## ด้วยเสียงและสไตล์ที่แตกต่างเพื่อดึงดูดผู้อ่านที่แตกต่างกัน \n\n โดยใช้คีย์เวิร์ดนี้ ##keywords## \n\n \n\n น้ำเสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1495, 'template_id' => 41, 'key' => "uk-UA", 'value' => "Перефразуйте цей зміст:\n\n ##description## іншим голосом і стилем, щоб звернутися до різних читачів \n\n використовуючи ці ключові слова ##keywords## \n\n \n\n Тон голосу результату повинен бути:\n ##tone_language## \n\n"],

            ['id' => 1496, 'template_id' => 41, 'key' => "lt-LT", 'value' => "Perfrazuokite šį turinį:\n\n ##description## kitu balsu ir stiliumi, kad patiktų skirtingiems skaitytojams \n\n naudojant šiuos raktinius žodžius ##keywords## \n\n \n\n Turi būti rezultato balso tonas:\n ##tone_language## \n\n"],

            ['id' => 1497, 'template_id' => 41, 'key' => "bg-BG", 'value' => "Перифразирайте това съдържание :\n\n ##description## с различен глас и стил, за да се хареса на различни читатели \n\n използвайки тези ключови думи ##keywords## \n\n \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1498, 'template_id' => 42, 'key' => "en-US", 'value' => 'Improve and extend this content:\n\n ##description## \n\n Use following keywords in the content:\n ##keywords## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 1499, 'template_id' => 42, 'key' => "ar-AE", 'value' => 'تحسين وتوسيع هذه المحتويات:\n\n ##description## \n\n استخدام الكلمات المرشدة التالية في المحتويات:\n ##keywords## \n\n Tالوحيدة من صوت النتيجة يجب أن تكون:\n ##tone_language## \n\n'],

            ['id' => 1500, 'template_id' => 42, 'key' => "cmn-CN", 'value' => '改进和扩展此内容:\n\n ##description## \n\n 在内容中使用以下关键字:\n ##keywords## \n\n T结果的声音必须是:\n ##tone_language## \n\n'],

            ['id' => 1501, 'template_id' => 42, 'key' => "hr-HR", 'value' => 'Poboljsi i produži ovaj sadržaj:\n\n ##description## \n\n Koristite sljedeće ključne riječi u sadržaju:\n ##keywords## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n'],

            ['id' => 1502, 'template_id' => 42, 'key' => "cs-CZ", 'value' => 'Vylepšit a rozšířit tento obsah:\n\n ##description## \n\n UPoužít následující klíčová slova v obsahu:\n ##keywords## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n'],

            ['id' => 1503, 'template_id' => 42, 'key' => "da-DK", 'value' => 'Forbedre og udvid dette indhold:\n\n ##description## \n\n Brug følgende nøgleord i indholdet:\n ##keywords## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n'],

            ['id' => 1504, 'template_id' => 42, 'key' => "nl-NL", 'value' => 'Deze content verbeteren en uitbreiden:\n\n ##description## \n\n Gebruik de volgende sleutelwoorden in de inhoud:\n ##keywords## \n\n Toon van de stem van het resultaat moet zijn:\n ##tone_language## \n\n'],

            ['id' => 1505, 'template_id' => 42, 'key' => "et-EE", 'value' => 'Parandada ja laiendada seda sisu:\n\n ##description## \n\n Kasuta järgmisi võtmesõnu:\n ##keywords## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 1506, 'template_id' => 42, 'key' => "fi-FI", 'value' => 'Tämän sisällön parantaminen ja laajentaminen:\n\n ##description## \n\n Käytä sisällön avainsanoja:\n ##keywords## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n'],

            ['id' => 1507, 'template_id' => 42, 'key' => "fr-FR", 'value' => 'Améliorer et étendre ce contenu:\n\n ##description## \n\n Utiliser les mots clés suivants dans le contenu:\n ##keywords## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n'],

            ['id' => 1508, 'template_id' => 42, 'key' => "de-DE", 'value' => 'Diese Inhalte verbessern und erweitern:\n\n ##description## \n\n Verwenden Sie die folgenden Schlüsselwörter im Inhalt:\n ##keywords## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n'],

            ['id' => 1509, 'template_id' => 42, 'key' => "el-GR", 'value' => 'Βελτίωση και επέκταση αυτού του περιεχομένου.:\n\n ##description## \n\n Χρήση ακόλουθων λέξεων-κλειδιών στο περιεχόμενο:\n ##keywords## \n\n Η φωνή του αποτελέσματος πρέπει να είναι ...:\n ##tone_language## \n\n'],

            ['id' => 1510, 'template_id' => 42, 'key' => "he-IL", 'value' => 'שיפור ולהרחיב תוכן זה:\n\n ##description## \n\n שימוש במילות המפתח שלהלן בתוכן:\n ##keywords## \n\n Tone של הקול של התוצאה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 1511, 'template_id' => 42, 'key' => "hi-IN", 'value' => 'इस सामग्री को सुधारता और विस्तार करें:\n\n ##description## \n\n सामग्री में निम्नलिखित कीवर्ड का प्रयोग करें:\n ##keywords## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n'],

            ['id' => 1512, 'template_id' => 42, 'key' => "hu-HU", 'value' => 'A tartalom javítása és kiterjesztése:\n\n ##description## \n\n Használja a következő kulcsszavakat a tartalomban:\n ##keywords## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n'],

            ['id' => 1513, 'template_id' => 42, 'key' => "is-IS", 'value' => 'Bættu og stækkuðu þetta efni:\n\n ##description## \n\n  Notaðu eftirfarandi leitarorð í innihaldinu:\n ##keywords## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 1514, 'template_id' => 42, 'key' => "id-ID", 'value' => 'Meningkatkan dan memperluas konten ini:\n\n ##description## \n\n Gunakan kata kunci berikut dalam isi:\n ##keywords## \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n'],

            ['id' => 1515, 'template_id' => 42, 'key' => "it-IT", 'value' => 'Migliorare ed estendere questo contenuto:\n\n ##description## \n\n Utilizzare le seguenti parole chiave nel contenuto:\n ##keywords## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n'],

            ['id' => 1516, 'template_id' => 42, 'key' => "ja-JP", 'value' => 'このコンテンツの改善と拡張:\n\n ##description## \n\n コンテンツ内の以下のキーワードを使用:\n ##keywords## \n\n 結果の声のトーンは、:\n ##tone_language## \n\n'],

            ['id' => 1517, 'template_id' => 42, 'key' => "ko-KR", 'value' => '이 컨텐츠 향상 및 확장:\n\n ##description## \n\n 컨텐츠에서 다음 키워드 사용:\n ##keywords## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다.:\n ##tone_language## \n\n'],

            ['id' => 1518, 'template_id' => 42, 'key' => "ms-MY", 'value' => 'Tingkatkan dan lanjutkan kandungan ini:\n\n ##description## \n\n Guna kata kekunci berikut dalam kandungan:\n ##keywords## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n'],

            ['id' => 1519, 'template_id' => 42, 'key' => "nb-NO", 'value' => 'Forbedre og utvide dette innholdet:\n\n ##description## \n\n Bruk følgende nøkkelord i innholdet:\n ##keywords## \n\n Tone av stemmen til resultatet må være:\n ##tone_language## \n\n'],

            ['id' => 1520, 'template_id' => 42, 'key' => "pl-PL", 'value' => 'Ulepszanie i rozszerzanie tej treści:\n\n ##description## \n\n Użyj następujących słów kluczowych w treści:\n ##keywords## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n'],

            ['id' => 1521, 'template_id' => 42, 'key' => "pt-PT", 'value' => 'Melhorar e alargar este conteúdo:\n\n ##description## \n\n Utilizar as seguintes palavras-chave no conteúdo:\n ##keywords## \n\n Tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 1522, 'template_id' => 42, 'key' => "ru-RU", 'value' => 'Улучшить и расширить это содержимое:\n\n ##description## \n\n Использовать следующие ключевые слова в содержимом:\n ##keywords## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n'],

            ['id' => 1523, 'template_id' => 42, 'key' => "es-ES", 'value' => 'Mejorar y ampliar este contenido:\n\n ##description## \n\n Utilizar las palabras clave siguientes en el contenido:\n ##keywords## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n'],

            ['id' => 1524, 'template_id' => 42, 'key' => "sv-SE", 'value' => 'Förbättra och utöka innehållet:\n\n ##description## \n\n Använd följande nyckelord i innehållet:\n ##keywords## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n'],

            ['id' => 1525, 'template_id' => 42, 'key' => "tr-TR", 'value' => 'Bu içeriği iyileştirin ve genişletin:\n\n ##description## \n\n İçerikte aşağıdaki anahtar sözcükleri kullan:\n ##keywords## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n'],

            ['id' => 1526, 'template_id' => 42, 'key' => "pt-BR", 'value' => 'Aprimore e amplie esse conteúdo:\n\n ##description## \n\n  Use as seguintes palavras-chave no conteúdo:\n ##keywords## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 1527, 'template_id' => 42, 'key' => "ro-RO", 'value' => 'Îmbunătățirea și extinderea acestui conținut:\n\n ##description## \n\n Utilizați următoarele cuvinte cheie în conținut:\n ##keywords## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 1528, 'template_id' => 42, 'key' => "vi-VN", 'value' => 'Cải thiện và mở rộng nội dung này:\n\n ##description## \n\n Sử dụng sau tư ̀ khóa trong nội dung:\n ##keywords## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n'],

            ['id' => 1529, 'template_id' => 42, 'key' => "sw-KE", 'value' => 'Boresha na upanue maudhui haya:\n\n ##description## \n\n  Tumia maneno muhimu yafuatayo katika yaliyomo:\n ##keywords## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 1530, 'template_id' => 42, 'key' => "sl-SI", 'value' => 'Izboljjte in razširite to vsebino:\n\n ##description## \n\n Uporabi naslednje ključne besede v vsebini:\n ##keywords## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n'],

            ['id' => 1531, 'template_id' => 42, 'key' => "th-TH", 'value' => 'ปรับปรุงและขยายเนื้อหานี้:\n\n ##description## \n\n ใช้คีย์เวิร์ดต่อไปนี้ในเนื้อหา:\n ##keywords## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 1532, 'template_id' => 42, 'key' => "uk-UA", 'value' => 'Покращення і розширення вмісту:\n\n ##description## \n\n  Використовувати наступні ключові слова у зміні:\n ##keywords## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n'],

            ['id' => 1533, 'template_id' => 42, 'key' => "lt-LT", 'value' => 'Pagerinti ir išplėsti šį turinį:\n\n ##description## \n\n Naudoti pagal raktažodžius turinį:\n ##keywords## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n'],

            ['id' => 1534, 'template_id' => 42, 'key' => "bg-BG", 'value' => 'Подобряване и разширяване на това съдържание:\n\n ##description## \n\n  Използване на следните ключови думи в съдържанието:\n ##keywords## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 1535, 'template_id' => 43, 'key' => "en-US", 'value' =>  "Summarize this text in a short concise way:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 1536, 'template_id' => 43, 'key' => "ar-AE", 'value' =>  "قم بتلخيص هذا النص بطريقة مختصرة قصيرة:\n\n ##description## \n\n Tالوحيدة من صوت النتيجة يجب أن تكون:\n ##tone_language## \n\n"],

            ['id' => 1537, 'template_id' => 43, 'key' => "cmn-CN", 'value' =>  "以簡短的方式彙總此文字:\n\n ##description## \n\n 必須要有聲音的聲音:\n ##tone_language## \n\n"],

            ['id' => 1538, 'template_id' => 43, 'key' => "hr-HR", 'value' =>  "Sumiraj ovaj tekst na kratki koncizni način:\n\n ##description## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n"],

            ['id' => 1539, 'template_id' => 43, 'key' => "cs-CZ", 'value' =>  "Shrňte tento text stručným výstižným způsobem:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 1540, 'template_id' => 6, 'key' => "da-DK", 'value' => "Opsummér teksten på kort koncis måde:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 1541, 'template_id' => 43, 'key' => "nl-NL", 'value' =>  "Een korte samenvatting van deze tekst samenvatten:\n\n ##description## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1542, 'template_id' => 43, 'key' => "et-EE", 'value' =>  "Selle teksti kokkuvõtlik lühikokkuvõte:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1543, 'template_id' => 43, 'key' => "fi-FI", 'value' =>  "Tiivistä tämä teksti lyhyesti lyhyesti:\n\n ##description## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n"],

            ['id' => 1544, 'template_id' => 43, 'key' => "fr-FR", 'value' =>  "Résumez ce texte de manière concise et concise:\n\n ##description## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 1545, 'template_id' => 43, 'key' => "de-DE", 'value' =>  "Fassen Sie diesen Text kurz zusammen.:\n\n ##description## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n"],

            ['id' => 1546, 'template_id' => 43, 'key' => "el-GR", 'value' =>  "Συνοπτική παρουσίαση αυτού του κειμένου με συνοπτικές συνοπτικές:\n\n ##description## \n\n Η φωνή του αποτελέσματος πρέπει να είναι ...:\n ##tone_language## \n\n"],

            ['id' => 1547, 'template_id' => 43, 'key' => "he-IL", 'value' =>  "סיכום התמליל באופן תמציתי קצר.:\n\n ##description## \n\n טון הדיבור של הפסקאות חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 1548, 'template_id' => 43, 'key' => "hi-IN", 'value' =>  "संक्षिप्त सारांश में इस पाठ को सारांशित करें:\n\n ##description## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n"],

            ['id' => 1549, 'template_id' => 43, 'key' => "hu-HU", 'value' =>  "Összegezze ezt a szöveget rövid tömör formában:\n\n ##description## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1550, 'template_id' => 43, 'key' => "is-IS", 'value' =>  "Dragðu saman þennan texta á stuttan hnitmiðaðan hátt:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1551, 'template_id' => 43, 'key' => "id-ID", 'value' =>  "Ikhtisar teks ini dengan cara ringkas singkat:\n\n ##description## \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n"],

            ['id' => 1552, 'template_id' => 43, 'key' => "it-IT", 'value' =>  "Riepiloga questo testo in modo conciso breve:\n\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 1553, 'template_id' => 43, 'key' => "ja-JP", 'value' =>  "簡潔な簡潔な方法でこのテキストを要約する:\n\n ##description## \n\n 結果の声のトーンは、:\n ##tone_language## \n\n"],

            ['id' => 1554, 'template_id' => 43, 'key' => "ko-KR", 'value' =>  "짧은 간결한 방식으로 이 텍스트 요약:\n\n ##description## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 1555, 'template_id' => 43, 'key' => "ms-MY", 'value' =>  "Panggil teks ini dalam cara concise pendek:\n\n ##description## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n"],

            ['id' => 1556, 'template_id' => 43, 'key' => "nb-NO", 'value' =>  "Oppsummer denne teksten på en kort og konsist måte:\n\n ##description## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 1557, 'template_id' => 43, 'key' => "pl-PL", 'value' =>  "Podsumuj ten tekst w krótkim zwięzonym sposobie:\n\n ##description## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 1558, 'template_id' => 43, 'key' => "pt-PT", 'value' =>  "Resumir este texto de uma forma concisa curta:\n\n ##description## \n\n Tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1559, 'template_id' => 43, 'key' => "ru-RU", 'value' =>  "Обобщить этот текст кратким кратким способом:\n\n ##description## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 1560, 'template_id' => 43, 'key' => "es-ES", 'value' =>  "Resumir este texto de una manera breve y concisa:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 1561, 'template_id' => 43, 'key' => "sv-SE", 'value' =>  "Sammanfatta den här texten på kort koncist sätt:\n\n ##description## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 1562, 'template_id' => 43, 'key' => "tr-TR", 'value' =>  "Bu metni kısa kısa bir kısa yolla özetle:\n\n ##description## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n"],

            ['id' => 1563, 'template_id' => 43, 'key' => "pt-BR", 'value' =>  "Resumir este texto de uma forma concisa curta:\n\n ##description## \n\n Tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1564, 'template_id' => 43, 'key' => "ro-RO", 'value' =>  "Rezumă acest text într-un mod concis scurt:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1565, 'template_id' => 43, 'key' => "vi-VN", 'value' =>  "Tóm tắt văn bản này một cách ngắn gọn:\n\n ##description## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 1566, 'template_id' => 43, 'key' => "sw-KE", 'value' =>  "Fupisha maandishi haya kwa njia fupi fupi:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1567, 'template_id' => 43, 'key' => "sl-SI", 'value' =>  "Seštej to besedilo na kratko kratko:\n\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 1568, 'template_id' => 43, 'key' => "th-TH", 'value' =>  "สรุปข้อความนี้ในวิธีที่สั้นกระชับ:\n\n ##description## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1569, 'template_id' => 43, 'key' => "uk-UA", 'value' =>  "Підсумовувати цей текст коротким шляхом:\n\n ##description## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n"],

            ['id' => 1570, 'template_id' => 43, 'key' => "lt-LT", 'value' =>  "Apibendrinkite šį tekstą trumpai glaustai:\n\n ##description## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n"],

            ['id' => 1571, 'template_id' => 43, 'key' => "bg-BG", 'value' =>  "Обобщи този текст по кратък кратък начин:\n\n ##description## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1572, 'template_id' => 44, 'key' => "en-US", 'value' =>  "Write a detailed answer for Quora of this question:\n\n ##title## \n\nUse this content for more information:\n ##description## \n\n Tone of voice of the answer must be:\n ##tone_language## \n\n"],

            ['id' => 1573, 'template_id' => 44, 'key' => "ar-AE", 'value' =>  "ككتابة جواب مفصل عن Quora من هذا السؤال:\n\n ##title## \n\nاستخدم هذه المحتويات لمزيد من المعلومات:\n ##description## \n\n نبرة صوت الإجابة يجب أن تكون:\n ##tone_language## \n\n"],

            ['id' => 1574, 'template_id' => 44, 'key' => "cmn-CN", 'value' =>  "為 Quora 撰寫詳細的答案:\n\n ##title## \n\n使用此內容以取得相關資訊:\n ##description## \n\n 答案的聲音一定是:\n ##tone_language## \n\n"],

            ['id' => 1575, 'template_id' => 44, 'key' => "hr-HR", 'value' =>  "Napišite detaljan odgovor za Quora od ovog pitanja:\n\n ##title## \n\nKoristite ovaj sadržaj za više informacija:\n ##description## \n\n Ton glasa od odgovora mora biti:\n ##tone_language## \n\n"],

            ['id' => 1576, 'template_id' => 44, 'key' => "cs-CZ", 'value' =>  "Napište podrobnou odpověď pro Quora této otázky:\n\n ##title## \n\nPoužijte tento obsah pro další informace:\n ##description## \n\n Tón hlasu odpovědi musí být:\n ##tone_language## \n\n"],

            ['id' => 1577, 'template_id' => 44, 'key' => "da-DK", 'value' =>  "Skriv et detaljeret svar for Quora af dette spørgsmål:\n\n ##title## \n\nBrug dette indhold til flere oplysninger:\n ##description## \n\n Tonen i svaret skal være:\n ##tone_language## \n\n"],

            ['id' => 1578, 'template_id' => 44, 'key' => "nl-NL", 'value' =>  "Schrijf een gedetailleerd antwoord voor Quora van deze vraag:\n\n ##title## \n\nDeze content gebruiken voor meer informatie:\n ##description## \n\n Toon van de stem van het antwoord moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1579, 'template_id' => 44, 'key' => "et-EE", 'value' =>  "Selle küsimuse Quora-i üksikasjaliku vastuse kirjutamine:\n\n ##title## \n\nKasutage seda infosisu rohkem:\n ##description## \n\n Vastuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1580, 'template_id' => 44, 'key' => "fi-FI", 'value' =>  "Kirjoita yksityiskohtainen vastaus Quora tämän kysymyksen:\n\n ##title## \n\nKäytä tätä sisältöä lisätietoja varten:\n ##description## \n\n Vastauksen äänen on oltava:\n ##tone_language## \n\n"],

            ['id' => 1581, 'template_id' => 44, 'key' => "fr-FR", 'value' =>  "Rédiger une réponse détaillée pour Quora de cette question:\n\n ##title## \n\nUtiliser ce contenu pour plus d'informations:\n ##description## \n\n Tone de la voix de la réponse doit être:\n ##tone_language## \n\n"],

            ['id' => 1582, 'template_id' => 44, 'key' => "de-DE", 'value' =>  "Schreiben Sie eine ausführliche Antwort für Quora von dieser Frage:\n\n ##title## \n\nVerwenden Sie diesen Inhalt für weitere Informationen.:\n ##description## \n\n Ton der Stimme der Antwort muss sein:\n ##tone_language## \n\n"],

            ['id' => 1583, 'template_id' => 44, 'key' => "el-GR", 'value' =>  "Γράψτε μια αναλυτική απάντηση για την Quora αυτής της ερώτησης:\n\n ##title## \n\nΧρήση αυτού του περιεχομένου για περισσότερες πληροφορίες:\n ##description## \n\n Ο τόνος της φωνής της απάντησης πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 1584, 'template_id' => 44, 'key' => "he-IL", 'value' =>  "כתיבת תשובה מפורטת עבור Quora של שאלה זו:\n\n ##title## \n\nשימוש בתוכן זה לקבלת מידע נוסף:\n ##description## \n\n נימת הקול של התשובה חייבת להיות:\n ##tone_language## \n\n"],

            ['id' => 1585, 'template_id' => 44, 'key' => "hi-IN", 'value' =>  "इस प्रश्न के Quora के लिए एक विस्तृत उत्तर लिखें:\n\n ##title## \n\nअधिक जानकारी के लिए इस सामग्री का उपयोग करें:\n ##description## \n\n जवाब की आवाज का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 1586, 'template_id' => 44, 'key' => "hu-HU", 'value' =>  "Részletes válasz írása Quora Ebből a kérdésről:\n\n ##title## \n\nUse this content for more information:\n ##description## \n\n Tone of voice of the answer must be:\n ##tone_language## \n\n"],

            ['id' => 1587, 'template_id' => 44, 'key' => "is-IS", 'value' =>  "Skrifaðu ítarlegt svar fyrir Quora við þessari spurningu:\n\n ##title## \n\nNotaðu þetta efni til að fá frekari upplýsingar:\n ##description## \n\n Rödd svarsins verður be:\n ##tone_language## \n\n"],

            ['id' => 1588, 'template_id' => 44, 'key' => "id-ID", 'value' =>  "Tulis jawaban rinci untuk Quora pertanyaan ini:\n\n ##title## \n\nGunakan konten ini untuk informasi lebih lanjut:\n ##description## \n\n Nada suara harus dijawab.:\n ##tone_language## \n\n"],

            ['id' => 1589, 'template_id' => 44, 'key' => "it-IT", 'value' =>  "Scrivi una risposta dettagliata per Quora di questa domanda:\n\n ##title## \n\nUsa questo contenuto per ulteriori informazioni:\n ##description## \n\n Il tono di voce della risposta deve essere:\n ##tone_language## \n\n"],

            ['id' => 1590, 'template_id' => 44, 'key' => "ja-JP", 'value' =>  "この質問の Quora の詳細な回答を書き込む:\n\n ##title## \n\n詳しくは、このコンテンツを使用してください:\n ##description## \n\n 解答の声のトーンは:\n ##tone_language## \n\n"],

            ['id' => 1591, 'template_id' => 44, 'key' => "ko-KR", 'value' =>  "이 질문의 Quora에 대한 자세한 응답 작성:\n\n ##title## \n\n자세한 정보는 이 컨텐츠를 사용하십시오.:\n ##description## \n\n 대답의 음성은 반드시 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 1592, 'template_id' => 44, 'key' => "ms-MY", 'value' =>  "Tulis jawapan terperinci untuk Quora soalan ini:\n\n ##title## \n\nGuna kandungan ini untuk maklumat lanjut:\n ##description## \n\n Nada suara jawabannya mesti:\n ##tone_language## \n\n"],

            ['id' => 1593, 'template_id' => 44, 'key' => "nb-NO", 'value' =>  "Skriv et detaljert svar for Quora på dette spørsmålet:\n\n ##title## \n\nGuna kandungan ini untuk maklumat lanjut:\n ##description## \n\n Nada suara jawabannya mesti:\n ##tone_language## \n\n"],

            ['id' => 1594, 'template_id' => 44, 'key' => "pl-PL", 'value' =>  "Napisz szczegółową odpowiedź dla Quora o to pytanie:\n\n ##title## \n\nUżyj tej treści, aby uzyskać więcej informacji:\n ##description## \n\n Ton głosu odpowiedzi musi być:\n ##tone_language## \n\n"],

            ['id' => 1595, 'template_id' => 44, 'key' => "pt-PT", 'value' =>  "Escreva uma resposta detalhada para Quora desta pergunta:\n\n ##title## \n\nUse este conteúdo para mais informações:\n ##description## \n\n Tom de voz da resposta deve ser:\n ##tone_language## \n\n"],

            ['id' => 1596, 'template_id' => 44, 'key' => "ru-RU", 'value' =>  "Написать подробный ответ для Quora данного вопроса:\n\n ##title## \n\nИспользуйте этот контент для получения дополнительной информации:\n ##description## \n\n Тон голоса должен быть:\n ##tone_language## \n\n"],

            ['id' => 1597, 'template_id' => 44, 'key' => "es-ES", 'value' =>  "Escriba una respuesta detallada para Quora de esta pregunta:\n\n ##title## \n\nUtilice este contenido para obtener más información:\n ##description## \n\n El tono de voz de la respuesta debe ser:\n ##tone_language## \n\n"],

            ['id' => 1598, 'template_id' => 44, 'key' => "sv-SE", 'value' =>  "Skriv ett detaljerat svar på Quora av denna fråga:\n\n ##title## \n\nAnvänd det här innehållet för mer information:\n ##description## \n\n Tone of voice of the svar must be:\n ##tone_language## \n\n"],

            ['id' => 1599, 'template_id' => 44, 'key' => "tr-TR", 'value' =>  "Bu sorunun Quora için ayrıntılı bir yanıt yazın:\n\n ##title## \n\nDaha fazla bilgi için bu içeriği kullanın:\n ##description## \n\n Cevabından ses tonunun sesi olmalı.:\n ##tone_language## \n\n"],

            ['id' => 1600, 'template_id' => 44, 'key' => "pt-BR", 'value' =>  "Escreva uma resposta detalhada para Quora desta pergunta:\n\n ##title## \n\nUse este conteúdo para mais informações:\n ##description## \n\n Tom de voz da resposta deve ser:\n ##tone_language## \n\n"],

            ['id' => 1601, 'template_id' => 44, 'key' => "ro-RO", 'value' =>  "Utilizați acest conținut pentru mai multe informații:\n\n ##title## \n\nUtilizați acest conținut pentru mai multe informații:\n ##description## \n\n Tonul vocii răspunsului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1602, 'template_id' => 44, 'key' => "vi-VN", 'value' =>  "Viết một câu trả lời chi tiết cho Quora của câu hỏi này:\n\n ##title## \n\nDùng nội dung này để biết thêm thông tin:\n ##description## \n\n Giọng nói của câu trả lời phải là:\n ##tone_language## \n\n"],

            ['id' => 1603, 'template_id' => 44, 'key' => "sw-KE", 'value' =>  "Andika jibu la kina kwa Quora la swali hili:\n\n ##title## \n\nUtazama yaliyomo kwa habari zaidi:\n ##description## \n\n Toni ya sauti ya jibu lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1604, 'template_id' => 44, 'key' => "sl-SI", 'value' =>  "Napišite podroben odgovor na vprašanje Quora o tem vprašanju:\n\n ##title## \n\nZa več informacij uporabite to vsebino:\n ##description## \n\n Ton glasu odgovora mora biti:\n ##tone_language## \n\n"],

            ['id' => 1605, 'template_id' => 44, 'key' => "th-TH", 'value' =>  "เขียนคำตอบโดยละเอียดสำหรับ Quora ของคำถามนี้:\n\n ##title## \n\nใช้เนื้อหานี้เพื่อดูข้อมูลเพิ่มเติม:\และ \n ##description## \n\n น้ำเสียงของคำตอบต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1606, 'template_id' => 44, 'key' => "uk-UA", 'value' =>  "Напишіть розгорнуту відповідь для Quora на це питання:\n\n ##title## \n\nВикористовуйте цей вміст для отримання додаткової інформації:\n ##description## \n\n Тон відповіді повинен бути:\n ##tone_language## \n\n"],

            ['id' => 1607, 'template_id' => 44, 'key' => "lt-LT", 'value' =>  "Parašykite išsamų atsakymą į šį klausimą Quora:\n\n ##title## \n\nNorėdami gauti daugiau informacijos, naudokite šį turinį:\n ##description## \n\n Turi būti atsakymo balso tonas:\n ##tone_language## \n\n"],

            ['id' => 1608, 'template_id' => 44, 'key' => "bg-BG", 'value' =>  "Напишете подробен отговор за Quora на този въпрос:\n\n ##title## \n\nИзползвайте това съдържание за повече информация:\n ##description## \n\n Тонът на гласа на отговора трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1609, 'template_id' => 45, 'key' => "en-US", 'value' =>  "Write a detailed answer with bullet points:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 1610, 'template_id' => 45, 'key' => "ar-AE", 'value' =>"اكتب إجابة مفصلة بالنقاط :\n\n ##description## \n\n يجب أن تكون نبرة صوت النتيجة :\n ##tone_language## \n\n"],

            ['id' => 1611, 'template_id' => 45, 'key' => "cmn-CN", 'value' =>"用要點寫一個詳細的答案:\n\n ##description## \n\n 結果的語氣必須是:\n ##tone_language## \n\n" ],
                
            ['id' => 1612, 'template_id' => 45, 'key' => "hr-HR", 'value' =>"Napišite detaljan odgovor s točkama:\n\n ##description## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n" ],

            ['id' => 1613, 'template_id' => 45, 'key' => "cs-CZ", 'value' =>"Napište podrobnou odpověď s odrážkami:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n" ],

            ['id' => 1614, 'template_id' => 45, 'key' => "da-DK", 'value' =>"Skriv et detaljeret svar med punktopstillinger:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n" ],

            ['id' => 1615, 'template_id' => 45, 'key' => "nl-NL", 'value' =>"Schrijf een gedetailleerd antwoord met opsommingstekens:\n\n ##description## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n" ],

            ['id' => 1616, 'template_id' => 45, 'key' => "et-EE", 'value' =>"Kirjutage üksikasjalik vastus täppidega:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n" ],

            ['id' => 1617, 'template_id' => 45, 'key' => "fi-FI", 'value' =>"Kirjoita yksityiskohtainen vastaus luettelomerkein:\n\n ##description## \n\n Äänesävyn tuloksen on oltava:\n ##tone_language## \n\n" ],

            ['id' => 1618, 'template_id' => 45, 'key' => "fr-FR", 'value' =>"Rédigez une réponse détaillée avec une puce points:\n\n ##description## \n\n Le ton de la voix du résultat doit être:\n ##tone_language## \n\n" ],

            ['id' => 1619, 'template_id' => 45, 'key' => "de-DE", 'value' =>"Schreiben Sie eine ausführliche Antwort mit Aufzählungszeichen:\n\n ##description## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n" ],

            ['id' => 1620, 'template_id' => 45, 'key' => "el-GR", 'value' =>"Γράψτε μια λεπτομερή απάντηση με κουκκίδες:\n\n ##description## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n" ],

            ['id' => 1621, 'template_id' => 45, 'key' => "he-IL", 'value' =>"כתבו תשובה מפורטת עם תבליטים:\n\n ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n" ],

            ['id' => 1622, 'template_id' => 45, 'key' => "hi-IN", 'value' =>"बुलेट प्वाइंट्स के साथ विस्तृत उत्तर लिखें:\n\n ##description## \n\n परिणाम का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n" ],

            ['id' => 1623, 'template_id' => 45, 'key' => "hu-HU", 'value' =>"Írjon részletes választ pontokkal:\n\n ##description## \n\n Az eredmény hangnemének kell lennie:\n ##tone_language## \n\n" ],

            ['id' => 1624, 'template_id' => 45, 'key' => "is-IS", 'value' =>"Skrifaðu ítarlegt svar með punktum:\n\n ##description## \n\n Rödd í niðurstöðunni verður að vera:\n ##tone_language## \n\n" ],

            ['id' => 1625, 'template_id' => 45, 'key' => "id-ID", 'value' =>"Tulis jawaban terperinci dengan poin-poin:\n\n ##description## \n\n Nada suara hasilnya harus:\n ##tone_language## \n\n" ],

            ['id' => 1626, 'template_id' => 45, 'key' => "it-IT", 'value' =>"Scrivi una risposta dettagliata con elenchi puntati:\n\n ##description## \n\n Tono di voce del risultato deve essere:\n ##tone_language## \n\n" ],

            ['id' => 1627, 'template_id' => 45, 'key' => "ja-JP", 'value' =>"箇条書きで詳細な回答を書く:\n\n ##description## \n\n 結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n" ],

            ['id' => 1628, 'template_id' => 45, 'key' => "ko-KR", 'value' =>"글머리 기호로 자세한 답변을 작성하세요.:\n\n ##description## \n\n 결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n" ],

            ['id' => 1629, 'template_id' => 45, 'key' => "ms-MY", 'value' =>"Tulis jawapan terperinci dengan titik peluru:\n\n ##description## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n" ],

            ['id' => 1630, 'template_id' => 45, 'key' => "nb-NO", 'value' =>"Skriv et detaljert svar med punkter:\n\n ##description## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n" ],

            ['id' => 1631, 'template_id' => 45, 'key' => "pl-PL", 'value' =>"Napisz szczegółową odpowiedź z punktorami:\n\n ##description## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n" ],

            ['id' => 1632, 'template_id' => 45, 'key' => "pt-PT", 'value' =>"Escreva uma resposta detalhada com marcadores:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n" ],

            ['id' => 1633, 'template_id' => 45, 'key' => "ru-RU", 'value' =>"Напишите подробный ответ с пунктами:\n\n ##description## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n" ],

            ['id' => 1634, 'template_id' => 45, 'key' => "es-ES", 'value' =>"Escriba una respuesta detallada con viñetas:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n" ],

            ['id' => 1635, 'template_id' => 45, 'key' => "sv-SE", 'value' =>"Skriv ett utförligt svar med punkter:\n\n ##description## \n\n Tonen i rösten för resultatet måste vara:\n ##tone_language## \n\n" ],

            ['id' => 1636, 'template_id' => 45, 'key' => "tr-TR", 'value' =>"Madde işaretleri ile ayrıntılı bir cevap yazın:\n\n ##description## \n\n Sonucun ses tonu şöyle olmalıdır::\n ##tone_language## \n\n" ],

            ['id' => 1637, 'template_id' => 45, 'key' => "pt-BR", 'value' =>"Escreva uma resposta detalhada com marcadores:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n" ],

            ['id' => 1638, 'template_id' => 45, 'key' => "ro-RO", 'value' =>"Scrieți un răspuns detaliat cu puncte:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n" ],

            ['id' => 1639, 'template_id' => 45, 'key' => "vi-VN", 'value' =>"Viết một câu trả lời chi tiết với các gạch đầu dòng:\n\n ##description## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n" ],

            ['id' => 1640, 'template_id' => 45, 'key' => "sw-KE", 'value' =>"Andika jibu la kina na vidokezo vya risasi:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n" ],

            ['id' => 1641, 'template_id' => 45, 'key' => "sl-SI", 'value' =>"Napišite podroben odgovor z točkami:\n\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n" ],

            ['id' => 1642, 'template_id' => 45, 'key' => "th-TH", 'value' =>"เขียนคำตอบโดยละเอียดพร้อมสัญลักษณ์แสดงหัวข้อย่อย:\n\n ##description## \n\n น้ำเสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n" ],

            ['id' => 1643, 'template_id' => 45, 'key' => "uk-UA", 'value' =>"Напишіть розгорнуту відповідь, позначивши її маркерами:\n\n ##description## \n\n Тон голосу результату повинен бути:\n ##tone_language## \n\n" ],

            ['id' => 1644, 'template_id' => 45, 'key' => "lt-LT", 'value' =>"Parašykite išsamų atsakymą su taškais:\n\n ##description## \n\n Turi būti rezultato balso tonas:\n ##tone_language## \n\n" ],

            ['id' => 1645, 'template_id' => 45, 'key' => "bg-BG", 'value' =>"Напишете подробен отговор с точки:\n\n ##description## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n" ],

            ['id' => 1646, 'template_id' => 46, 'key' => "en-US", 'value' =>  "What is the meaning of:\n\n ##keyword## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 1647, 'template_id' => 46, 'key' => "ar-AE", 'value' =>  "ما معنى:\n\n ##keyword## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 1648, 'template_id' => 46, 'key' => "cmn-CN", 'value' =>  "是什么意思：\n\n ##keyword## \n\n 结果的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 1649, 'template_id' => 46, 'key' => "hr-HR", 'value' =>  "Što je smisao:\n\n ##keyword## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 1650, 'template_id' => 46, 'key' => "cs-CZ", 'value' =>  "Co znamená:\n\n ##keyword## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 1651, 'template_id' => 46, 'key' => "da-DK", 'value' =>  "Hvad er meningen med:\n\n ##keyword## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 1652, 'template_id' => 46, 'key' => "nl-NL", 'value' =>  "Wat is de betekenis van:\n\n ##keyword## \n\n Tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1653, 'template_id' => 46, 'key' => "et-EE", 'value' =>  "Mida tähendab:\n\n ##keyword## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1654, 'template_id' => 46, 'key' => "fi-FI", 'value' =>  "Mikä on tarkoitus:\n\n ##keyword## \n\n Tuloksen äänensävyn tulee olla:\n ##tone_language## \n\n"],

            ['id' => 1655, 'template_id' => 46, 'key' => "fr-FR", 'value' =>  "Quel est le sens de:\n\n ##keyword## \n\n Le ton de voix du résultat doit être :\n ##tone_language## \n\n"],

            ['id' => 1656, 'template_id' => 46, 'key' => "de-DE", 'value' =>  "Was ist die Bedeutung von:\n\n ##keyword## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 1657, 'template_id' => 46, 'key' => "el-GR", 'value' =>  "Ποια είναι η σημασία του:\n\n ##keyword## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 1658, 'template_id' => 46, 'key' => "he-IL", 'value' =>  "מה המשמעות של:\n\n ##keyword## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 1659, 'template_id' => 46, 'key' => "hi-IN", 'value' =>  "का अर्थ क्या है:\n\n ##keyword## \n\n परिणाम की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 1660, 'template_id' => 46, 'key' => "hu-HU", 'value' =>  "Mit jelent az hogy:\n\n ##keyword## \n\n Az eredmény hangnemének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1661, 'template_id' => 46, 'key' => "is-IS", 'value' =>  "Hvað þýðir:\n\n ##keyword## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1662, 'template_id' => 46, 'key' => "id-ID", 'value' =>  "Apa arti dari:\n\n ##keyword## \n\n Nada suara hasil harus:\n ##tone_language## \n\n"],

            ['id' => 1663, 'template_id' => 46, 'key' => "it-IT", 'value' =>  "Qual è il significato di:\n\n ##keyword## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 1664, 'template_id' => 46, 'key' => "ja-JP", 'value' =>  "意味は次のとおりです:\n\n ##keyword## \n\n 結果の口調は次のようになります:\n ##tone_language## \n\n"],

            ['id' => 1665, 'template_id' => 46, 'key' => "ko-KR", 'value' =>  "다음의 의미는 무엇입니까:\n\n ##keyword## \n\n 결과의 어조는 다음과 같아야 합니다.\n ##tone_language## \n\n"],

            ['id' => 1666, 'template_id' => 46, 'key' => "ms-MY", 'value' =>  "Apakah maksud:\n\n ##keyword## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 1667, 'template_id' => 46, 'key' => "nb-NO", 'value' =>  "Hva er betydningen av:\n\n ##keyword## \n\n Stemmetonen for resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 1668, 'template_id' => 46, 'key' => "pl-PL", 'value' =>  "Jakie jest znaczenie:\n\n ##keyword## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 1669, 'template_id' => 46, 'key' => "pt-PT", 'value' =>  "Qual é o significado de:\n\n ##keyword## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1670, 'template_id' => 46, 'key' => "ru-RU", 'value' =>  "Каково значение:\n\n ##keyword## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 1671, 'template_id' => 46, 'key' => "es-ES", 'value' =>  "Cuál es el significado de:\n\n ##keyword## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 1672, 'template_id' => 46, 'key' => "sv-SE", 'value' =>  "Vad är meningen med:\n\n ##keyword## \n\n Tonen i resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 1673, 'template_id' => 46, 'key' => "tr-TR", 'value' =>  "Anlamı ne:\n\n ##keyword## \n\n Sonucun ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 1674, 'template_id' => 46, 'key' => "pt-BR", 'value' =>  "Qual é o significado de:\n\n ##keyword## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1675, 'template_id' => 46, 'key' => "ro-RO", 'value' =>  "Ce înseamnă:\n\n ##keyword## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1676, 'template_id' => 46, 'key' => "vi-VN", 'value' =>  "Ý nghĩa của:\n\n ##keyword## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 1677, 'template_id' => 46, 'key' => "sw-KE", 'value' =>  "Nini maana ya:\n\n ##keyword## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1678, 'template_id' => 46, 'key' => "sl-SI", 'value' =>  "Kaj je pomen:\n\n ##keyword## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 1679, 'template_id' => 46, 'key' => "th-TH", 'value' =>  "ความหมายของ:\n\n ##keyword## \n\n โทนเสียงของผลลัพธ์จะต้อง:\n ##tone_language## \n\n"],

            ['id' => 1680, 'template_id' => 46, 'key' => "uk-UA", 'value' =>  "Яке значення:\n\n ##keyword## \n\n Тон голосу результату повинен бути таким:\n ##tone_language## \n\n"],

            ['id' => 1681, 'template_id' => 46, 'key' => "lt-LT", 'value' =>  "Ką reiškia:\n\n ##keyword## \n\n Rezultato balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 1682, 'template_id' => 46, 'key' => "bg-BG", 'value' =>  "Какво е значението на:\n\n ##keyword## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1683, 'template_id' => 47, 'key' => "en-US", 'value' => 'Write a long and detailed answer of:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 1684, 'template_id' => 47, 'key' => "ar-AE", 'value' => 'كتابة إجابة طويلة ومفصلة عن:\n\n ##description## \n\n Tالوحيدة من صوت النتيجة يجب أن تكون:\n ##tone_language## \n\n'],

            ['id' => 1685, 'template_id' => 47, 'key' => "cmn-CN", 'value' => '写长详细的答案:\n\n ##description## \n\n 结果的声音必须是:\n ##tone_language## \n\n'],

            ['id' => 1686, 'template_id' => 47, 'key' => "hr-HR", 'value' => 'Napišite dug i detaljan odgovor:\n\n ##description## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n'],

            ['id' => 1687, 'template_id' => 47, 'key' => "cs-CZ", 'value' => 'Napište dlouhou a podrobnou odpověď:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n'],

            ['id' => 1688, 'template_id' => 47, 'key' => "da-DK", 'value' => 'Skriv et langt og detaljeret svar på:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n'],

            ['id' => 1689, 'template_id' => 47, 'key' => "nl-NL", 'value' => 'Schrijf een lang en gedetailleerd antwoord van:\n\n ##description## \n\n Toon van de stem van het resultaat moet zijn:\n ##tone_language## \n\n'],

            ['id' => 1690, 'template_id' => 47, 'key' => "et-EE", 'value' => 'Kirjuta pikk ja üksikasjalik vastus:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 1691, 'template_id' => 47, 'key' => "fi-FI", 'value' => 'Kirjoita pitkä ja yksityiskohtainen vastaus:\n\n ##description## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n'],

            ['id' => 1692, 'template_id' => 47, 'key' => "fr-FR", 'value' => 'Rédiger une réponse longue et détaillée:\n\n ##description## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n'],

            ['id' => 1693, 'template_id' => 47, 'key' => "de-DE", 'value' => 'Schreiben Sie eine lange und detaillierte Antwort von:\n\n ##description## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n'],

            ['id' => 1694, 'template_id' => 47, 'key' => "el-GR", 'value' => 'Γράψτε μια μακρά και λεπτομερή απάντηση.:\n\n ##description## \n\n Η φωνή του αποτελέσματος πρέπει να είναι ...:\n ##tone_language## \n\n'],

            ['id' => 1695, 'template_id' => 47, 'key' => "he-IL", 'value' => 'כתיבת תשובה ארוכה ומפורטת של:\n\n ##description## \n\n Tone של הקול של התוצאה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 1696, 'template_id' => 47, 'key' => "hi-IN", 'value' => 'के एक लंबा और विस्तृत उत्तर लिखें:\n\n ##description## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n'],

            ['id' => 1697, 'template_id' => 47, 'key' => "hu-HU", 'value' => 'Írjon hosszú és részletes választ a:\n\n ##description## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n'],

            ['id' => 1698, 'template_id' => 47, 'key' => "is-IS", 'value' => 'Skrifaðu langt og ítarlegt svar af:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 1699, 'template_id' => 47, 'key' => "id-ID", 'value' => 'Tulis jawaban yang panjang dan rinci:\n\n ##description## \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n'],

            ['id' => 1700, 'template_id' => 47, 'key' => "it-IT", 'value' => 'Scrivi una risposta lunga e dettagliata di:\n\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n'],

            ['id' => 1701, 'template_id' => 47, 'key' => "ja-JP", 'value' => 'ロング・アンド・詳細な回答を書き込む:\n\n ##description## \n\n 結果の声のトーンは、:\n ##tone_language## \n\n'],

            ['id' => 1702, 'template_id' => 47, 'key' => "ko-KR", 'value' => '자세한 내용과 자세한 내용을 작성하십시오.:\n\n ##description## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다.:\n ##tone_language## \n\n'],

            ['id' => 1703, 'template_id' => 47, 'key' => "ms-MY", 'value' => 'Tulis jawapan panjang dan terperinci terperinci:\n\n ##description## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n'],

            ['id' => 1704, 'template_id' => 47, 'key' => "nb-NO", 'value' => 'Skriv et langt og detaljert svar på:\n\n ##description## \n\n Tone av stemmen til resultatet må være:\n ##tone_language## \n\n'],

            ['id' => 1705, 'template_id' => 47, 'key' => "pl-PL", 'value' => 'Napisz długą i szczegółową odpowiedź:\n\n ##description## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n'],

            ['id' => 1706, 'template_id' => 47, 'key' => "pt-PT", 'value' => 'Escrever uma resposta longa e pormenorizada sobre:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 1707, 'template_id' => 47, 'key' => "ru-RU", 'value' => 'Напишите длинный и подробный ответ на:\n\n ##description## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n'],

            ['id' => 1708, 'template_id' => 47, 'key' => "es-ES", 'value' => 'Escribir una respuesta larga y detallada de:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n'],

            ['id' => 1709, 'template_id' => 47, 'key' => "sv-SE", 'value' => 'Skriv ett långt och detaljerat svar på:\n\n ##description## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n'],

            ['id' => 1710, 'template_id' => 47, 'key' => "tr-TR", 'value' => 'Uzun ve ayrıntılı bir yanıt yazın:\n\n ##description## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n'],

            ['id' => 1711, 'template_id' => 47, 'key' => "pt-BR", 'value' => 'Escreva uma resposta longa e detalhada sobre:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 1712, 'template_id' => 47, 'key' => "ro-RO", 'value' => 'Scrie un răspuns lung și detaliat al:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 1713, 'template_id' => 47, 'key' => "vi-VN", 'value' => 'Viết câu trả lời dài và chi tiết:\n\n ##description## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n'],

            ['id' => 1714, 'template_id' => 47, 'key' => "sw-KE", 'value' => 'Andika jibu refu na la kina:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 1715, 'template_id' => 47, 'key' => "sl-SI", 'value' => 'Napišite dolg in podroben odgovor:\n\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n'],

            ['id' => 1716, 'template_id' => 47, 'key' => "th-TH", 'value' => 'เขียนคำตอบยาวๆและละเอียดของ:\n\n ##description## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 1717, 'template_id' => 47, 'key' => "uk-UA", 'value' => 'Написати довгу і детальну відповідь:\n\n ##description## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n'],

            ['id' => 1718, 'template_id' => 47, 'key' => "lt-LT", 'value' => 'Rašykite ilgą ir išsamų atsakymą:\n\n ##description## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n'],

            ['id' => 1719, 'template_id' => 47, 'key' => "bg-BG", 'value' => 'Напишете дълъг и подробен отговор на:\n\n ##description## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 1720, 'template_id' => 48, 'key' => "en-US", 'value' =>  "Create 10 engaging questions from this paragraph:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],
                
            ['id' => 1721, 'template_id' => 48, 'key' => "ar-AE", 'value' =>  "قم بإنشاء 10 أسئلة جذابة من هذه الفقرة:\n\n ##description## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 1722, 'template_id' => 48, 'key' => "cmn-CN", 'value' =>  "根據本段提出 10 個引人入勝的問題:\n\n ##description## \n\n 結果的語氣必須是:\n ##tone_language## \n\n"],

            ['id' => 1723, 'template_id' => 48, 'key' => "hr-HR", 'value' =>  "Napravite 10 zanimljivih pitanja iz ovog odlomka:\n\n ##description## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 1724, 'template_id' => 48, 'key' => "cs-CZ", 'value' =>  "Vytvořte 10 poutavých otázek z tohoto odstavce:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 1725, 'template_id' => 48, 'key' => "da-DK", 'value' =>  "Lav 10 engagerende spørgsmål fra dette afsnit:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 1726, 'template_id' => 48, 'key' => "nl-NL", 'value' =>  "Maak 10 boeiende vragen uit deze paragraaf:\n\n ##description## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1727, 'template_id' => 48, 'key' => "et-EE", 'value' =>  "Looge sellest lõigust 10 köitvat küsimust:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1728, 'template_id' => 48, 'key' => "fi-FI", 'value' =>  "Luo 10 kiinnostavaa kysymystä tästä kappaleesta:\n\n ##description## \n\n Äänesävyn tuloksen on oltava:\n ##tone_language## \n\n"],

            ['id' => 1729, 'template_id' => 48, 'key' => "fr-FR", 'value' =>  "Créez 10 questions engageantes à partir de ce paragraphe:\n\n ##description## \n\n Le ton de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 1730, 'template_id' => 48, 'key' => "de-DE", 'value' =>  "Erstellen Sie aus diesem Absatz 10 spannende Fragen:\n\n ##description## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 1731, 'template_id' => 48, 'key' => "el-GR", 'value' =>  "Δημιουργήστε 10 ενδιαφέρουσες ερωτήσεις από αυτήν την παράγραφο:\n\n ##description## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 1732, 'template_id' => 48, 'key' => "he-IL", 'value' =>  "צור 10 שאלות מרתקות מפסקה זו:\n\n ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 1733, 'template_id' => 48, 'key' => "hi-IN", 'value' =>  "इस पैराग्राफ से 10 आकर्षक प्रश्न बनाएं:\n\n ##description## \n\n परिणाम का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 1734, 'template_id' => 48, 'key' => "hu-HU", 'value' =>  "Hozz létre 10 megnyerő kérdést ebből a bekezdésből:\n\n ##description## \n\n Az eredmény hangnemének kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1735, 'template_id' => 48, 'key' => "is-IS", 'value' =>  "Búðu til 10 áhugaverðar spurningar úr þessari málsgrein:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1736, 'template_id' => 48, 'key' => "id-ID", 'value' =>  "Buat 10 pertanyaan menarik dari paragraf ini:\n\n ##description## \n\n Nada suara hasilnya harus:\n ##tone_language## \n\n"],

            ['id' => 1737, 'template_id' => 48, 'key' => "it-IT", 'value' =>  "Crea 10 domande coinvolgenti da questo paragrafo:\n\n ##description## \n\n Tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 1738, 'template_id' => 48, 'key' => "ja-JP", 'value' =>  "この段落から魅力的な質問を 10 個作成します:\n\n ##description## \n\n 結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n"],

            ['id' => 1739, 'template_id' => 48, 'key' => "ko-KR", 'value' =>  "이 단락에서 10개의 매력적인 질문을 만드십시오.:\n\n ##description## \n\n 결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 1740, 'template_id' => 48, 'key' => "ms-MY", 'value' =>  "Cipta 10 soalan yang menarik daripada perenggan ini:\n\n ##description## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 1741, 'template_id' => 48, 'key' => "nb-NO", 'value' =>  "Lag 10 engasjerende spørsmål fra dette avsnittet:\n\n ##description## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 1742, 'template_id' => 48, 'key' => "pl-PL", 'value' =>  "Utwórz 10 interesujących pytań z tego akapitu:\n\n ##description## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 1743, 'template_id' => 48, 'key' => "pt-PT", 'value' =>  "Crie 10 perguntas envolventes a partir deste parágrafo:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1744, 'template_id' => 48, 'key' => "ru-RU", 'value' =>  "Создайте 10 увлекательных вопросов из этого абзаца:\n\n ##description## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 1745, 'template_id' => 48, 'key' => "es-ES", 'value' =>  "Crea 10 preguntas atractivas a partir de este párrafo:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 1746, 'template_id' => 48, 'key' => "sv-SE", 'value' =>  "Skapa 10 engagerande frågor från detta stycke:\n\n ##description## \n\n Tonen i rösten för resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 1747, 'template_id' => 48, 'key' => "tr-TR", 'value' =>  "Bu paragraftan 10 ilgi çekici soru oluşturun:\n\n ##description## \n\n Sonucun ses tonu şöyle olmalıdır::\n ##tone_language## \n\n"],

            ['id' => 1748, 'template_id' => 48, 'key' => "pt-BR", 'value' =>  "Crie 10 perguntas envolventes a partir deste parágrafo:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1749, 'template_id' => 48, 'key' => "ro-RO", 'value' =>  "Creați 10 întrebări captivante din acest paragraf:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1750, 'template_id' => 48, 'key' => "vi-VN", 'value' =>  "Tạo 10 câu hỏi hấp dẫn từ đoạn văn này:\n\n ##description## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 1751, 'template_id' => 48, 'key' => "sw-KE", 'value' =>  "Unda maswali 10 ya kuvutia kutoka kwa aya hii:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1752, 'template_id' => 48, 'key' => "sl-SI", 'value' =>  "Ustvarite 10 privlačnih vprašanj iz tega odstavka:\n\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 1753, 'template_id' => 48, 'key' => "th-TH", 'value' =>  "สร้างคำถามที่น่าสนใจ 10 ข้อจากย่อหน้านี้:\n\n ##description## \n\n น้ำเสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1754, 'template_id' => 48, 'key' => "uk-UA", 'value' =>  "Створіть 10 цікавих запитань із цього абзацу:\n\n ##description## \n\n Тон голосу результату повинен бути:\n ##tone_language## \n\n"],

            ['id' => 1755, 'template_id' => 48, 'key' => "lt-LT", 'value' =>  "Sukurkite 10 patrauklių klausimų iš šios pastraipos:\n\n ##description## \n\n Turi būti rezultato balso tonas:\n ##tone_language## \n\n"],

            ['id' => 1756, 'template_id' => 48, 'key' => "bg-BG", 'value' =>  "Създайте 10 ангажиращи въпроса от този параграф:\n\n ##description## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],
                
            ['id' => 1757, 'template_id' => 49, 'key' => "en-US", 'value' => 'Convert this passive voice sentence into active voice:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 1758, 'template_id' => 49, 'key' => "ar-AE", 'value' => 'تحويل هذه الجملة الصوتية السلبية الى صوت فعال:\n\n ##description## \n\n Tالوحيدة من صوت النتيجة يجب أن تكون:\n ##tone_language## \n\n'],

            ['id' => 1759, 'template_id' => 49, 'key' => "cmn-CN", 'value' => '将此被动语音语句转换为活动语音:\n\n ##description## \n\n 结果的声音必须是:\n ##tone_language## \n\n'],

            ['id' => 1760, 'template_id' => 49, 'key' => "hr-HR", 'value' => 'Pretvori ovu pasivnu glasovnu rečenicu u aktivni glas:\n\n ##description## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n'],

            ['id' => 1761, 'template_id' => 49, 'key' => "cs-CZ", 'value' => 'Převést tuto pasivní hlasovou větu do aktivního hlasu:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n'],

            ['id' => 1762, 'template_id' => 49, 'key' => "da-DK", 'value' => 'Konvertér denne passive stemmesætning til aktiv stemme:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n'],

            ['id' => 1763, 'template_id' => 49, 'key' => "nl-NL", 'value' => 'Deze passieve stem omzetten in actieve stem:\n\n ##description## \n\n Toon van de stem van het resultaat moet zijn:\n ##tone_language## \n\n'],

            ['id' => 1764, 'template_id' => 49, 'key' => "et-EE", 'value' => 'Selle passiivse häälelause teisendamine aktiivsesse häälesse:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 1765, 'template_id' => 49, 'key' => "fi-FI", 'value' => 'Muunna tämä passiivinen ääni aktiiviseksi ääneksi:\n\n ##description## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n'],

            ['id' => 1766, 'template_id' => 49, 'key' => "fr-FR", 'value' => 'Convertir cette phrase vocale passive en voix active:\n\n ##description## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n'],

            ['id' => 1767, 'template_id' => 49, 'key' => "de-DE", 'value' => 'Diesen passiven Sprachsatz in aktive Stimme umwandeln:\n\n ##description## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n'],

            ['id' => 1768, 'template_id' => 49, 'key' => "el-GR", 'value' => 'Μετάτρεψε αυτή την παθητική φωνή σε ενεργή φωνή.:\n\n ##description## \n\n v:\n ##tone_language## \n\n'],

            ['id' => 1769, 'template_id' => 49, 'key' => "he-IL", 'value' => 'המרת משפט קול פסיבי לקול פעיל:\n\n ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 1770, 'template_id' => 49, 'key' => "hi-IN", 'value' => 'इस निष्क्रिय आवाज वाक्य को सक्रिय आवाज में बदलें:\n\n ##description## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n'],

            ['id' => 1771, 'template_id' => 49, 'key' => "hu-HU", 'value' => 'A passzív hangmondat átalakítása aktív hanggá:\n\n ##description## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n'],

            ['id' => 1772, 'template_id' => 49, 'key' => "is-IS", 'value' => 'Umbreyttu þessari óvirku raddsetningu í virka rödd:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 1773, 'template_id' => 49, 'key' => "id-ID", 'value' => 'Ubah kalimat suara pasif ini menjadi suara aktif:\n\n ##description## \n\n TNada suara hasilnya harus dibuat.:\n ##tone_language## \n\n'],

            ['id' => 1774, 'template_id' => 49, 'key' => "it-IT", 'value' => 'Convertire questa frase voce passivo in voce attiva:\n\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n'],

            ['id' => 1775, 'template_id' => 49, 'key' => "ja-JP", 'value' => 'この受動音声文をアクティブな音声に変換します:\n\n ##description## \n\n 結果の声のトーンは、:\n ##tone_language## \n\n'],

            ['id' => 1776, 'template_id' => 49, 'key' => "ko-KR", 'value' => '이 수동 음성 문장을 활성 음성으로 변환합니다.:\n\n ##description## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다.:\n ##tone_language## \n\n'],

            ['id' => 1777, 'template_id' => 49, 'key' => "ms-MY", 'value' => 'Tukar ayat suara pasif ini ke dalam suara aktif:\n\n ##description## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n'],

            ['id' => 1778, 'template_id' => 49, 'key' => "nb-NO", 'value' => 'Konverter denne passive stemmesetningen inn i aktiv stemme:\n\n ##description## \n\n Tone av stemmen til resultatet må være:\n ##tone_language## \n\n'],

            ['id' => 1779, 'template_id' => 49, 'key' => "pl-PL", 'value' => 'Przekształć ten bierny głos głosowy w aktywny głos:\n\n ##description## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n'],

            ['id' => 1780, 'template_id' => 49, 'key' => "pt-PT", 'value' => 'Converter esta frase de voz passiva em voz activa:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 1781, 'template_id' => 49, 'key' => "ru-RU", 'value' => 'Преобразовать этот пассивный голос в активный голос:\n\n ##description## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n'],

            ['id' => 1782, 'template_id' => 49, 'key' => "es-ES", 'value' => 'Convertir esta frase de voz pasiva en voz activa:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n'],

            ['id' => 1783, 'template_id' => 49, 'key' => "sv-SE", 'value' => 'Konvertera den här passiva röstdomen till aktiv röst:\n\n ##description## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n'],

            ['id' => 1784, 'template_id' => 49, 'key' => "tr-TR", 'value' => 'Bu pasif sesli cümleyi etkin sese dönüştür:\n\n ##description## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n'],

            ['id' => 1785, 'template_id' => 49, 'key' => "pt-BR", 'value' => 'Converta essa frase de voz passiva em voz ativa:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 1786, 'template_id' => 49, 'key' => "ro-RO", 'value' => 'Convertiți această propoziție vocală pasivă în voce activă:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 1787, 'template_id' => 49, 'key' => "vi-VN", 'value' => 'Chuyển đổi câu thoại bị động này thành giọng nói hoạt động:\n\n ##description## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n'],

            ['id' => 1788, 'template_id' => 49, 'key' => "sw-KE", 'value' => 'Badilisha sentensi hii ya sauti tulivu kuwa sauti tendaji:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 1789, 'template_id' => 49, 'key' => "sl-SI", 'value' => 'Pretvori to pasivno glasovno kazen v aktivni glas:\n\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n'],

            ['id' => 1790, 'template_id' => 49, 'key' => "th-TH", 'value' => 'แปลงประโยคเสียงพาสซีฟนี้เป็นเสียงที่แอ็คทีฟ:\n\n ##description## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 1791, 'template_id' => 49, 'key' => "uk-UA", 'value' => 'Перетворити цей пасивний голосовий речення на активний голос:\n\n ##description## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n'],

            ['id' => 1792, 'template_id' => 49, 'key' => "lt-LT", 'value' => 'Konvertuoti šį pasyvaus balso sakinį į aktyvų balsą:\n\n ##description## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n'],

            ['id' => 1793, 'template_id' => 49, 'key' => "bg-BG", 'value' => 'Превръщане на това пасивно изречение в активен глас:\n\n ##description## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 1794, 'template_id' => 50, 'key' => "en-US", 'value' =>   "Improve and rewrite this content in a smart way:\n\n ##description## \n\nMust use following keywords in the content:\n ##keywords## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 1795, 'template_id' => 50, 'key' => "ar-AE", 'value' =>   "تحسين واعادة كتابة هذه المحتويات بطريقة ذكية:\n\n ##description## \n\nيجب استخدام الكلمات المرشدة التالية في المحتويات:\n ##keywords## \n\n Tالوحيدة من صوت النتيجة يجب أن تكون:\n ##tone_language## \n\n"],

            ['id' => 1796, 'template_id' => 50, 'key' => "cmn-CN", 'value' =>   "以智慧型方式改善並改寫此內容:\n\n ##description## \n\n必須在內容中使用下列關鍵字:\n ##keywords## \n\n 必須要有聲音的聲音:\n ##tone_language## \n\n"],

            ['id' => 1797, 'template_id' => 50, 'key' => "hr-HR", 'value' =>   "Poboljste i ponovno napišite ovaj sadržaj na pametan način:\n\n ##description## \n\nMora se koristiti sljedeće ključne riječi u sadržaju:\n ##keywords## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n"],

            ['id' => 1798, 'template_id' => 50, 'key' => "cs-CZ", 'value' =>   "Vylepšete a přepište tento obsah inteligentním způsobem:\n\n ##description## \n\nMusí používat následující klíčová slova v obsahu:\n ##keywords## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 1799, 'template_id' => 50, 'key' => "da-DK", 'value' =>   "Forbedre og reskriv dette indhold på en smart måde:\n\n ##description## \n\nMSkal bruge følgende nøgleord i indholdet:\n ##keywords## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 1800, 'template_id' => 50, 'key' => "nl-NL", 'value' =>   "Deze content op een slimme manier verbeteren en herschrijven:\n\n ##description## \n\nMoet gebruik maken van de volgende sleutelwoorden in de inhoud:\n ##keywords## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1801, 'template_id' => 50, 'key' => "et-EE", 'value' =>   "Selle sisu parandamine ja ümberkirjutamine arukalt:\n\n ##description## \n\nPeab kasutama järgmisi märksõnu sisu:\n ##keywords## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1802, 'template_id' => 50, 'key' => "fi-FI", 'value' =>   "Paranna ja kirjoita tämä sisältö uudelleen älykkäällä tavalla:\n\n ##description## \n\nOn käytettävä sisällön avainsanojen jälkeen:\n ##keywords## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n"],

            ['id' => 1803, 'template_id' => 50, 'key' => "fr-FR", 'value' =>   "Améliorer et réécrire ce contenu de manière intelligente:\n\n ##description## \n\nDoit utiliser les mots clés suivants dans le contenu:\n ##keywords## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 1804, 'template_id' => 50, 'key' => "de-DE", 'value' =>   "Verbessern und umschreiben Sie diese Inhalte auf intelligente Weise:\n\n ##description## \n\nSie müssen die folgenden Schlüsselwörter im Inhalt verwenden:\n ##keywords## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n"],

            ['id' => 1805, 'template_id' => 50, 'key' => "el-GR", 'value' =>   "Βελτίωση και επανεγγραφή αυτού του περιεχομένου με έξυπνο τρόπο:\n\n ##description## \n\nΠρέπει να χρησιμοποιήσετε λέξεις-κλειδιά στο περιεχόμενο.:\n ##keywords## \n\n Η φωνή του αποτελέσματος πρέπει να είναι ...:\n ##tone_language## \n\n"],

            ['id' => 1806, 'template_id' => 50, 'key' => "he-IL", 'value' =>   "שיפור ושכתב תוכן זה בדרך חכמה.:\n\n ##description## \n\nיש להשתמש במילות המפתח שלהלן בתוכן:\n ##keywords## \n\n טון הדיבור של הפסקאות חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 1807, 'template_id' => 50, 'key' => "hi-IN", 'value' =>   "इस सामग्री को एक स्मार्ट तरीका में सुधार और फिर से लिखना:\n\n ##description## \n\nसामग्री में निम्नलिखित कीशब्दों का प्रयोग करना चाहिए:\n ##keywords## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n"],

            ['id' => 1808, 'template_id' => 50, 'key' => "hu-HU", 'value' =>   "Tartalom javítása és újraírása intelligens módon:\n\n ##description## \n\nA következő kulcsszavakat kell használni a tartalomban:\n ##keywords## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1809, 'template_id' => 50, 'key' => "is-IS", 'value' =>   "Bættu og endurskrifaðu þetta efni á snjallan hátt:\n\n ##description## \n\nVerður að nota eftirfarandi leitarorð í innihaldinu:\n ##keywords## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1810, 'template_id' => 50, 'key' => "id-ID", 'value' =>   "Meningkatkan dan menulis ulang konten ini dengan cara yang cerdas:\n\n ##description## \n\nHarus menggunakan kata kunci berikut dalam isi:\n ##keywords## \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n"],

            ['id' => 1811, 'template_id' => 50, 'key' => "it-IT", 'value' =>   "Migliorare e riscrivere questo contenuto in modo intelligente:\n\n ##description## \n\nDeve utilizzare le seguenti parole chiave nel contenuto:\n ##keywords## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 1812, 'template_id' => 50, 'key' => "ja-JP", 'value' =>   "このコンテンツをスマートな方法で改善して再書き込みする:\n\n ##description## \n\nコンテンツ内の以下のキーワードを使用する必要:\n ##keywords## \n\n 結果の声のトーンは、:\n ##tone_language## \n\n"],

            ['id' => 1813, 'template_id' => 50, 'key' => "ko-KR", 'value' =>   "스마트한 방식으로 이 컨텐츠를 개선하고 다시 작성합니다.:\n\n ##description## \n\n컨텐츠에서 다음 키워드를 사용해야 합니다.:\n ##keywords## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 1814, 'template_id' => 50, 'key' => "ms-MY", 'value' =>   "Tingkatkan dan tulis semula kandungan ini dalam cara pintar:\n\n ##description## \n\nMesti gunakan perkataan kekunci berikut dalam kandungan:\n ##keywords## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n"],

            ['id' => 1815, 'template_id' => 50, 'key' => "nb-NO", 'value' =>   "Forbedre og omskriv dette innholdet på en smart måte:\n\n ##description## \n\nMå bruke følgende nøkkelord i innholdet:\n ##keywords## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 1816, 'template_id' => 50, 'key' => "pl-PL", 'value' =>   "Ulepszanie i przepisanie tej treści w inteligentny sposób:\n\n ##description## \n\nW treści muszą być używane następujące słowa kluczowe::\n ##keywords## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 1817, 'template_id' => 50, 'key' => "pt-PT", 'value' =>   "Melhore e reescreva este conteúdo de forma inteligente:\n\n ##description## \n\nDeve usar seguindo palavras-chave no conteúdo:\n ##keywords## \n\n Tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1818, 'template_id' => 50, 'key' => "ru-RU", 'value' =>   "Улучшить и переписать это содержимое в смарт-пути:\n\n ##description## \n\nНеобходимо использовать следующие ключевые слова в содержимом:\n ##keywords## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 1819, 'template_id' => 50, 'key' => "es-ES", 'value' =>   "Mejorar y reescribir este contenido de forma inteligente:\n\n ##description## \n\nDebe utilizar las palabras clave siguientes en el contenido:\n ##keywords## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 1820, 'template_id' => 50, 'key' => "sv-SE", 'value' =>   "Förbättra och skriva om det här innehållet på ett smart sätt:\n\n ##description## \n\nMåste använda följande nyckelord i innehållet:\n ##keywords## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 1821, 'template_id' => 50, 'key' => "tr-TR", 'value' =>   "Bu içeriği geliştirin ve akıllı bir şekilde yeniden yazın.:\n\n ##description## \n\nİçerikte aşağıdaki anahtar sözcükleri kullanmak gerekir::\n ##keywords## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n"],

            ['id' => 1822, 'template_id' => 50, 'key' => "pt-BR", 'value' =>   "Melhore e reescreva este conteúdo de forma inteligente:\n\n ##description## \n\nDeve usar seguindo palavras-chave no conteúdo:\n ##keywords## \n\n Tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1823, 'template_id' => 50, 'key' => "ro-RO", 'value' =>   "Îmbunătățiți și rescrieți acest conținut într-un mod inteligent:\n\n ##description## \n\nTrebuie să utilizeze următoarele cuvinte cheie în conținut:\n ##keywords## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1824, 'template_id' => 50, 'key' => "vi-VN", 'value' =>   "Cải thiện và viết lại nội dung này theo cách thông minh:\n\n ##description## \n\nPhải dùng sau tư ̀ khóa trong nội dung:\n ##keywords## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 1825, 'template_id' => 50, 'key' => "sw-KE", 'value' =>   "Boresha na uandike upya maudhui haya kwa njia nzuri:\n\n ##description## \n\nLazima utumie manenomsingi yafuatayo katika maudhui:\n ##keywords## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1826, 'template_id' => 50, 'key' => "sl-SI", 'value' =>   "Izboljšite in znova napišite to vsebino na pameten način:\n\n ##description## \n\nUporabiti morate naslednje ključne besede v vsebini:\n ##keywords## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 1827, 'template_id' => 50, 'key' => "th-TH", 'value' =>   "ปรับปรุงและเขียนเนื้อหานี้ใหม่ในวิธีที่ฉลาด:\n\n ##description## \n\nต้องใช้คีย์เวิร์ดต่อไปนี้ในเนื้อหา:\n ##keywords## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1828, 'template_id' => 50, 'key' => "uk-UA", 'value' =>   "Покращення і переписати цей вміст у розумний спосіб:\n\n ##description## \n\nМає використовувати наступні ключові слова у зміні:\n ##keywords## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n"],

            ['id' => 1829, 'template_id' => 50, 'key' => "lt-LT", 'value' =>   "Pagerinti ir perrašyti šį turinį protingu būdu:\n\n ##description## \n\nTuri naudoti šiuos raktažodžius turinio:\n ##keywords## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n"],

            ['id' => 1830, 'template_id' => 50, 'key' => "bg-BG", 'value' =>   "Подобрете и презапишете това съдържание по умен начин:\n\n ##description## \n\nТрябва да използвате следните ключови думи в съдържанието:\n ##keywords## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1831, 'template_id' => 51, 'key' => "en-US", 'value' =>  "please check grammar mistakes in this:\n\n ##description## and correct it"],

            ['id' => 1832, 'template_id' => 51, 'key' => "ar-AE", 'value' =>  "يرجى التحقق من الأخطاء النحوية في هذا:\n\n ##description## وتصحيحه"],

            ['id' => 1833, 'template_id' => 51, 'key' => "cmn-CN", 'value' =>  "請檢查這裡的語法錯誤:\n\n ##description## 並改正"],

            ['id' => 1834, 'template_id' => 51, 'key' => "hr-HR", 'value' =>  "molimo provjerite gramatičke pogreške u ovome:\n\n ##description## i ispraviti ga"],

            ['id' => 1835, 'template_id' => 51, 'key' => "cs-CZ", 'value' =>  "zkontrolujte prosím gramatické chyby v tomto:\n\n ##description## a opravit to"],

            ['id' => 1836, 'template_id' => 51, 'key' => "da-DK", 'value' =>  "tjek venligst grammatikfejl i dette:\n\n ##description## og rette det"],

            ['id' => 1837, 'template_id' => 51, 'key' => "nl-NL", 'value' =>  "controleer de grammaticafouten hierin:\n\n ##description## en corrigeer het"],

            ['id' => 1838, 'template_id' => 51, 'key' => "et-EE", 'value' =>  "palun kontrolli selles grammatikavigu:\n\n ##description## ja parandage see"],
                
            ['id' => 1839, 'template_id' => 51, 'key' => "fi-FI", 'value' =>  "tarkista kielioppivirheet tässä:\n\n ##description## ja korjaa se"],

            ['id' => 1840, 'template_id' => 51, 'key' => "fr-FR", 'value' =>  "s'il vous plaît vérifier les erreurs de grammaire dans ce:\n\n ##description## et corrigez-le"],
                
            ['id' => 1841, 'template_id' => 51, 'key' => "de-DE", 'value' =>  "Bitte überprüfen Sie hier die Grammatikfehler:\n\n ##description##und korrigieren Sie es"],

            ['id' => 1842, 'template_id' => 51, 'key' => "el-GR", 'value' =>  "ελέγξτε τα γραμματικά λάθη σε αυτό:\n\n ##description## και διορθώστε το"],
                
            ['id' => 1843, 'template_id' => 51, 'key' => "he-IL", 'value' =>  "אנא בדוק את טעויות הדקדוק בזה:\n\n ##description## ולתקן את זה"],

            ['id' => 1844, 'template_id' => 51, 'key' => "hi-IN", 'value' =>  "कृपया इसमें व्याकरण की गलतियों की जांच करें:\n\n ##description## और इसे ठीक करें"],

            ['id' => 1845, 'template_id' => 51, 'key' => "hu-HU", 'value' =>  "Kérjük, ellenőrizze a nyelvtani hibákat ebben:\n\n ##description## és javítsa ki"],

            ['id' => 1846, 'template_id' => 51, 'key' => "is-IS", 'value' =>  "vinsamlegast athugaðu málfræðivillur í þessu:\n\n ##description## og leiðrétta það"],

            ['id' => 1847, 'template_id' => 51, 'key' => "id-ID", 'value' =>  "silakan periksa kesalahan tata bahasa dalam hal ini:\n\n ##description## dan memperbaikinya"],

            ['id' => 1848, 'template_id' => 51, 'key' => "it-IT", 'value' =>  "per favore controlla gli errori grammaticali in questo:\n\n ##description## e correggilo"],

            ['id' => 1849, 'template_id' => 51, 'key' => "ja-JP", 'value' =>  "この文法の間違いを確認してください:\n\n ##description## そしてそれを修正してください"],

            ['id' => 1850, 'template_id' => 51, 'key' => "ko-KR", 'value' =>  "이것에서 문법 오류를 확인하십시오:\n\n ##description## 수정하고"],

            ['id' => 1851, 'template_id' => 51, 'key' => "ms-MY", 'value' =>  "sila semak kesalahan tatabahasa dalam ini:\n\n ##description## dan betulkan"],

            ['id' => 1852, 'template_id' => 51, 'key' => "nb-NO", 'value' =>  "Vennligst sjekk grammatikkfeil i dette:\n\n ##description## og korrigere det"],

            ['id' => 1853, 'template_id' => 51, 'key' => "pl-PL", 'value' =>  "proszę sprawdzić błędy gramatyczne w tym:\n\n ##description## i popraw to"],

            ['id' => 1854, 'template_id' => 51, 'key' => "pt-PT", 'value' =>  "por favor, verifique os erros gramaticais neste:\n\n ##description## e corrija"],

            ['id' => 1855, 'template_id' => 51, 'key' => "ru-RU", 'value' =>  "пожалуйста, проверьте грамматические ошибки в этом:\n\n ##description## и исправить это"],

            ['id' => 1856, 'template_id' => 51, 'key' => "es-ES", 'value' =>  "por favor revise los errores gramaticales en este:\n\n ##description## y corregirlo"],

            ['id' => 1857, 'template_id' => 51, 'key' => "sv-SE", 'value' =>  "kontrollera grammatikfel i detta:\n\n ##description## och rätta till det"],

            ['id' => 1858, 'template_id' => 51, 'key' => "tr-TR", 'value' =>  "lütfen buradaki gramer hatalarını kontrol edin:\n\n ##description## ve düzelt"],

            ['id' => 1859, 'template_id' => 51, 'key' => "pt-BR", 'value' =>  "por favor, verifique os erros gramaticais neste:\n\n ##description## e corrija"],

            ['id' => 1860, 'template_id' => 51, 'key' => "ro-RO", 'value' =>  "Vă rugăm să verificați greșelile gramaticale din aceasta:\n\n ##description## si corecteaza-l"],

            ['id' => 1861, 'template_id' => 51, 'key' => "vi-VN", 'value' =>  "vui lòng kiểm tra lỗi ngữ pháp trong này:\n\n ##description## và sửa nó"],

            ['id' => 1862, 'template_id' => 51, 'key' => "sw-KE", 'value' =>  "tafadhali angalia makosa ya sarufi katika hili:\n\n ##description## na kusahihisha"],

            ['id' => 1863, 'template_id' => 51, 'key' => "sl-SI", 'value' =>  "prosim preverite slovnične napake v tem:\n\n ##description## in ga popravi"],

            ['id' => 1864, 'template_id' => 51, 'key' => "th-TH", 'value' =>  "โปรดตรวจสอบข้อผิดพลาดทางไวยากรณ์ในเรื่องนี้:\n\n ##description## และแก้ไขให้ถูกต้อง"],

            ['id' => 1865, 'template_id' => 51, 'key' => "uk-UA", 'value' =>  "будь ласка, перевірте граматичні помилки в цьому:\n\n ##description## і виправте це"],

            ['id' => 1866, 'template_id' => 51, 'key' => "lt-LT", 'value' =>  "patikrinkite gramatikos klaidas:\n\n ##description## ir pataisyti"],

            ['id' => 1867, 'template_id' => 51, 'key' => "bg-BG", 'value' =>  "моля, проверете граматическите грешки в това:\n\n ##description## и го коригирайте"],

            ['id' => 1868, 'template_id' => 52, 'key' => "en-US", 'value' =>  "Write a vision that attracts the right people and customers. \n\nCompany Name:\n ##title## \n\nCompany Information:\n ##description## \n\nTone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 1869, 'template_id' => 52, 'key' => "ar-AE", 'value' =>  "اكتب رؤية تجذب الأشخاص والعملاء المناسبين.\n\n اسم الشركة:\n ##title## \n\nمعلومات الشركة:\n ##description## \n\nيجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 1870, 'template_id' => 52, 'key' => "cmn-CN", 'value' =>  "寫下吸引合適的人和客戶的願景。 \n\n公司名稱:\n ##title## \n\n公司信息:\n ##description## \n\n結果的語氣必須是:\n ##tone_language## \n\n"],

            ['id' => 1871, 'template_id' => 52, 'key' => "hr-HR", 'value' =>  "Napišite viziju koja privlači prave ljude i kupce. \n\nNaziv tvrtke:\n ##title## \n\nInformacije o tvrtki:\n ##description## \n\nTon glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 1872, 'template_id' => 52, 'key' => "cs-CZ", 'value' =>  "Napište vizi, která přiláká ty správné lidi a zákazníky. \n\nJméno společnosti:\n ##title## \n\nInformace o společnosti:\n ##description## \n\nTón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 1873, 'template_id' => 52, 'key' => "da-DK", 'value' =>  "Skriv en vision, der tiltrækker de rigtige mennesker og kunder. \n\nfirmanavn:\n ##title## \n\nVirksomhedsoplysninger:\n ##description## \n\nTone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 1874, 'template_id' => 52, 'key' => "nl-NL", 'value' =>  "Schrijf een visie die de juiste mensen en klanten aantrekt. \n\nBedrijfsnaam:\n ##title## \n\nbedrijfsinformatie:\n ##description## \n\nDe tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1875, 'template_id' => 52, 'key' => "et-EE", 'value' =>  "Kirjutage visioon, mis meelitab ligi õigeid inimesi ja kliente. \n\nEttevõtte nimi:\n ##title## \n\nettevõtte info:\n ##description## \n\nTulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1876, 'template_id' => 52, 'key' => "fi-FI", 'value' =>  "Kirjoita visio, joka houkuttelee oikeat ihmiset ja asiakkaat \n\nYrityksen nimi:\n ##title## \n\nYrityksen tiedot:\n ##description## \n\nÄänesävyn tuloksen on oltava:\n ##tone_language## \n\n"],

            ['id' => 1877, 'template_id' => 52, 'key' => "fr-FR", 'value' =>  "Rédigez une vision qui attire les bonnes personnes et les bons clients. \n\nNom de l'entreprise:\n ##title## \n\nInformations sur la société:\n ##description## \n\nLe ton de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 1878, 'template_id' => 52, 'key' => "de-DE", 'value' =>  "Schreiben Sie eine Vision, die die richtigen Leute und Kunden anzieht. \n\nName der Firma:\n ##title## \n\nFirmeninformation:\n ##description## \n\nDer Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 1879, 'template_id' => 52, 'key' => "el-GR", 'value' =>  "Γράψτε ένα όραμα που προσελκύει τους κατάλληλους ανθρώπους και πελάτες. \n\nΌνομα εταιρείας:\n ##title## \n\nΣτοιχεία της εταιρείας:\n ##description## \n\nΟ τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 1880, 'template_id' => 52, 'key' => "he-IL", 'value' =>  "כתוב חזון שמושך את האנשים והלקוחות הנכונים. \n\nשם החברה:\n ##title## \n\nמידע על החברה:\n ##description## \n\nטון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 1881, 'template_id' => 52, 'key' => "hi-IN", 'value' =>  "एक दृष्टि लिखें जो सही लोगों और ग्राहकों को आकर्षित करे। \n\nकंपनी का नाम:\n ##title## \n\nकारखाना की जानकारी:\n ##description## \n\nपरिणाम का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 1882, 'template_id' => 52, 'key' => "hu-HU", 'value' =>  "Írjon olyan jövőképet, amely vonzza a megfelelő embereket és ügyfeleket. \n\nCégnév:\n ##title## \n\ncéginformáció:\n ##description## \n\nAz eredmény hangnemének kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1883, 'template_id' => 52, 'key' => "is-IS", 'value' =>  "Skrifaðu framtíðarsýn sem laðar að rétta fólkið og viðskiptavinina. \n\nnafn fyrirtækis:\n ##title## \n\nFyrirtækjaupplýsingar:\n ##description## \n\nRödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1884, 'template_id' => 52, 'key' => "id-ID", 'value' =>  "Tulis visi yang menarik orang dan pelanggan yang tepat. \n\nNama perusahaan:\n ##title## \n\ninformasi perusahaan:\n ##description## \n\nNada suara hasilnya harus:\n ##tone_language## \n\n"],

            ['id' => 1885, 'template_id' => 52, 'key' => "it-IT", 'value' =>  "Scrivi una visione che attiri le persone e i clienti giusti. \n\nNome della ditta:\n ##title## \n\nInformazioni sulla società:\n ##description## \n\nTono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 1886, 'template_id' => 52, 'key' => "ja-JP", 'value' =>  "適切な人材や顧客を惹きつけるビジョンを書きましょう。 \n\n会社名:\n ##title## \n\n企業情報:\n ##description## \n\n結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n"],

            ['id' => 1887, 'template_id' => 52, 'key' => "ko-KR", 'value' =>  "올바른 사람과 고객을 끌어들이는 비전을 작성하십시오. \n\n회사 이름:\n ##title## \n\n회사 정보:\n ##description## \n\n결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 1888, 'template_id' => 52, 'key' => "ms-MY", 'value' =>  "Tulis visi yang menarik orang dan pelanggan yang betul. \n\nnama syarikat:\n ##title## \n\nMaklumat Syarikat:\n ##description## \n\nNada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 1889, 'template_id' => 52, 'key' => "nb-NO", 'value' =>  "Skriv en visjon som tiltrekker de rette menneskene og kundene. \n\nselskapsnavn:\n ##title## \n\nfirmainformasjon:\n ##description## \n\nTonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 1890, 'template_id' => 52, 'key' => "pl-PL", 'value' =>  "Napisz wizję, która przyciąga właściwych ludzi i klientów. \n\nNazwa firmy:\n ##title## \n\ninformacje o firmie:\n ##description## \n\nTon głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 1891, 'template_id' => 52, 'key' => "pt-PT", 'value' =>  "Escreva uma visão que atraia as pessoas e os clientes certos. \n\nnome da empresa:\n ##title## \n\nInformações da Empresa:\n ##description## \n\nO tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1892, 'template_id' => 52, 'key' => "ru-RU", 'value' =>  "Напишите видение, которое привлечет нужных людей и клиентов. \n\nНазвание компании:\n ##title## \n\nИнформация о компании:\n ##description## \n\nТон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 1893, 'template_id' => 52, 'key' => "es-ES", 'value' =>  "Escribe una visión que atraiga a las personas y clientes adecuados. \n\nnombre de empresa:\n ##title## \n\nInformación de la empresa:\n ##description## \n\nEl tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 1894, 'template_id' => 52, 'key' => "sv-SE", 'value' =>  "Skriv en vision som attraherar rätt personer och kunder. \n\nFöretagsnamn:\n ##title## \n\nFöretagsinformation\n ##description## \n\nTonen i rösten för resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 1895, 'template_id' => 52, 'key' => "tr-TR", 'value' =>  "Doğru insanları ve müşterileri çeken bir vizyon yazın. \n\nFirma Adı:\n ##title## \n\nŞirket Bilgisi:\n ##description## \n\nSonucun ses tonu şöyle olmalıdır::\n ##tone_language## \n\n"],

            ['id' => 1896, 'template_id' => 52, 'key' => "pt-BR", 'value' =>  "Escreva uma visão que atraia as pessoas e os clientes certos. \n\nnome da empresa:\n ##title## \n\nInformações da Empresa:\n ##description## \n\nO tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1897, 'template_id' => 52, 'key' => "ro-RO", 'value' =>  "Scrieți o viziune care să atragă oamenii și clienții potriviți. \n\nNumele companiei:\n ##title## \n\nInformatiile Companiei:\n ##description## \n\nTonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1898, 'template_id' => 52, 'key' => "vi-VN", 'value' =>  "Viết một tầm nhìn thu hút đúng người và khách hàng. \n\nTên công ty:\n ##title## \n\nThông tin công ty:\n ##description## \n\nGiọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 1899, 'template_id' => 52, 'key' => "sw-KE", 'value' =>  "Andika maono yanayovutia watu na wateja sahihi. \n\njina la kampuni:\n ##title## \n\nTaarifa za Kampuni:\n ##description## \n\nToni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1900, 'template_id' => 52, 'key' => "sl-SI", 'value' =>  "Napišite vizijo, ki pritegne prave ljudi in stranke. \n\nime podjetja:\n ##title## \n\nInformacije o podjetju:\n ##description## \n\nTon glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 1901, 'template_id' => 52, 'key' => "th-TH", 'value' =>  "เขียนวิสัยทัศน์ที่ดึงดูดผู้คนและลูกค้าที่เหมาะสม \n\nชื่อ บริษัท:\n ##title## \n\nข้อมูล บริษัท:\n ##description## \n\nน้ำเสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1902, 'template_id' => 52, 'key' => "uk-UA", 'value' =>  "Напишіть бачення, яке приверне потрібних людей і клієнтів. \n\nНазва компанії:\n ##title## \n\nінформація про компанію:\n ##description## \n\nТон голосу результату повинен бути:\n ##tone_language## \n\n"],

            ['id' => 1903, 'template_id' => 52, 'key' => "lt-LT", 'value' =>  "Parašykite viziją, kuri pritraukia reikiamus žmones ir klientus. \n\nĮmonės pavadinimas:\n ##title## \n\nKompanijos informacija:\n ##description## \n\nTuri būti rezultato balso tonas:\n ##tone_language## \n\n"],

            ['id' => 1904, 'template_id' => 52, 'key' => "bg-BG", 'value' =>  "Напишете визия, която привлича правилните хора и клиенти. \n\nИме на фирмата:\n ##title## \n\nинформация за компанията:\n ##description## \n\nТонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1905, 'template_id' => 53, 'key' => "en-US", 'value' =>  "Write a clear and concise statement of the company's goals and purpose, Company Name:\n ##title## \n\nCompany Information:\n ##description## \n\n  Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 1906, 'template_id' => 53, 'key' => "ar-AE", 'value' =>  "اكتب بيانًا واضحًا وموجزًا ​​عن أهداف الشركة والغرض منها ، اسم الشركة:\n ##title## \n\nمعلومات الشركة:\n ##description## \n\n  يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 1907, 'template_id' => 53, 'key' => "cmn-CN", 'value' =>  "就公司的目標和宗旨寫出清晰簡潔的陳述，公司名稱:\n ##title## \n\n公司信息:\n ##description## \n\n  結果的語氣必須是:\n ##tone_language## \n\n"],

            ['id' => 1908, 'template_id' => 53, 'key' => "hr-HR", 'value' =>  "Napišite jasnu i konciznu izjavu o ciljevima i svrsi tvrtke, Naziv tvrtke:\n ##title## \n\nInformacije o tvrtki:\n ##description## \n\n  Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 1909, 'template_id' => 53, 'key' => "cs-CZ", 'value' =>  "Napište jasné a stručné prohlášení o cílech a účelu společnosti, Název společnosti:\n ##title## \n\nInformace o společnosti:\n ##description## \n\n  Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 1910, 'template_id' => 53, 'key' => "da-DK", 'value' =>  "Skriv en klar og kortfattet redegørelse for virksomhedens mål og formål, Firmanavn:\n ##title## \n\nVirksomhedsoplysninger:\n ##description## \n\n  Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 1911, 'template_id' => 53, 'key' => "nl-NL", 'value' =>  "Schrijf een duidelijke en beknopte verklaring van de doelstellingen en het doel van het bedrijf, bedrijfsnaam:\n ##title## \n\nbedrijfsinformatie:\n ##description## \n\n  De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1912, 'template_id' => 53, 'key' => "et-EE", 'value' =>  "Kirjutage selge ja lühidalt ettevõtte eesmärkide ja otstarbe kohta ettevõtte nimi:\n ##title## \n\nettevõtte info:\n ##description## \n\n  Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1913, 'template_id' => 53, 'key' => "fi-FI", 'value' =>  "Kirjoita selkeä ja ytimekäs selvitys yrityksen tavoitteista ja tarkoituksesta, Yrityksen nimi:\n ##title## \n\nYrityksen tiedot:\n ##description## \n\n  Äänesävyn tuloksen on oltava:\n ##tone_language## \n\n"],

            ['id' => 1914, 'template_id' => 53, 'key' => "fr-FR", 'value' =>  "Rédigez une déclaration claire et concise des objectifs et du but de l'entreprise, Nom de l'entreprise:\n ##title## \n\nInformations sur la société:\n ##description## \n\n  Le ton de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 1915, 'template_id' => 53, 'key' => "de-DE", 'value' =>  "Schreiben Sie eine klare und prägnante Erklärung zu den Zielen und Zwecken des Unternehmens sowie zum Firmennamen:\n ##title## \n\nFirmeninformation:\n ##description## \n\n  Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 1916, 'template_id' => 53, 'key' => "el-GR", 'value' =>  "Γράψτε μια σαφή και συνοπτική δήλωση των στόχων και του σκοπού της εταιρείας, Όνομα εταιρείας:\n ##title## \n\nΣτοιχεία της εταιρείας:\n ##description## \n\n  Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 1917, 'template_id' => 53, 'key' => "he-IL", 'value' =>  "כתבו הצהרה ברורה ותמציתית של מטרות החברה ומטרתה, שם חברה:\n ##title## \n\nמידע על החברה:\n ##description## \n\n  טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 1918, 'template_id' => 53, 'key' => "hi-IN", 'value' =>  "कंपनी के लक्ष्यों और उद्देश्य, कंपनी का नाम का स्पष्ट और संक्षिप्त विवरण लिखें:\n ##title## \n\nकारखाना की जानकारी:\n ##description## \n\n  परिणाम का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 1919, 'template_id' => 53, 'key' => "hu-HU", 'value' =>  "Írjon világos és tömör nyilatkozatot a vállalat céljairól és céljáról, Cégnév:\n ##title## \n\ncéginformáció:\n ##description## \n\n  Az eredmény hangnemének kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1920, 'template_id' => 53, 'key' => "is-IS", 'value' =>  "Skrifaðu skýra og hnitmiðaða yfirlýsingu um markmið og tilgang fyrirtækisins, Nafn fyrirtækis:\n ##title## \n\nFyrirtækjaupplýsingar:\n ##description## \n\n  Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1921, 'template_id' => 53, 'key' => "id-ID", 'value' =>  "Tulis pernyataan yang jelas dan ringkas tentang maksud dan tujuan perusahaan, Nama Perusahaan:\n ##title## \n\ninformasi perusahaan:\n ##description## \n\n  Nada suara hasilnya harus:\n ##tone_language## \n\n"],

            ['id' => 1922, 'template_id' => 53, 'key' => "it-IT", 'value' =>  "Scrivi una dichiarazione chiara e concisa degli obiettivi e dello scopo dell'azienda, nome dell'azienda:\n ##title## \n\nInformazioni sulla società:\n ##description## \n\n  Tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 1923, 'template_id' => 53, 'key' => "ja-JP", 'value' =>  "会社の目標と目的、会社名を明確かつ簡潔に記述します。:\n ##title## \n\n企業情報:\n ##description## \n\n  結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n"],

            ['id' => 1924, 'template_id' => 53, 'key' => "ko-KR", 'value' =>  "회사의 목표와 목적, 회사 이름을 명확하고 간결하게 작성하십시오.:\n ##title## \n\n회사 정보:\n ##description## \n\n  결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 1925, 'template_id' => 53, 'key' => "ms-MY", 'value' =>  "Tulis kenyataan yang jelas dan padat tentang matlamat dan tujuan syarikat, Nama Syarikat:\n ##title## \n\nMaklumat Syarikat:\n ##description## \n\n  Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 1926, 'template_id' => 53, 'key' => "nb-NO", 'value' =>  "Skriv en klar og kortfattet redegjørelse for selskapets mål og formål, Firmanavn:\n ##title## \n\nfirmainformasjon:\n ##description## \n\n  Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 1927, 'template_id' => 53, 'key' => "pl-PL", 'value' =>  "Napisz jasne i zwięzłe oświadczenie o celach i celu firmy, Nazwa firmy:\n ##title## \n\ninformacje o firmie:\n ##description## \n\n  Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 1928, 'template_id' => 53, 'key' => "pt-PT", 'value' =>  "Escreva uma declaração clara e concisa dos objetivos e propósito da empresa, Nome da empresa:\n ##title## \n\nInformações da Empresa:\n ##description## \n\n  O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1929, 'template_id' => 53, 'key' => "ru-RU", 'value' =>  "Напишите четкое и краткое изложение целей и задач компании, название компании:\n ##title## \n\nИнформация о компании:\n ##description## \n\n  Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 1930, 'template_id' => 53, 'key' => "es-ES", 'value' =>  "Escriba una declaración clara y concisa de los objetivos y el propósito de la empresa, Nombre de la empresa:\n ##title## \n\nInformación de la empresa:\n ##description## \n\n  El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],
                
            ['id' => 1931, 'template_id' => 53, 'key' => "sv-SE", 'value' =>  "Skriv en tydlig och kortfattad redogörelse för företagets mål och syfte, Företagsnamn:\n ##title## \n\nFöretagsinformation:\n ##description## \n\n  Tonen i rösten för resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 1932, 'template_id' => 53, 'key' => "tr-TR", 'value' =>  "Şirketin hedeflerini ve amacını, Şirket Adı'nı açık ve öz bir şekilde yazın:\n ##title## \n\nŞirket Bilgisi:\n ##description## \n\n  Sonucun ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 1933, 'template_id' => 53, 'key' => "pt-BR", 'value' =>  "Escreva uma declaração clara e concisa dos objetivos e propósito da empresa, Nome da empresa:\n ##title## \n\nInformações da Empresa:\n ##description## \n\n  O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1934, 'template_id' => 53, 'key' => "ro-RO", 'value' =>  "Scrieți o declarație clară și concisă a obiectivelor și scopului companiei, Numele companiei:\n ##title## \n\nInformatiile Companiei:\n ##description## \n\n  Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1935, 'template_id' => 53, 'key' => "vi-VN", 'value' =>  "Viết một tuyên bố rõ ràng và súc tích về mục tiêu và mục đích của công ty, Tên công ty:\n ##title## \n\nThông tin công ty :\n ##description## \n\n  Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 1936, 'template_id' => 53, 'key' => "sw-KE", 'value' =>  "Andika taarifa iliyo wazi na fupi ya malengo na madhumuni ya kampuni, Jina la Kampuni:\n ##title## \n\nTaarifa za Kampuni:\n ##description## \n\n  Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1937, 'template_id' => 53, 'key' => "sl-SI", 'value' =>  "Napišite jasno in jedrnato izjavo o ciljih in namenu podjetja, ime podjetja:\n ##title## \n\nInformacije o podjetju:\n ##description## \n\n  Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 1938, 'template_id' => 53, 'key' => "th-TH", 'value' =>  "เขียนข้อความที่ชัดเจนและกระชับเกี่ยวกับเป้าหมายและวัตถุประสงค์ของบริษัท ชื่อบริษัท:\n ##title## \n\nข้อมูล บริษัท:\n ##description## \n\n  น้ำเสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1939, 'template_id' => 53, 'key' => "uk-UA", 'value' =>  "Напишіть чітке та стисле викладення цілей та мети компанії, назву компанії:\n ##title## \n\nінформація про компанію:\n ##description## \n\n  Тон голосу результату повинен бути:\n ##tone_language## \n\n"],

            ['id' => 1940, 'template_id' => 53, 'key' => "lt-LT", 'value' =>  "Aiškiai ir glaustai parašykite įmonės tikslus ir paskirtį, Įmonės pavadinimas:\n ##title## \n\nKompanijos informacija:\n ##description## \n\n  Turi būti rezultato balso tonas:\n ##tone_language## \n\n"],

            ['id' => 1941, 'template_id' => 53, 'key' => "bg-BG", 'value' =>  "Напишете ясно и кратко изложение на целите и предназначението на компанията, Име на компанията:\n ##title## \n\nинформация за компанията:\n ##description## \n\n  Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1942, 'template_id' => 54, 'key' => "en-US", 'value' =>  "Write a short and cool bio for ##platform## \n\nCompany Name:\n ##title## \n\nCompany Information:\n ##description## \n\n  Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 1943, 'template_id' => 54, 'key' => "ar-AE", 'value' =>  "اكتب سيرته الذاتية قصيرة وباردة ##platform## \n\nاسم الشركة:\n ##title## \n\nمعلومات الشركة:\n ##description## \n\n  Tالوحيدة من صوت النتيجة يجب أن تكون:\n ##tone_language## \n\n"],

            ['id' => 1944, 'template_id' => 54, 'key' => "cmn-CN", 'value' =>  "給你寫個簡短又酷的生物 ##platform## \n\n公司名稱:\n ##title## \n\n公司資訊:\n ##description## \n\n  必須要有聲音的聲音:\n ##tone_language## \n\n"],

            ['id' => 1945, 'template_id' => 54, 'key' => "hr-HR", 'value' =>  "Napiši kratku i cool biografije za ##platform## \n\nIme poduzeća:\n ##title## \n\nInformacije o tvrtki:\n ##description## \n\n  Ton glasa za rezultat mora biti:\n ##tone_language## \n\n"],

            ['id' => 1946, 'template_id' => 54, 'key' => "cs-CZ", 'value' =>  "Napište krátký a cool bio pro ##platform## \n\nNázev společnosti:\n ##title## \n\nInformace o společnosti:\n ##description## \n\n  Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 1947, 'template_id' => 54, 'key' => "da-DK", 'value' =>  "Skrive en kort og sej biografi til ##platform## \n\nFirmanavn:\n ##title## \n\nVirksomhedsoplysninger:\n ##description## \n\n  Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 1948, 'template_id' => 54, 'key' => "nl-NL", 'value' =>  "Schrijf een korte en koele bio voor ##platform## \n\nBedrijfsnaam:\n ##title## \n\nbedrijfsinformatie:\n ##description## \n\n  De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1949, 'template_id' => 54, 'key' => "et-EE", 'value' =>  "Kirjuta lühike ja jahe bio ##platform## \n\nÄriühingu nimi:\n ##title## \n\nettevõtte info:\n ##description## \n\n  Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1950, 'template_id' => 54, 'key' => "fi-FI", 'value' =>  "Kirjoita lyhyt ja viileä ##platform## \n\nYrityksen nimi:\n ##title## \n\nYrityksen tiedot:\n ##description## \n\n  Tuloksen äänen on oltava:\n ##tone_language## \n\n"],

            ['id' => 1951, 'template_id' => 54, 'key' => "fr-FR", 'value' =>  "Écrivez une biographie courte et fraîche pour ##platform## \n\nNom de l'entreprise:\n ##title## \n\nInformations sur la société:\n ##description## \n\n  Tone de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 1952, 'template_id' => 54, 'key' => "de-DE", 'value' =>  "Schreiben Sie eine kurze und coole Bio für ##platform## \n\nFirmenname:\n ##title## \n\nFirmeninformation:\n ##description## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n"],

            ['id' => 1953, 'template_id' => 54, 'key' => "el-GR", 'value' =>  "Γράψτε ένα σύντομο και δροσερό βιογραφικό για ##platform## \n\nΌνομα εταιρείας::\n ##title## \n\nΣτοιχεία της εταιρείας:\n ##description## \n\n  Η φωνή του αποτελέσματος πρέπει να είναι ...:\n ##tone_language## \n\n"],

            ['id' => 1954, 'template_id' => 54, 'key' => "he-IL", 'value' =>  "תכתוב קורות חיים קצרים ומגניבים. ##platform## \n\nשם חברה:\n ##title## \n\nמידע על החברה:\n ##description## \n\n  טון הדיבור של הפסקאות חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 1955, 'template_id' => 54, 'key' => "hi-IN", 'value' =>  "के लिए एक छोटा और शांत जैव लिखें ##platform## \n\nकंपनी का नाम:\n ##title## \n\nकंपनी की जानकारी:\n ##description## \n\n  परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n"],

            ['id' => 1956, 'template_id' => 54, 'key' => "hu-HU", 'value' =>  "Írj egy rövid és hűvös bioanyagot ##platform## \n\nCégnév:\n ##title## \n\ncéginformáció:\n ##description## \n\n  Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1957, 'template_id' => 54, 'key' => "is-IS", 'value' =>  "Skrifaðu stutta og flotta ævisögu fyrir ##platform## \n\nnafn fyrirtækis:\n ##title## \n\nFyrirtækjaupplýsingar:\n ##description## \n\n  Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1958, 'template_id' => 54, 'key' => "id-ID", 'value' =>  "Tulis bio singkat dan keren untuk ##platform## \n\nNama perusahaan:\n ##title## \n\ninformasi perusahaan:\n ##description## \n\n  Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n"],

            ['id' => 1959, 'template_id' => 54, 'key' => "it-IT", 'value' =>  "Scrivi un bio breve e cool per ##platform## \n\nNome della ditta:\n ##title## \n\nInformazioni sulla società:\n ##description## \n\n  Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 1960, 'template_id' => 54, 'key' => "ja-JP", 'value' =>  "短く、クールな生体を書いてください ##platform## \n\n会社名:\n ##title## \n\n企業情報:\n ##description## \n\n  結果の声のトーンは、:\n ##tone_language## \n\n"],

            ['id' => 1961, 'template_id' => 54, 'key' => "ko-KR", 'value' =>  "짧고 멋진 약력을 작성하십시오. ##platform## \n\n회사 이름:\n ##title## \n\n회사 정보:\n ##description## \n\n  결과의 음성 중 하나는 반드시 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 1962, 'template_id' => 54, 'key' => "ms-MY", 'value' =>  "Tulis bio ringkas dan keren untuk ##platform## \n\nnama syarikat:\n ##title## \n\nMaklumat Syarikat:\n ##description## \n\n  Nada suara hasilnya mesti.:\n ##tone_language## \n\n"],

            ['id' => 1963, 'template_id' => 54, 'key' => "nb-NO", 'value' =>  "Skriv en kort og kul bio for ##platform## \n\nselskapsnavn:\n ##title## \n\nfirmainformasjon:\n ##description## \n\n  Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 1964, 'template_id' => 54, 'key' => "pl-PL", 'value' =>  "Napisz krótką i fajną biografię dla ##platform## \n\nNazwa firmy:\n ##title## \n\ninformacje o firmie:\n ##description## \n\n  Ton głosu w wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 1965, 'template_id' => 54, 'key' => "pt-PT", 'value' =>  "Escreva uma biografia curta e legal para ##platform## \n\nnome da empresa:\n ##title## \n\nInformações da Empresa:\n ##description## \n\n  Tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1966, 'template_id' => 54, 'key' => "ru-RU", 'value' =>  "Напишите короткую и интересную биографию для ##platform## \n\nНазвание компании:\n ##title## \n\nИнформация о компании:\n ##description## \n\n  Тон голоса результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 1967, 'template_id' => 54, 'key' => "es-ES", 'value' =>  "Escriba una breve y fresca biografía para ##platform## \n\nnombre de empresa:\n ##title## \n\nInformación de la empresa:\n ##description## \n\n  El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 1968, 'template_id' => 54, 'key' => "sv-SE", 'value' =>  "Skriv en kort och cool bio för ##platform## \n\nFöretagsnamn:\n ##title## \n\nFöretagsinformation:\n ##description## \n\n  Ton av resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 1969, 'template_id' => 54, 'key' => "tr-TR", 'value' =>  "için kısa ve havalı bir biyografi yazın ##platform## \n\nFirma Adı:\n ##title## \n\nŞirket Bilgisi:\n ##description## \n\n  Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n"],

            ['id' => 1970, 'template_id' => 54, 'key' => "pt-BR", 'value' =>  "Escreva uma biografia curta e legal para ##platform## \n\nnome da empresa:\n ##title## \n\nInformações da Empresa:\n ##description## \n\n  Tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 1971, 'template_id' => 54, 'key' => "ro-RO", 'value' =>  "Scrie o biografie scurtă și cool pentru ##platform## \n\nNumele companiei:\n ##title## \n\nInformatiile Companiei:\n ##description## \n\n  Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 1972, 'template_id' => 54, 'key' => "vi-VN", 'value' =>  "Viết một tiểu sử ngắn và thú vị cho ##platform## \n\nTên công ty:\n ##title## \n\nThông tin công ty:\n ##description## \n\n  Giọng nói của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 1973, 'template_id' => 54, 'key' => "sw-KE", 'value' =>  "Andika wasifu mfupi na mzuri kwa ##platform## \n\njina la kampuni:\n ##title## \n\nTaarifa za Kampuni:\n ##description## \n\n  Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 1974, 'template_id' => 54, 'key' => "sl-SI", 'value' =>  "Napišite kratek in kul življenjepis za ##platform## \n\nime podjetja:\n ##title## \n\nInformacije o podjetju:\n ##description## \n\n  Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 1975, 'template_id' => 54, 'key' => "th-TH", 'value' =>  "เขียนชีวประวัติสั้น ๆ และน่าสนใจสำหรับ ##platform## \n\nชื่อ บริษัท:\n ##title## \n\nข้อมูล บริษัท:\n ##description## \n\n  เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 1976, 'template_id' => 54, 'key' => "uk-UA", 'value' =>  "Напишіть коротку та круту біографію для ##platform## \n\nНазва компанії:\n ##title## \n\nінформація про компанію:\n ##description## \n\n  Тон голосу результату має бути:\n ##tone_language## \n\n"],

            ['id' => 1977, 'template_id' => 54, 'key' => "lt-LT", 'value' =>  "Parašykite trumpą ir šaunią biografiją ##platform## \n\nĮmonės pavadinimas:\n ##title## \n\nKompanijos informacija:\n ##description## \n\n  Balso tonas rezultatas turi būti:\n ##tone_language## \n\n"],

            ['id' => 1978, 'template_id' => 54, 'key' => "bg-BG", 'value' =>  "Напишете кратка и готина биография за ##platform## \n\nИме на фирмата:\n ##title## \n\nинформация за компанията:\n ##description## \n\n  Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 1979, 'template_id' => 55, 'key' => "en-US", 'value' => "Write an engaging email about:\n\n ##description## \n\n Recipient:\n ##recipient## \n\n Recipient Position:\n ##recipient_position## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 1980, 'template_id' => 55, 'key' => "ar-AE", 'value' => "اكتب بريدًا إلكترونيًا جذابًا عنه:\n\n ##description## \n\n متلقي:\n ##recipient## \n\n موقف المستلم:\n ##recipient_position## \n\n Tالوحيدة من صوت النتيجة يجب أن تكون:\n ##tone_language## \n\n"],

            ['id' => 1981, 'template_id' => 55, 'key' => "cmn-CN", 'value' => "撰寫關於的吸引人的電子郵件:\n\n ##description## \n\n 收件者:\n ##recipient## \n\n 收件者位置:\n ##recipient_position## \n\n 必須要有聲音的聲音:\n ##tone_language## \n\n"],

            ['id' => 1982, 'template_id' => 55, 'key' => "hr-HR", 'value' => "Napišite zanimljivu e-poruku o:\n\n ##description## \n\n Primatelj:\n ##recipient## \n\n Položaj primatelja:\n ##recipient_position## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n"],

            ['id' => 1983, 'template_id' => 55, 'key' => "cs-CZ", 'value' => "Napište zajímavý e-mail o:\n\n ##description## \n\n Příjemce:\n ##recipient## \n\n Pozice příjemce:\n ##recipient_position## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 1984, 'template_id' => 55, 'key' => "da-DK", 'value' => "Skriv en engagerende e-mail om:\n\n ##description## \n\n Modtager:\n ##recipient## \n\n Modtagerposition:\n ##recipient_position## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 1985, 'template_id' => 55, 'key' => "nl-NL", 'value' => "Schrijf een boeiende e-mail over:\n\n ##description## \n\n Ontvanger:\n ##recipient## \n\n Positie van de ontvanger:\n ##recipient_position## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 1986, 'template_id' => 55, 'key' => "et-EE", 'value' => "Kirjutage kaasahaarav e-kiri:\n\n ##description## \n\n Saaja:\n ##recipient## \n\n Saaja positsioon:\n ##recipient_position## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 1987, 'template_id' => 55, 'key' => "fi-FI", 'value' => "Kirjoita kiinnostava sähköposti aiheesta:\n\n ##description## \n\n Vastaanottaja:\n ##recipient## \n\n Vastaanottajan asema:\n ##recipient_position## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n"],

            ['id' => 1988, 'template_id' => 55, 'key' => "fr-FR", 'value' => "Rédigez un e-mail engageant à propos de:\n\n ##description## \n\n Destinataire:\n ##recipient## \n\n Poste du destinataire:\n ##recipient_position## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 1989, 'template_id' => 55, 'key' => "de-DE", 'value' => "Schreiben Sie eine ansprechende E-Mail über:\n\n ##description## \n\n Empfänger:\n ##recipient## \n\n Empfängerposition:\n ##recipient_position## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n"],

            ['id' => 1990, 'template_id' => 55, 'key' => "el-GR", 'value' => "Γράψτε ένα συναρπαστικό email για:\n\n ##description## \n\n Παραλήπτης:\n ##recipient## \n\n Θέση Παραλήπτη:\n ##recipient_position## \n\n Η φωνή του αποτελέσματος πρέπει να είναι ...:\n ##tone_language## \n\n"],

            ['id' => 1991, 'template_id' => 55, 'key' => "he-IL", 'value' => "כתוב אימייל מרתק על:\n\n ##description## \n\n מקבל:\n ##recipient## \n\n עמדת נמען:\n ##recipient_position## \n\n טון הדיבור של הפסקאות חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 1992, 'template_id' => 55, 'key' => "hi-IN", 'value' => "के बारे में एक आकर्षक ईमेल लिखें:\n\n ##description## \n\n प्राप्तकर्ता:\n ##recipient## \n\n प्राप्तकर्ता स्थिति:\n ##recipient_position## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n"],

            ['id' => 1993, 'template_id' => 55, 'key' => "hu-HU", 'value' => "Írjon lebilincselő e-mailt erről:\n\n ##description## \n\n Befogadó:\n ##recipient## \n\n Címzett pozíciója:\n ##recipient_position## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n"],

            ['id' => 1994, 'template_id' => 55, 'key' => "is-IS", 'value' => "Skrifaðu grípandi tölvupóst um:\n\n ##description## \n\n Viðtakandi:\n ##recipient## \n\n Staða viðtakanda:\n ##recipient_position## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 1995, 'template_id' => 55, 'key' => "id-ID", 'value' => "Tulis email yang menarik tentang:\n\n ##description## \n\n Penerima:\n ##recipient## \n\n Posisi Penerima:\n ##recipient_position## \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n"],

            ['id' => 1996, 'template_id' => 55, 'key' => "it-IT", 'value' => "Scrivi un'e-mail coinvolgente su:\n\n ##description## \n\n Destinatario:\n ##recipient## \n\n Posizione del destinatario:\n ##recipient_position## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 1997, 'template_id' => 55, 'key' => "ja-JP", 'value' => "～について魅力的なメールを書く:\n\n ##description## \n\n 受信者:\n ##recipient## \n\n 受信者の位置:\n ##recipient_position## \n\n 結果の声のトーンは、:\n ##tone_language## \n\n"],

            ['id' => 1998, 'template_id' => 55, 'key' => "ko-KR", 'value' => "에 대한 매력적인 이메일 작성:\n\n ##description## \n\n 받는 사람:\n ##recipient## \n\n 받는 사람 위치:\n ##recipient_position## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 1999, 'template_id' => 55, 'key' => "ms-MY", 'value' => "Tulis e-mel yang menarik tentang:\n\n ##description## \n\n Penerima:\n ##recipient## \n\n Kedudukan Penerima:\n ##recipient_position## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n"],

            ['id' => 2000, 'template_id' => 55, 'key' => "nb-NO", 'value' => "Skriv en engasjerende e-post om:\n\n ##description## \n\n Mottaker:\n ##recipient## \n\n Mottakerposisjon:\n ##recipient_position## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 2001, 'template_id' => 55, 'key' => "pl-PL", 'value' => "Napisz angażującego e-maila nt:\n\n ##description## \n\n Odbiorca:\n ##recipient## \n\n Pozycja odbiorcy:\n ##recipient_position## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 2002, 'template_id' => 55, 'key' => "pt-PT", 'value' => "Escreva um e-mail atraente sobre:\n\n ##description## \n\n Destinatário:\n ##recipient## \n\n Posição do Destinatário:\n ##recipient_position## \n\n Tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2003, 'template_id' => 55, 'key' => "ru-RU", 'value' => "Напишите увлекательное письмо о:\n\n ##description## \n\n Получатель:\n ##recipient## \n\n Позиция получателя:\n ##recipient_position## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 2004, 'template_id' => 55, 'key' => "es-ES", 'value' => "Escriba un correo electrónico atractivo sobre:\n\n ##description## \n\n Recipiente:\n ##recipient## \n\n Posición del destinatario:\n ##recipient_position## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 2005, 'template_id' => 55, 'key' => "sv-SE", 'value' => "Skriv ett engagerande mejl om:\n\n ##description## \n\n Mottagare:\n ##recipient## \n\n Mottagarens position:\n ##recipient_position## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 2006, 'template_id' => 55, 'key' => "tr-TR", 'value' => "hakkında ilgi çekici bir e-posta yazın:\n\n ##description## \n\n alıcı:\n ##recipient## \n\n Alıcı Pozisyonu:\n ##recipient_position## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n"],

            ['id' => 2007, 'template_id' => 55, 'key' => "pt-BR", 'value' => "Escreva um e-mail atraente sobre:\n\n ##description## \n\n Destinatário:\n ##recipient## \n\n Recipient Position:\n ##recipient_position## \n\n Tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2008, 'template_id' => 55, 'key' => "ro-RO", 'value' => "Scrieți un e-mail captivant despre:\n\n ##description## \n\n Destinatar:\n ##recipient## \n\n Poziția destinatarului:\n ##recipient_position## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 2009, 'template_id' => 55, 'key' => "vi-VN", 'value' => "Viết một email hấp dẫn về:\n\n ##description## \n\n Người nhận:\n ##recipient## \n\n Vị trí người nhận:\n ##recipient_position## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 2010, 'template_id' => 55, 'key' => "sw-KE", 'value' => "Andika barua pepe ya kuvutia kuhusu:\n\n ##description## \n\n Mpokeaji:\n ##recipient## \n\n Nafasi ya Mpokeaji:\n ##recipient_position## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 2011, 'template_id' => 55, 'key' => "sl-SI", 'value' => "Napišite privlačno e-poštno sporočilo o:\n\n ##description## \n\n Prejemnik:\n ##recipient## \n\n Položaj prejemnika:\n ##recipient_position## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2012, 'template_id' => 55, 'key' => "th-TH", 'value' => "เขียนอีเมลที่น่าสนใจเกี่ยวกับ:\n\n ##description## \n\n ผู้รับ:\n ##recipient## \n\n ตำแหน่งผู้รับ:\n ##recipient_position## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 2013, 'template_id' => 55, 'key' => "uk-UA", 'value' => "Напишіть цікавий електронний лист про:\n\n ##description## \n\n одержувач:\n ##recipient## \n\n Посада отримувача:\n ##recipient_position## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n"],

            ['id' => 2014, 'template_id' => 55, 'key' => "lt-LT", 'value' => "Parašykite patrauklų el. laišką apie:\n\n ##description## \n\n Gavėjas:\n ##recipient## \n\n Gavėjo padėtis:\n ##recipient_position## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n"],

            ['id' => 2015, 'template_id' => 55, 'key' => "bg-BG", 'value' => "Напишете увлекателен имейл за:\n\n ##description## \n\n Получател:\n ##recipient## \n\n Позиция на получател:\n ##recipient_position## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 2016, 'template_id' => 56, 'key' => "en-US", 'value' => "Write an engaging email about:\n\n ##description## \n\n From:\n ##from## n\n To:\n ##to## \n\n Main Goal of this email:\n ##goal## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 2017, 'template_id' => 56, 'key' => "ar-AE", 'value' => "اكتب بريدًا إلكترونيًا جذابًا حول:\n\n ##description## \n\n من:\n ##from## n\n ل:\n ##to## \n\n الهدف الرئيسي لهذا البريد الإلكتروني:\n ##goal## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 2018, 'template_id' => 56, 'key' => "cmn-CN", 'value' => "写一封引人入胜的电子邮件：\n\n ##description## \n\n 从：\n ##from## n\n 到：\n ##to## \n\n 这封电子邮件的主要目标：\n ##goal## \n\n 结果的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 2019, 'template_id' => 56, 'key' => "hr-HR", 'value' => "Napišite zanimljivu e-poruku o:\n\n ##description## \n\n Iz:\n ##from## n\n Do:\n ##to## \n\n Glavni cilj ove e-pošte:\n ##goal## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2020, 'template_id' => 56, 'key' => "cs-CZ", 'value' => "Napište zajímavý e-mail o:\n\n ##description## \n\n Z:\n ##from## n\n Na:\n ##to## \n\n Hlavní cíl tohoto e-mailu:\n ##goal## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 2021, 'template_id' => 56, 'key' => "da-DK", 'value' => "Skriv en engagerende e-mail om:\n\n ##description## \n\n Fra:\n ##from## n\n Til:\n ##to## \n\n Hovedformålet med denne e-mail:\n ##goal## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 2022, 'template_id' => 56, 'key' => "nl-NL", 'value' => "Schrijf een boeiende e-mail over:\n\n ##description## \n\n Van:\n ##from## n\n Naar:\n ##to## \n\n Hoofddoel van deze e-mail:\n ##goal## \n\n Tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 2023, 'template_id' => 56, 'key' => "et-EE", 'value' => "Kirjutage kaasahaarav e-kiri teemal:\n\n ##description## \n\n Alates:\n ##from## n\n Saaja:\n ##to## \n\n Selle meili peamine eesmärk:\n ##goal## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 2024, 'template_id' => 56, 'key' => "fi-FI", 'value' => "Kirjoita kiinnostava sähköposti aiheesta:\n\n ##description## \n\n Lähettäjä:\n ##from## n\n Vastaanottaja:\n ##to## \n\n Tämän sähköpostin päätavoite:\n ##goal## \n\n Tuloksen äänensävyn tulee olla:\n ##tone_language## \n\n"],

            ['id' => 2025, 'template_id' => 56, 'key' => "fr-FR", 'value' => "Rédigez un e-mail engageant sur:\n\n ##description## \n\n Depuis:\n ##from## n\n Pour:\n ##to## \n\n Objectif principal de cet e-mail:\n ##goal## \n\n Le ton de voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 2026, 'template_id' => 56, 'key' => "de-DE", 'value' => "Schreiben Sie eine ansprechende E-Mail über:\n\n ##description## \n\n Aus:\n ##from## n\n Zu:\n ##to## \n\n Hauptziel dieser E-Mail:\n ##goal## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 2027, 'template_id' => 56, 'key' => "el-GR", 'value' => "Γράψτε ένα συναρπαστικό email σχετικά με:\n\n ##description## \n\n Από:\n ##from## n\n Προς την:\n ##to## \n\n Κύριος στόχος αυτού του email:\n ##goal## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 2028, 'template_id' => 56, 'key' => "he-IL", 'value' => "כתוב אימייל מרתק על:\n\n ##description## \n\n מ:\n ##from## n\n ל:\n ##to## \n\n המטרה העיקרית של האימייל הזה:\n ##goal## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 2029, 'template_id' => 56, 'key' => "hi-IN", 'value' => "इसके बारे में एक आकर्षक ईमेल लिखें:\n\n ##description## \n\n से:\n ##from## n\n को:\n ##to## \n\n इस ईमेल का मुख्य लक्ष्य:\n ##goal## \n\n परिणाम की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 2030, 'template_id' => 56, 'key' => "hu-HU", 'value' => "Írjon lebilincselő e-mailt a következőkről:\n\n ##description## \n\n Tól től:\n ##from## n\n Nak nek:\n ##to## \n\n Ennek az e-mailnek a fő célja:\n ##goal## \n\n Az eredmény hangnemének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 2031, 'template_id' => 56, 'key' => "is-IS", 'value' => "Skrifaðu grípandi tölvupóst um:\n\n ##description## \n\n Frá:\n ##from## n\n Til:\n ##to## \n\n Meginmarkmið þessa tölvupósts:\n ##goal## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 2032, 'template_id' => 56, 'key' => "id-ID", 'value' => "Tulis email yang menarik tentang:\n\n ##description## \n\n Dari:\n ##from## n\n Ke:\n ##to## \n\n Tujuan Utama dari email ini:\n ##goal## \n\n Nada suara hasil harus:\n ##tone_language## \n\n"],

            ['id' => 2033, 'template_id' => 56, 'key' => "it-IT", 'value' => "Scrivi un'email coinvolgente su:\n\n ##description## \n\n Da:\n ##from## n\n A:\n ##to## \n\n Obiettivo principale di questa email:\n ##goal## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 2034, 'template_id' => 56, 'key' => "ja-JP", 'value' => "以下について魅力的なメールを書きましょう:\n\n ##description## \n\n から:\n ##from## n\n に:\n ##to## \n\n このメールの主な目的:\n ##goal## \n\n 結果の口調は次のようになります:\n ##tone_language## \n\n"],

            ['id' => 2035, 'template_id' => 56, 'key' => "ko-KR", 'value' => "다음에 대해 관심을 끄는 이메일을 작성하세요:\n\n ##description## \n\n 에서:\n ##from## n\n 에게:\n ##to## \n\n 이 이메일의 주요 목표:\n ##goal## \n\n 결과의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 2036, 'template_id' => 56, 'key' => "ms-MY", 'value' => "Tulis e-mel yang menarik tentang:\n\n ##description## \n\n Daripada:\n ##from## n\n Kepada:\n ##to## \n\n Matlamat Utama e-mel ini:\n ##goal## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 2037, 'template_id' => 56, 'key' => "nb-NO", 'value' => "Skriv en engasjerende e-post om:\n\n ##description## \n\n Fra:\n ##from## n\n Til:\n ##to## \n\n Hovedmålet med denne e-posten:\n ##goal## \n\n Stemmetonen for resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 2038, 'template_id' => 56, 'key' => "pl-PL", 'value' => "Napisz angażującego e-maila na temat:\n\n ##description## \n\n Z:\n ##from## n\n Do:\n ##to## \n\n Główny cel tego e-maila:\n ##goal## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 2039, 'template_id' => 56, 'key' => "pt-PT", 'value' => "Escreva um e-mail atraente sobre:\n\n ##description## \n\n De:\n ##from## n\n Para:\n ##to## \n\n Objetivo principal deste e-mail:\n ##goal## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2040, 'template_id' => 56, 'key' => "ru-RU", 'value' => "Напишите увлекательное письмо о:\n\n ##description## \n\n От:\n ##from## n\n К:\n ##to## \n\n Основная цель этого письма:\n ##goal## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 2041, 'template_id' => 56, 'key' => "es-ES", 'value' => "Escriba un correo electrónico atractivo sobre:\n\n ##description## \n\n De:\n ##from## n\n A:\n ##to## \n\n Objetivo principal de este correo electrónico:\n ##goal## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 2042, 'template_id' => 56, 'key' => "sv-SE", 'value' => "Skriv ett engagerande mejl om:\n\n ##description## \n\n Från:\n ##from## n\n Till:\n ##to## \n\n Huvudmålet med detta e-postmeddelande:\n ##goal## \n\n Tonen i resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 2043, 'template_id' => 56, 'key' => "tr-TR", 'value' => "Şunlar hakkında ilgi çekici bir e-posta yazın:\n\n ##description## \n\n İtibaren:\n ##from## n\n İle:\n ##to## \n\n Bu e-postanın Ana Hedefi:\n ##goal## \n\n Sonucun ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 2044, 'template_id' => 56, 'key' => "pt-BR", 'value' => "Escreva um e-mail envolvente sobre:\n\n ##description## \n\n De:\n ##from## n\n Para:\n ##to## \n\n Objetivo principal deste e-mail:\n ##goal## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2045, 'template_id' => 56, 'key' => "ro-RO", 'value' => "Scrieți un e-mail captivant despre:\n\n ##description## \n\n Din:\n ##from## n\n La:\n ##to## \n\n Scopul principal al acestui e-mail:\n ##goal## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 2046, 'template_id' => 56, 'key' => "vi-VN", 'value' => "Viết một email hấp dẫn về:\n\n ##description## \n\n Từ:\n ##from## n\n ĐẾN:\n ##to## \n\n Mục tiêu chính của email này:\n ##goal## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 2047, 'template_id' => 56, 'key' => "sw-KE", 'value' => "Andika barua pepe ya kuvutia kuhusu:\n\n ##description## \n\n Kutoka:\n ##from## n\n Kwa:\n ##to## \n\n Lengo Kuu la barua pepe hii:\n ##goal## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 2048, 'template_id' => 56, 'key' => "sl-SI", 'value' => "Napišite privlačno e-pošto o:\n\n ##description## \n\n Od:\n ##from## n\n Za:\n ##to## \n\n Glavni cilj tega e-poštnega sporočila:\n ##goal## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2049, 'template_id' => 56, 'key' => "th-TH", 'value' => "เขียนอีเมลที่น่าสนใจเกี่ยวกับ:\n\n ##description## \n\n จาก:\n ##from## n\n ถึง:\n ##to## \n\n เป้าหมายหลักของอีเมลนี้:\n ##goal## \n\n โทนเสียงของผลลัพธ์ต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 2050, 'template_id' => 56, 'key' => "uk-UA", 'value' => "Напишіть цікавий електронний лист про:\n\n ##description## \n\n Від:\n ##from## n\n до:\n ##to## \n\n Основна мета цього листа:\n ##goal## \n\n Тон голосу результату повинен бути таким:\n ##tone_language## \n\n"],

            ['id' => 2051, 'template_id' => 56, 'key' => "lt-LT", 'value' => "Parašykite patrauklų el. laišką apie:\n\n ##description## \n\n Iš:\n ##from## n\n Į: Kam:\n ##to## \n\n Pagrindinis šio el. laiško tikslas:\n ##goal## \n\n Rezultato balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 2052, 'template_id' => 56, 'key' => "bg-BG", 'value' => "Напишете увлекателен имейл за:\n\n ##description## \n\n От:\n ##from## n\n До:\n ##to## \n\n Основна цел на този имейл:\n ##goal## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 2053, 'template_id' => 57, 'key' => "en-US", 'value' => "Write 10 catchy email subject lines for this product:\n\n ##title## \n\nProduct Description:\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 2054, 'template_id' => 57, 'key' => "ar-AE", 'value' => "اكتب 10 سطور جذابة لموضوع البريد الإلكتروني لهذا المنتج:\n\n ##title## \n\nوصف المنتج:\n ##description## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 2055, 'template_id' => 57, 'key' => "cmn-CN", 'value' => "为该产品写 10 个醒目的电子邮件主题行：\n\n ##title## \n\n产品描述：\n ##description## \n\n 结果的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 2056, 'template_id' => 57, 'key' => "hr-HR", 'value' => "Napišite 10 privlačnih redaka predmeta e-pošte za ovaj proizvod:\n\n ##title## \n\nOpis proizvoda:\n ##description## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2057, 'template_id' => 57, 'key' => "cs-CZ", 'value' => "Napište 10 chytlavých předmětů e-mailu pro tento produkt:\n\n ##title## \n\nPopis výrobku:\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 2058, 'template_id' => 57, 'key' => "da-DK", 'value' => "Skriv 10 fængende e-mail-emnelinjer for dette produkt:\n\n ##title## \n\nProdukt beskrivelse:\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 2059, 'template_id' => 57, 'key' => "nl-NL", 'value' => "Schrijf 10 pakkende e-mailonderwerpregels voor dit product:\n\n ##title## \n\nProduct beschrijving:\n ##description## \n\n Tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 2060, 'template_id' => 57, 'key' => "et-EE", 'value' => "Kirjutage selle toote kohta 10 meeldejäävat meili teemarida:\n\n ##title## \n\nTootekirjeldus:\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 2061, 'template_id' => 57, 'key' => "fi-FI", 'value' => "Kirjoita tälle tuotteelle 10 tarttuvaa sähköpostin aiheriviä:\n\n ##title## \n\nTuotteen Kuvaus:\n ##description## \n\n Tuloksen äänensävyn tulee olla:\n ##tone_language## \n\n"],

            ['id' => 2062, 'template_id' => 57, 'key' => "fr-FR", 'value' => "Rédigez 10 lignes d'objet accrocheuses pour ce produit:\n\n ##title## \n\nDescription du produit:\n ##description## \n\n Le ton de voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 2063, 'template_id' => 57, 'key' => "de-DE", 'value' => "Schreiben Sie 10 einprägsame E-Mail-Betreffzeilen für dieses Produkt:\n\n ##title## \n\nProduktbeschreibung:\n ##description## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 2064, 'template_id' => 57, 'key' => "el-GR", 'value' => "Γράψτε 10 συναρπαστικές γραμμές θέματος email για αυτό το προϊόν:\n\n ##title## \n\nΠεριγραφή προϊόντος:\n ##description## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 2065, 'template_id' => 57, 'key' => "he-IL", 'value' => "כתוב 10 שורות נושא קליטות בדוא ל עבור מוצר זה:\n\n ##title## \n\nתיאור מוצר:\n ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 2066, 'template_id' => 57, 'key' => "hi-IN", 'value' => "इस उत्पाद के लिए 10 आकर्षक ईमेल विषय पंक्तियाँ लिखें:\n\n ##title## \n\nउत्पाद वर्णन:\n ##description## \n\n परिणाम की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 2067, 'template_id' => 57, 'key' => "hu-HU", 'value' => "Írjon 10 fülbemászó e-mail tárgysort ehhez a termékhez:\n\n ##title## \n\nTermékleírás:\n ##description## \n\n Az eredmény hangnemének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 2068, 'template_id' => 57, 'key' => "is-IS", 'value' => "Skrifaðu 10 grípandi efnislínur í tölvupósti fyrir þessa vöru:\n\n ##title## \n\nVörulýsing:\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 2069, 'template_id' => 57, 'key' => "id-ID", 'value' => "Tulis 10 baris subjek email yang menarik untuk produk ini:\n\n ##title## \n\nDeskripsi Produk:\n ##description## \n\n Nada suara hasil harus:\n ##tone_language## \n\n"],

            ['id' => 2070, 'template_id' => 57, 'key' => "it-IT", 'value' => "Scrivi 10 righe dell'oggetto dell'e-mail accattivanti per questo prodotto:\n\n ##title## \n\nDescrizione del prodotto:\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 2071, 'template_id' => 57, 'key' => "ja-JP", 'value' => "この製品に関するキャッチーな電子メールの件名を 10 行書きます:\n\n ##title## \n\n製品説明：\n ##description## \n\n 結果の口調は次のようになります:\n ##tone_language## \n\n"],

            ['id' => 2072, 'template_id' => 57, 'key' => "ko-KR", 'value' => "이 제품에 대해 기억하기 쉬운 10개의 이메일 제목을 작성하십시오:\n\n ##title## \n\n제품 설명:\n ##description## \n\n 결과의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 2073, 'template_id' => 57, 'key' => "ms-MY", 'value' => "Tulis 10 baris subjek e-mel yang menarik untuk produk ini:\n\n ##title## \n\nPenerangan Produk:\n ##description## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 2074, 'template_id' => 57, 'key' => "nb-NO", 'value' => "Skriv 10 fengende e-postemnelinjer for dette produktet:\n\n ##title## \n\nProduktbeskrivelse:\n ##description## \n\n Stemmetonen for resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 2075, 'template_id' => 57, 'key' => "pl-PL", 'value' => "Napisz 10 chwytliwych tematów wiadomości e-mail dla tego produktu:\n\n ##title## \n\nOpis produktu:\n ##description## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 2076, 'template_id' => 57, 'key' => "pt-PT", 'value' => "Escreva 10 linhas de assunto de e-mail cativantes para este produto:\n\n ##title## \n\nDescrição do produto:\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2077, 'template_id' => 57, 'key' => "ru-RU", 'value' => "Напишите 10 броских тем письма для этого продукта:\n\n ##title## \n\nОписание продукта:\n ##description## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 2078, 'template_id' => 57, 'key' => "es-ES", 'value' => "Escriba 10 líneas de asunto de correo electrónico pegadizas para este producto:\n\n ##title## \n\nDescripción del Producto:\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 2079, 'template_id' => 57, 'key' => "sv-SE", 'value' => "Skriv 10 catchy e-postämnesrader för denna produkt:\n\n ##title## \n\nProduktbeskrivning:\n ##description## \n\n Tonen i resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 2080, 'template_id' => 57, 'key' => "tr-TR", 'value' => "Bu ürün için akılda kalıcı 10 e-posta konu satırı yazın:\n\n ##title## \n\nÜrün Açıklaması:\n ##description## \n\n Sonucun ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 2081, 'template_id' => 57, 'key' => "pt-BR", 'value' => "Escreva 10 linhas de assunto de e-mail atraentes para esse produto:\n\n ##title## \n\nDescrição do produto:\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2082, 'template_id' => 57, 'key' => "ro-RO", 'value' => "Scrieți 10 subiecte captivante pentru e-mail pentru acest produs:\n\n ##title## \n\nDescriere produs:\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 2083, 'template_id' => 57, 'key' => "vi-VN", 'value' => "Viết 10 dòng tiêu đề email hấp dẫn cho sản phẩm này:\n\n ##title## \n\nMô tả Sản phẩm:\n ##description## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 2084, 'template_id' => 57, 'key' => "sw-KE", 'value' => "Andika mada 10 za barua pepe zinazovutia za bidhaa hii:\n\n ##title## \n\nMaelezo ya bidhaa:\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 2085, 'template_id' => 57, 'key' => "sl-SI", 'value' => "Napišite 10 privlačnih vrstic z zadevo e-pošte za ta izdelek:\n\n ##title## \n\nOpis izdelka:\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2086, 'template_id' => 57, 'key' => "th-TH", 'value' => "เขียน 10 หัวเรื่องอีเมลที่จับใจสำหรับผลิตภัณฑ์นี้:\n\n ##title## \n\nรายละเอียดสินค้า:\n ##description## \n\n โทนเสียงของผลลัพธ์ต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 2087, 'template_id' => 57, 'key' => "uk-UA", 'value' => "Напишіть 10 яскравих тем електронних листів для цього продукту:\n\n ##title## \n\nОпис продукту:\n ##description## \n\n Тон голосу результату повинен бути таким:\n ##tone_language## \n\n"],

            ['id' => 2088, 'template_id' => 57, 'key' => "lt-LT", 'value' => "Parašykite 10 patrauklių šio produkto el. pašto temos eilučių:\n\n ##title## \n\nProdukto aprašymas:\n ##description## \n\n Rezultato balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 2089, 'template_id' => 57, 'key' => "bg-BG", 'value' => "Напишете 10 запомнящи се теми на имейла за този продукт:\n\n ##title## \n\nОписание на продукта:\n ##description## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 2090, 'template_id' => 58, 'key' => "en-US", 'value' => 'Write an engaging email content about:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 2091, 'template_id' => 58, 'key' => "ar-AE", 'value' => 'اكتب محتوى بريد إلكتروني جذاب حول:\n\n ##description## \n\n TTالوحيدة من صوت النتيجة يجب أن تكون::\n ##tone_language## \n\n'],

            ['id' => 2092, 'template_id' => 58, 'key' => "cmn-CN", 'value' => '撰写引人入胜的电子邮件内容:\n\n ##description## \n\n 结果的声音必须是:\n ##tone_language## \n\n'],

            ['id' => 2093, 'template_id' => 58, 'key' => "hr-HR", 'value' => 'Napišite zanimljiv sadržaj e-pošte o tome:\n\n ##description## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n'],

            ['id' => 2094, 'template_id' => 58, 'key' => "cs-CZ", 'value' => 'Napište zajímavý obsah e-mailu o:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n'],

            ['id' => 2095, 'template_id' => 58, 'key' => "da-DK", 'value' => 'Skriv et engagerende e-mailindhold om:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n'],

            ['id' => 2096, 'template_id' => 58, 'key' => "nl-NL", 'value' => 'Schrijf een boeiende e-mailinhoud over:\n\n ##description## \n\n Toon van de stem van het resultaat moet zijn:\n ##tone_language## \n\n'],

            ['id' => 2097, 'template_id' => 58, 'key' => "et-EE", 'value' => 'Kirjutage kaasahaarav meili sisu:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 2098, 'template_id' => 58, 'key' => "fi-FI", 'value' => 'Kirjoita kiinnostavaa sähköpostisisältöä aiheesta:\n\n ##description## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n'],

            ['id' => 2099, 'template_id' => 58, 'key' => "fr-FR", 'value' => 'Rédigez un contenu d e-mail engageant sur:\n\n ##description## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n'],

            ['id' => 2100, 'template_id' => 58, 'key' => "de-DE", 'value' => 'Schreiben Sie einen ansprechenden E-Mail-Inhalt über:\n\n ##description## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n'],

            ['id' => 2101, 'template_id' => 58, 'key' => "el-GR", 'value' => 'Γράψτε ένα ελκυστικό περιεχόμενο email για:\n\n ##description## \n\n Η φωνή του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 2102, 'template_id' => 58, 'key' => "he-IL", 'value' => 'כתוב תוכן דוא"ל מרתק על:\n\n ##description## \n\n של הקול של התוצאה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 2103, 'template_id' => 58, 'key' => "hi-IN", 'value' => 'के बारे में एक आकर्षक ईमेल सामग्री लिखें:\n\n ##description## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n'],

            ['id' => 2104, 'template_id' => 58, 'key' => "hu-HU", 'value' => 'Írjon lebilincselő e-mail tartalmat erről:\n\n ##description## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n'],

            ['id' => 2105, 'template_id' => 58, 'key' => "is-IS", 'value' => 'Skrifaðu grípandi tölvupóstsefni um:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 2106, 'template_id' => 58, 'key' => "id-ID", 'value' => 'Tulis konten email yang menarik tentang:\n\n ##description## \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n'],

            ['id' => 2107, 'template_id' => 58, 'key' => "it-IT", 'value' => 'Scrivi un contenuto email accattivante su:\n\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n'],

            ['id' => 2108, 'template_id' => 58, 'key' => "ja-JP", 'value' => '～について魅力的なメールコンテンツを作成します:\n\n ##description## \n\n 結果の声のトーンは:\n ##tone_language## \n\n'],

            ['id' => 2109, 'template_id' => 58, 'key' => "ko-KR", 'value' => '매력적인 이메일 콘텐츠를 작성하세요.:\n\n ##description## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다:\n ##tone_language## \n\n'],

            ['id' => 2110, 'template_id' => 58, 'key' => "ms-MY", 'value' => 'Tulis kandungan e-mel yang menarik tentang:\n\n ##description## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n'],

            ['id' => 2111, 'template_id' => 58, 'key' => "nb-NO", 'value' => 'Skriv et engasjerende e-postinnhold om:\n\n ##description## \n\n Tone av stemmen til resultatet må være:\n ##tone_language## \n\n'],

            ['id' => 2112, 'template_id' => 58, 'key' => "pl-PL", 'value' => 'Napisz angażującą treść e-maila nt:\n\n ##description## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n'],

            ['id' => 2113, 'template_id' => 58, 'key' => "pt-PT", 'value' => 'Escreva um conteúdo de correio electrónico cativante sobre:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 2114, 'template_id' => 58, 'key' => "ru-RU", 'value' => 'Напишите привлекательный контент по электронной почте о:\n\n ##description## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n'],

            ['id' => 2115, 'template_id' => 58, 'key' => "es-ES", 'value' => 'Escriba un contenido de correo electrónico atractivo sobre:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n'],

            ['id' => 2116, 'template_id' => 58, 'key' => "sv-SE", 'value' => 'Skriv ett engagerande e-postinnehåll om:\n\n ##description## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n'],

            ['id' => 2117, 'template_id' => 58, 'key' => "tr-TR", 'value' => 'Hakkında ilgi çekici bir e-posta içeriği yazın:\n\n ##description## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n'],

            ['id' => 2118, 'template_id' => 58, 'key' => "pt-BR", 'value' => 'Escreva um conteúdo de e-mail envolvente sobre:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 2119, 'template_id' => 58, 'key' => "ro-RO", 'value' => 'Scrieți un conținut de e-mail captivant despre:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 2120, 'template_id' => 58, 'key' => "vi-VN", 'value' => 'Viết một nội dung email hấp dẫn về:\n\n ##description## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n'],

            ['id' => 2121, 'template_id' => 58, 'key' => "sw-KE", 'value' => 'Andika maudhui ya barua pepe ya kuvutia kuhusu:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 2122, 'template_id' => 58, 'key' => "sl-SI", 'value' => 'Napišite privlačno e-poštno vsebino o:\n\n ##description## \n\n  Ton glasu rezultata mora biti:\n ##tone_language## \n\n'],

            ['id' => 2123, 'template_id' => 58, 'key' => "th-TH", 'value' => 'เขียนเนื้อหาอีเมลที่น่าสนใจเกี่ยวกับ:\n\n ##description## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 2124, 'template_id' => 58, 'key' => "uk-UA", 'value' => 'Напишіть привабливий електронний лист про:\n\n ##description## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n'],

            ['id' => 2125, 'template_id' => 58, 'key' => "lt-LT", 'value' => 'Parašykite patrauklų el. pašto turinį:\n\n ##description## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n'],

            ['id' => 2126, 'template_id' => 58, 'key' => "bg-BG", 'value' => 'Напишете ангажиращо имейл съдържание за:\n\n ##description## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 2127, 'template_id' => 59, 'key' => "en-US", 'value' => "Write 10 interesting titles for Google ads of the following product aimed at:\n\n ##audience## \n\n Product name:\n ##title## \n\n Product description:\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n Title's length must be 30 characters\n\n"],

            ['id' => 2728, 'template_id' => 59, 'key' => "ar-AE", 'value' => "اكتب 10 عناوين مثيرة للاهتمام لإعلانات Google للمنتج التالي المستهدف:\n\n ##audience## \n\n اسم المنتج:\n ##title## \n\n وصف المنتج:\n ##description## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n يجب أن يكون طول العنوان 30 حرفًا\n\n"],

            ['id' => 2129, 'template_id' => 59, 'key' => "cmn-CN", 'value' =>  "为以下产品的 Google 广告写 10 个有趣的标题，目的是:\n\n ##audience## \n\n 产品名称:\n ##title## \n\n 产品描述:\n ##description## \n\n 结果的语气必须是:\n ##tone_language## \n\n 标题长度必须为 30 个字符\n\n"],

            ['id' => 2130, 'template_id' => 59, 'key' => "hr-HR", 'value' => "Napišite 10 zanimljivih naslova za Google oglase sljedećeg proizvoda namijenjenog:\n\n ##audience## \n\n Ime proizvoda:\n ##title## \n\n Opis proizvoda:\n ##description## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n Duljina naslova mora biti 30 znakova\n\n"],

            ['id' => 2131, 'template_id' => 59, 'key' => "cs-CZ", 'value' => "Napište 10 zajímavých názvů pro reklamy Google na následující produkt, na který je zaměřen:\n\n ##audience## \n\n Jméno výrobku:\n ##title## \n\n Popis výrobku:\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n Délka názvu musí být 30 znaků\n\n"],

            ['id' => 2132, 'template_id' => 59, 'key' => "da-DK", 'value' => "Skriv 10 interessante titler til Google-annoncer for følgende produkt rettet mod:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produkt beskrivelse:\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n Titlens længde skal være på 30 tegn\n\n"],

            ['id' => 2133, 'template_id' => 59, 'key' => "nl-NL", 'value' => "Schrijf 10 interessante titels voor Google-advertenties van het volgende product gericht op:\n\n ##audience## \n\n Productnaam:\n ##title## \n\n Product beschrijving:\n ##description## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n TDe lengte van de titel moet 30 tekens zijn\n\n"],

            ['id' => 2134, 'template_id' => 59, 'key' => "et-EE", 'value' => "Kirjutage 10 huvitavat pealkirja järgmise toote Google'i reklaamidele:\n\n ##audience## \n\n Tootenimi:\n ##title## \n\n Tootekirjeldus:\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n Pealkirja pikkus peab olema 30 tähemärki\n\n"],

            ['id' => 2135, 'template_id' => 59, 'key' => "fi-FI", 'value' => "Kirjoita 10 mielenkiintoista otsikkoa seuraavan tuotteen Google-mainoksille:\n\n ##audience## \n\n Tuotteen nimi:\n ##title## \n\n Tuotteen Kuvaus:\n ##description## \n\n Äänesävyn tuloksen on oltava:\n ##tone_language## \n\n Otsikon pituuden tulee olla 30 merkkiä\n\n"],

            ['id' => 2136, 'template_id' => 59, 'key' => "fr-FR", 'value' => "Écrivez 10 titres intéressants pour les annonces Google du produit suivant visant à:\n\n ##audience## \n\n Nom du produit:\n ##title## \n\n Description du produit:\n ##description## \n\n Le ton de la voix du résultat doit être:\n ##tone_language## \n\n TLa longueur du titre doit être de 30 caractères\n\n"],

            ['id' => 2137, 'template_id' => 59, 'key' => "de-DE", 'value' => "Schreiben Sie 10 interessante Titel für Google-Anzeigen für das folgende Produkt, auf das Sie abzielen:\n\n ##audience## \n\n Produktname:\n ##title## \n\n Produktbeschreibung:\n ##description## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n Die Länge des Titels muss 30 Zeichen betragen\n\n"],

            ['id' => 2138, 'template_id' => 59, 'key' => "el-GR", 'value' => "Γράψτε 10 ενδιαφέροντες τίτλους για τις διαφημίσεις Google για το παρακάτω προϊόν:\n\n ##audience## \n\n Ονομασία προϊόντος:\n ##title## \n\n Περιγραφή προϊόντος:\n ##description## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n Το μήκος του τίτλου πρέπει να είναι 30 χαρακτήρες\n\n"],

            ['id' => 2139, 'template_id' => 59, 'key' => "he-IL", 'value' => "כתוב 10 כותרות מעניינות למודעות גוגל של המוצר הבא שמיועדות אליו:\n\n ##audience## \n\n שם מוצר:\n ##title## \n\n תיאור מוצר:\n ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n אורך הכותרת חייב להיות 30 תווים\n\n"],

            ['id' => 2140, 'template_id' => 59, 'key' => "hi-IN", 'value' => "निम्नलिखित उत्पाद के Google विज्ञापनों के लिए 10 दिलचस्प शीर्षक लिखें:\n\n ##audience## \n\n प्रोडक्ट का नाम:\n ##title## \n\n उत्पाद वर्णन:\n ##description## \n\n परिणाम का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n शीर्षक की लंबाई 30 वर्ण होनी चाहिए\n\n"],

            ['id' => 2141, 'template_id' => 59, 'key' => "hu-HU", 'value' => "Írjon 10 érdekes címet a következő termék Google hirdetéseihez, amelyek célja:\n\n ##audience## \n\n Termék név:\n ##title## \n\n Termékleírás:\n ##description## \n\n Az eredmény hangnemének kell lennie:\n ##tone_language## \n\n A címnek 30 karakterből kell állnia\n\n"],

            ['id' => 2142, 'template_id' => 59, 'key' => "is-IS", 'value' => "Skrifaðu 10 áhugaverða titla fyrir Google auglýsingar fyrir eftirfarandi vöru sem miðar að:\n\n ##audience## \n\n Vöru NafnVörulýsing:\n ##title## \n\n :\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n Lengd titilsins verður að vera 30 stafir\n\n"],

            ['id' => 2143, 'template_id' => 59, 'key' => "id-ID", 'value' => "Tulis 10 judul iklan Google yang menarik dari produk berikut yang dituju:\n\n ##audience## \n\n Nama Produk:\n ##title## \n\n Deskripsi Produk:\n ##description## \n\n Nada suara hasilnya harus:\n ##tone_language## \n\n Panjang judul harus 30 karakter\n\n"],

            ['id' => 2144, 'template_id' => 59, 'key' => "it-IT", 'value' => "Scrivi 10 titoli interessanti per gli annunci Google del seguente prodotto mirato:\n\n ##audience## \n\n Nome del prodotto:\n ##title## \n\n Descrizione del prodotto:\n ##description## \n\n Tono di voce del risultato deve essere:\n ##tone_language## \n\n La lunghezza del titolo deve essere di 30 caratteri\n\n"],

            ['id' => 2145, 'template_id' => 59, 'key' => "ja-JP", 'value' => "次の商品の Google 広告用に、興味深いタイトルを 10 個書いてください。:\n\n ##audience## \n\n 商品名:\n ##title## \n\n 製品説明:\n ##description## \n\n 結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n タイトルの長さは 30 文字である必要があります\n\n"],

            ['id' => 2146, 'template_id' => 59, 'key' => "ko-KR", 'value' => "다음 제품에 대한 Google 광고의 흥미로운 제목 10개를 작성하세요.:\n\n ##audience## \n\n 상품명:\n ##title## \n\n 제품 설명:\n ##description## \n\n 결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n 제목 길이는 30자여야 합니다.\n\n"],

            ['id' => 2147, 'template_id' => 59, 'key' => "ms-MY", 'value' => "Tulis 10 tajuk menarik untuk iklan Google bagi produk berikut yang bertujuan:\n\n ##audience## \n\n Nama Produk:\n ##title## \n\n Penerangan Produk:\n ##description## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n Panjang tajuk mestilah 30 aksara\n\n"],

            ['id' => 2148, 'template_id' => 59, 'key' => "nb-NO", 'value' => "Skriv 10 interessante titler for Google-annonser for følgende produkt rettet mot:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n Tittelens lengde må være på 30 tegn\n\n"],

            ['id' => 2149, 'template_id' => 59, 'key' => "pl-PL", 'value' => "Napisz 10 interesujących tytułów reklam Google dla następującego produktu, do którego są skierowane:\n\n ##audience## \n\n Nazwa produktu:\n ##title## \n\n Opis produktu:\n ##description## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n TDługość tytułu musi wynosić 30 znaków\n\n"],

            ['id' => 2150, 'template_id' => 59, 'key' => "pt-PT", 'value' => "Escreva 10 títulos interessantes para anúncios Google do seguinte produto destinados a:\n\n ##audience## \n\n Nome do produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n O comprimento do título deve ser de 30 caracteres\n\n"],

            ['id' => 2151, 'template_id' => 59, 'key' => "ru-RU", 'value' => "Напишите 10 интересных заголовков для объявлений Google следующего продукта, нацеленных на:\n\n ##audience## \n\n Наименование товара:\n ##title## \n\n Описание продукта:\n ##description## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n Длина заголовка должна быть 30 символов.\n\n"],

            ['id' => 2152, 'template_id' => 59, 'key' => "es-ES", 'value' => "Escriba 10 títulos interesantes para los anuncios de Google del siguiente producto dirigido a:\n\n ##audience## \n\n Nombre del producto:\n ##title## \n\n PDescripción del Producto:\n ##description## \n\n TEl tono de voz del resultado debe ser:\n ##tone_language## \n\n La longitud del título debe ser de 30 caracteres.\n\n"],

            ['id' => 2153, 'template_id' => 59, 'key' => "sv-SE", 'value' => "Skriv 10 intressanta titlar för Google-annonser för följande produkt som syftar till:\n\n ##audience## \n\n Produktnamn:\n ##title## \n\n Produktbeskrivning:\n ##description## \n\n Tonen i rösten för resultatet måste vara:\n ##tone_language## \n\n Titelns längd måste vara 30 tecken\n\n"],

            ['id' => 2154, 'template_id' => 59, 'key' => "tr-TR", 'value' => "Hedeflenen aşağıdaki ürünün Google reklamları için 10 ilginç başlık yazın:\n\n ##audience## \n\n Ürün adı:\n ##title## \n\n Ürün AçıklamasıSonucun ses tonu şöyle olmalıdır:\n ##description## \n\n :\n ##tone_language## \n\n Başlığın uzunluğu 30 karakter olmalıdır\n\n"],

            ['id' => 2155, 'template_id' => 59, 'key' => "pt-BR", 'value' => "Escreva 10 títulos interessantes para anúncios do Google do seguinte produto destinado a:\n\n ##audience## \n\nNome do produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n O comprimento do título deve ser de 30 caracteres\n\n"],

            ['id' => 2156, 'template_id' => 59, 'key' => "ro-RO", 'value' => "Scrieți 10 titluri interesante pentru reclamele Google ale următorului produs vizat:\n\n ##audience## \n\n Numele produsului:\n ##title## \n\n Descriere produs:\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n Lungimea titlului trebuie să fie de 30 de caractere\n\n"],

            ['id' => 2157, 'template_id' => 59, 'key' => "vi-VN", 'value' => "Viết 10 tiêu đề thú vị cho quảng cáo Google của sản phẩm sau nhắm đến:\n\n ##audience## \n\n Tên sản phẩm:\n ##title## \n\n Mô tả Sản phẩm:\n ##description## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n Độ dài của tiêu đề phải là 30 ký tự\n\n"],

            ['id' => 2158, 'template_id' => 59, 'key' => "sw-KE", 'value' => "Andika mada 10 za kuvutia za matangazo ya Google za bidhaa ifuatayo inayolengaJina la bidhaa:\n\n ##audience## \n\n :\n ##title## \n\n PMaelezo ya bidhaa:\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n Urefu wa kichwa lazima uwe na vibambo 30\n\n"],

            ['id' => 2159, 'template_id' => 59, 'key' => "sl-SI", 'value' => "Napišite 10 zanimivih naslovov za Google oglase naslednjega izdelka, namenjenegaIme izdelka:\n\n ##audience## \n\n :\n ##title## \n\n Opis izdelka:\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n Dolžina naslova mora biti 30 znakov\n\n"],

            ['id' => 2160, 'template_id' => 59, 'key' => "th-TH", 'value' => "เขียน 10 ชื่อที่น่าสนใจสำหรับโฆษณา Google ของผลิตภัณฑ์ต่อไปนี้มุ่งเป้าไปที่:\n\n ##audience## \n\n ชื่อผลิตภัณฑ์:\n ##title## \n\n รายละเอียดสินค้า:\n ##description## \n\nเสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n ความยาวของชื่อเรื่องต้องมีความยาว 30 อักขระ\n\n"],

            ['id' => 2161, 'template_id' => 59, 'key' => "uk-UA", 'value' => "Напишіть 10 цікавих назв для оголошень Google наступного продукту, спрямованих на:\n\n ##audience## \n\n Назва продукту:\n ##title## \n\n Опис продукту:\n ##description## \n\n Тон голосу результату повинен бути:\n ##tone_language## \n\n Довжина заголовка має бути 30 символів\n\n"],

            ['id' => 2162, 'template_id' => 59, 'key' => "lt-LT", 'value' => "Parašykite 10 įdomių šio produkto „Google“ skelbimų pavadinimų:\n\n ##audience## \n\nProdukto pavadinimas:\n ##title## \n\n Prekės aprašymas:\n ##description## \n\n Turi būti rezultato balso tonas:\n ##tone_language## \n\n Pavadinimo ilgis turi būti 30 simbolių\n\n"],

            ['id' => 2163, 'template_id' => 59, 'key' => "bg-BG", 'value' => "Напишете 10 интересни заглавия за Google реклами на следния продукт, насочен към:\n\n ##audience## \n\n Име на продукта:\n ##title## \n\n Описание на продукта:\n ##description## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n Дължината на заглавието трябва да бъде 30 знака\n\n"],

            ['id' => 2164, 'template_id' => 60, 'key' => "en-US", 'value' => 'Write a trending tweet for a Twitter post about:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 2165, 'template_id' => 60, 'key' => "ar-AE", 'value' => 'اكتب تغريدة رائجة لمشاركة Twitter حول:\n\n ##description## \n\n TTالوحيدة من صوت النتيجة يجب أن تكون::\n ##tone_language## \n\n'],

            ['id' => 2166, 'template_id' => 60, 'key' => "cmn-CN", 'value' => '撰写引人入胜的电子邮件内容:\n\n ##description## \n\n 结果的声音必须是:\n ##tone_language## \n\n'],

            ['id' => 2167, 'template_id' => 60, 'key' => "hr-HR", 'value' => 'Napišite trendovski tweet za post na Twitteru o:\n\n ##description## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n'],

            ['id' => 2168, 'template_id' => 60, 'key' => "cs-CZ", 'value' => 'Napište trendový tweet pro příspěvek na Twitteru o:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n'],

            ['id' => 2169, 'template_id' => 60, 'key' => "da-DK", 'value' => 'Skriv et trending tweet til et Twitter-indlæg om:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n'],

            ['id' => 2170, 'template_id' => 60, 'key' => "nl-NL", 'value' => 'Schrijf een trending tweet voor een Twitter-post over:\n\n ##description## \n\n Toon van de stem van het resultaat moet zijn:\n ##tone_language## \n\n'],

            ['id' => 2171, 'template_id' => 60, 'key' => "et-EE", 'value' => 'Kirjutage Twitteri postituse jaoks trendikas säuts:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 2172, 'template_id' => 60, 'key' => "fi-FI", 'value' => 'Kirjoita trendaava twiitti Twitter-postaukseen aiheesta:\n\n ##description## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n'],

            ['id' => 2173, 'template_id' => 60, 'key' => "fr-FR", 'value' => 'Rédigez un tweet tendance pour un post Twitter sur:\n\n ##description## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n'],

            ['id' => 2174, 'template_id' => 60, 'key' => "de-DE", 'value' => 'Schreiben Sie einen Trend-Tweet für einen Twitter-Beitrag darüber:\n\n ##description## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n'],

            ['id' => 2175, 'template_id' => 60, 'key' => "el-GR", 'value' => 'Γράψτε ένα δημοφιλές tweet για μια ανάρτηση στο Twitter σχετικά με:\n\n ##description## \n\n Η φωνή του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 2176, 'template_id' => 60, 'key' => "he-IL", 'value' => ' כתוב ציוץ מגמתי לפוסט בטוויטר על:\n\n ##description## \n\n של הקול של התוצאה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 2177, 'template_id' => 60, 'key' => "hi-IN", 'value' => 'के बारे में एक ट्विटर पोस्ट के लिए एक ट्रेंडिंग ट्वीट लिखें:\n\n ##description## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n'],

            ['id' => 2178, 'template_id' => 60, 'key' => "hu-HU", 'value' => 'Írj egy felkapott tweetet egy Twitter-bejegyzéshez, amely erről szól:\n\n ##description## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n'],

            ['id' => 2179, 'template_id' => 60, 'key' => "is-IS", 'value' => 'Skrifaðu vinsælt kvak fyrir Twitter færslu um:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 2180, 'template_id' => 60, 'key' => "id-ID", 'value' => 'Tulis tweet yang sedang tren untuk posting Twitter tentang:\n\n ##description## \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n'],

            ['id' => 2181, 'template_id' => 60, 'key' => "it-IT", 'value' => 'Scrivi un tweet di tendenza per un post su Twitter:\n\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n'],

            ['id' => 2182, 'template_id' => 60, 'key' => "ja-JP", 'value' => 'についての Twitter 投稿のトレンドツイートを書く:\n\n ##description## \n\n 結果の声のトーンは:\n ##tone_language## \n\n'],

            ['id' => 2183, 'template_id' => 60, 'key' => "ko-KR", 'value' => '에 대한 Twitter 게시물에 대한 최신 트윗 작성:\n\n ##description## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다:\n ##tone_language## \n\n'],

            ['id' => 2184, 'template_id' => 60, 'key' => "ms-MY", 'value' => 'Tulis tweet sohor kini untuk siaran Twitter tentang:\n\n ##description## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n'],

            ['id' => 2185, 'template_id' => 60, 'key' => "nb-NO", 'value' => 'Skriv en populær tweet for et Twitter-innlegg om:\n\n ##description## \n\n Tone av stemmen til resultatet må være:\n ##tone_language## \n\n'],

            ['id' => 2186, 'template_id' => 60, 'key' => "pl-PL", 'value' => 'Napisz popularny tweet do wpisu na Twitterze:\n\n ##description## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n'],

            ['id' => 2187, 'template_id' => 60, 'key' => "pt-PT", 'value' => 'Escrever um trending tweet para uma publicação no Twitter sobre:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 2188, 'template_id' => 60, 'key' => "ru-RU", 'value' => 'Напишите популярный твит для поста в Твиттере о:\n\n ##description## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n'],

            ['id' => 2189, 'template_id' => 60, 'key' => "es-ES", 'value' => 'Escriba un tweet de tendencia para una publicación de Twitter sobre:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n'],

            ['id' => 2190, 'template_id' => 60, 'key' => "sv-SE", 'value' => 'Skriv en trendig tweet för ett Twitter-inlägg om:\n\n ##description## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n'],

            ['id' => 2191, 'template_id' => 60, 'key' => "tr-TR", 'value' => 'Hakkında bir Twitter gönderisi için trend olan bir tweet yazın:\n\n ##description## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n'],

            ['id' => 2192, 'template_id' => 60, 'key' => "pt-BR", 'value' => 'Escreva um tweet de tendência para uma postagem no Twitter sobre:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 2193, 'template_id' => 60, 'key' => "ro-RO", 'value' => 'Scrieți un tweet în tendințe pentru o postare Twitter despre:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 2194, 'template_id' => 60, 'key' => "vi-VN", 'value' => 'Viết một tweet xu hướng cho một bài đăng trên Twitter về:\n\n ##description## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n'],

            ['id' => 2195, 'template_id' => 60, 'key' => "sw-KE", 'value' => 'Andika tweet inayovuma kwa chapisho la Twitter kuhusu:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 2196, 'template_id' => 60, 'key' => "sl-SI", 'value' => 'Napišite trendovski tvit za objavo na Twitterju o:\n\n ##description## \n\n  Ton glasu rezultata mora biti:\n ##tone_language## \n\n'],

            ['id' => 2197, 'template_id' => 60, 'key' => "th-TH", 'value' => 'เขียนทวีตที่กำลังมาแรงสำหรับโพสต์ Twitter เกี่ยวกับ:\n\n ##description## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 2198, 'template_id' => 60, 'key' => "uk-UA", 'value' => 'Напишіть популярний твіт для публікації в Twitter про:\n\n ##description## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n'],

            ['id' => 2199, 'template_id' => 60, 'key' => "lt-LT", 'value' => 'Parašykite patrauklų el. pašto turinį:\n\n ##description## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n'],

            ['id' => 2200, 'template_id' => 60, 'key' => "bg-BG", 'value' => 'Напишете актуален туит за публикация в Twitter за:\n\n ##description## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 2201, 'template_id' => 61, 'key' => "en-US", 'value' => 'Write inspiring posts for LinkedIn about:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 2202, 'template_id' => 61, 'key' => "ar-AE", 'value' => 'اكتب منشورات ملهمة على LinkedIn عنها:\n\n ##description## \n\n Tالوحيدة من صوت النتيجة يجب أن تكون:\n ##tone_language## \n\n'],

            ['id' => 2203, 'template_id' => 61, 'key' => "cmn-CN", 'value' => '为 LinkedIn 撰写鼓舞人心的帖子:\n\n ##description## \n\n 结果的声音必须是:\n ##tone_language## \n\n'],

            ['id' => 2204, 'template_id' => 61, 'key' => "hr-HR", 'value' => 'Pišite inspirativne postove za LinkedIn o tome:\n\n ##description## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n'],

            ['id' => 2205, 'template_id' => 61, 'key' => "cs-CZ", 'value' => 'Pište inspirativní příspěvky pro LinkedIn o:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n'],

            ['id' => 2206, 'template_id' => 61, 'key' => "da-DK", 'value' => 'Skriv inspirerende indlæg til LinkedIn om:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n'],

            ['id' => 2207, 'template_id' => 61, 'key' => "nl-NL", 'value' => 'Schrijf inspirerende posts voor LinkedIn over:\n\n ##description## \n\n Toon van de stem van het resultaat moet zijn:\n ##tone_language## \n\n'],

            ['id' => 2208, 'template_id' => 61, 'key' => "et-EE", 'value' => 'Kirjutage LinkedIni jaoks inspireerivaid postitusi:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 2209, 'template_id' => 61, 'key' => "fi-FI", 'value' => 'Kirjoita inspiroivia viestejä LinkedInille aiheesta:\n\n ##description## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n'],

            ['id' => 2210, 'template_id' => 61, 'key' => "fr-FR", 'value' => 'Rédigez des articles inspirants pour LinkedIn à propos de:\n\n ##description## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n'],

            ['id' => 2211, 'template_id' => 61, 'key' => "de-DE", 'value' => 'Schreiben Sie inspirierende Beiträge für LinkedIn über:\n\n ##description## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n'],

            ['id' => 2212, 'template_id' => 61, 'key' => "el-GR", 'value' => 'Γράψτε εμπνευσμένες αναρτήσεις για το LinkedIn:\n\n ##description## \n\n v:\n ##tone_language## \n\n'],

            ['id' => 2213, 'template_id' => 61, 'key' => "he-IL", 'value' => 'כתוב פוסטים מעוררי השראה עבור LinkedIn על\n\n ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 2214, 'template_id' => 61, 'key' => "hi-IN", 'value' => 'लिंक्डइन के बारे में प्रेरक पोस्ट लिखें:\n\n ##description## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n'],

            ['id' => 2215, 'template_id' => 61, 'key' => "hu-HU", 'value' => 'Írjon inspiráló bejegyzéseket a LinkedIn számára erről:\n\n ##description## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n'],

            ['id' => 2216, 'template_id' => 61, 'key' => "is-IS", 'value' => 'Skrifaðu hvetjandi færslur fyrir LinkedIn um:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 2217, 'template_id' => 61, 'key' => "id-ID", 'value' => 'Tulis postingan yang menginspirasi untuk LinkedIn tentang:\n\n ##description## \n\n TNada suara hasilnya harus dibuat.:\n ##tone_language## \n\n'],

            ['id' => 2218, 'template_id' => 61, 'key' => "it-IT", 'value' => 'Scrivi post stimolanti per LinkedIn su:\n\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n'],

            ['id' => 2219, 'template_id' => 61, 'key' => "ja-JP", 'value' => '～についてLinkedInにインスピレーションを与える投稿を書く:\n\n ##description## \n\n 結果の声のトーンは、:\n ##tone_language## \n\n'],

            ['id' => 2220, 'template_id' => 61, 'key' => "ko-KR", 'value' => 'LinkedIn에 대한 영감을 주는 게시물 작성:\n\n ##description## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다.:\n ##tone_language## \n\n'],

            ['id' => 2221, 'template_id' => 61, 'key' => "ms-MY", 'value' => 'Tulis catatan yang memberi inspirasi untuk LinkedIn tentang:\n\n ##description## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n'],

            ['id' => 2222, 'template_id' => 61, 'key' => "nb-NO", 'value' => 'Skriv inspirerende innlegg for LinkedIn om:\n\n ##description## \n\n Tone av stemmen til resultatet må være:\n ##tone_language## \n\n'],

            ['id' => 2223, 'template_id' => 61, 'key' => "pl-PL", 'value' => 'Pisz inspirujące posty na LinkedIn na temat:\n\n ##description## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n'],

            ['id' => 2224, 'template_id' => 61, 'key' => "pt-PT", 'value' => 'Escreva mensagens inspiradoras para o LinkedIn sobre:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 2225, 'template_id' => 61, 'key' => "ru-RU", 'value' => 'Пишите вдохновляющие посты для LinkedIn о:\n\n ##description## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n'],

            ['id' => 2226, 'template_id' => 61, 'key' => "es-ES", 'value' => 'Escribe publicaciones inspiradoras para LinkedIn sobre:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n'],

            ['id' => 2227, 'template_id' => 61, 'key' => "sv-SE", 'value' => 'Skriv inspirerande inlägg för LinkedIn om:\n\n ##description## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n'],

            ['id' => 2228, 'template_id' => 61, 'key' => "tr-TR", 'value' => 'hakkında LinkedIn için ilham verici yazılar yazın:\n\n ##description## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n'],

            ['id' => 2229, 'template_id' => 61, 'key' => "pt-BR", 'value' => 'Escreva postagens inspiradoras para o LinkedIn sobre:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 2230, 'template_id' => 61, 'key' => "ro-RO", 'value' => 'Scrieți postări inspiratoare pentru LinkedIn despre:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 2231, 'template_id' => 61, 'key' => "vi-VN", 'value' => 'Viết các bài đăng đầy cảm hứng cho LinkedIn về:\n\n ##description## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n'],

            ['id' => 2232, 'template_id' => 61, 'key' => "sw-KE", 'value' => 'Andika machapisho ya kutia moyo kwa LinkedIn kuhusu:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 2233, 'template_id' => 61, 'key' => "sl-SI", 'value' => 'Pišite navdihujoče objave za LinkedIn o:\n\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n'],

            ['id' => 2234, 'template_id' => 61, 'key' => "th-TH", 'value' => 'เขียนโพสต์ที่สร้างแรงบันดาลใจสำหรับ LinkedIn เกี่ยวกับ:\n\n ##description## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 2235, 'template_id' => 61, 'key' => "uk-UA", 'value' => 'Пишіть надихаючі дописи для LinkedIn про:\n\n ##description## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n'],

            ['id' => 2236, 'template_id' => 61, 'key' => "lt-LT", 'value' => 'Rašykite įkvepiančius „LinkedIn“ įrašus apie:\n\n ##description## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n'],

            ['id' => 2237, 'template_id' => 61, 'key' => "bg-BG", 'value' => 'Пишете вдъхновяващи публикации за LinkedIn за:\n\n ##description## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 2238, 'template_id' => 62, 'key' => "en-US", 'value' => 'Generate 10 eye catching notification messages about:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 2239, 'template_id' => 62, 'key' => "ar-AE", 'value' => 'إنشاء 10 رسائل إخطارات لافتة للنظر حول\n\n ##description## \n\n Tالوحيدة من صوت النتيجة يجب أن تكون:\n ##tone_language## \n\n'],

            ['id' => 2240, 'template_id' => 62, 'key' => "cmn-CN", 'value' => '生成 10 条引人注目的通知消息:\n\n ##description## \n\n 结果的声音必须是:\n ##tone_language## \n\n'],

            ['id' => 2241, 'template_id' => 62, 'key' => "hr-HR", 'value' => 'Generirajte 10 privlačnih poruka obavijesti o:\n\n ##description## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n'],

            ['id' => 2242, 'template_id' => 62, 'key' => "cs-CZ", 'value' => 'Vygenerujte 10 poutavých oznámení o:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n'],

            ['id' => 2243, 'template_id' => 62, 'key' => "da-DK", 'value' => 'Generer 10 iøjnefaldende meddelelser om:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n'],

            ['id' => 2244, 'template_id' => 62, 'key' => "nl-NL", 'value' => 'Genereer 10 opvallende meldingsberichten over:\n\n ##description## \n\n Toon van de stem van het resultaat moet zijn:\n ##tone_language## \n\n'],

            ['id' => 2245, 'template_id' => 62, 'key' => "et-EE", 'value' => 'Looge 10 pilkupüüdvat teavitussõnumit:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 2246, 'template_id' => 62, 'key' => "fi-FI", 'value' => 'Luo 10 huomiota herättävää ilmoitusviestiä aiheesta:\n\n ##description## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n'],

            ['id' => 2247, 'template_id' => 62, 'key' => "fr-FR", 'value' => 'Générez 10 messages de notification accrocheurs sur:\n\n ##description## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n'],

            ['id' => 2248, 'template_id' => 62, 'key' => "de-DE", 'value' => 'Generieren Sie 10 auffällige Benachrichtigungen über:\n\n ##description## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n'],

            ['id' => 2249, 'template_id' => 62, 'key' => "el-GR", 'value' => 'Δημιουργήστε 10 εντυπωσιακά μηνύματα ειδοποίησης σχετικά με:\n\n ##description## \n\n v:\n ##tone_language## \n\n'],

            ['id' => 2250, 'template_id' => 62, 'key' => "he-IL", 'value' => 'צור 10 הודעות התראה מושכות עין על\n\n ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 2251, 'template_id' => 62, 'key' => "hi-IN", 'value' => 'के बारे में 10 आकर्षक सूचना संदेश उत्पन्न करें:\n\n ##description## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n'],

            ['id' => 2252, 'template_id' => 62, 'key' => "hu-HU", 'value' => 'Hozzon létre 10 szemet gyönyörködtető értesítő üzenetet:\n\n ##description## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n'],

            ['id' => 2253, 'template_id' => 62, 'key' => "is-IS", 'value' => 'Búðu til 10 áberandi tilkynningaskilaboð um:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 2254, 'template_id' => 62, 'key' => "id-ID", 'value' => 'Hasilkan 10 pesan pemberitahuan yang menarik tentang:\n\n ##description## \n\n TNada suara hasilnya harus dibuat.:\n ##tone_language## \n\n'],

            ['id' => 2255, 'template_id' => 62, 'key' => "it-IT", 'value' => 'Genera 10 messaggi di notifica accattivanti su:\n\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n'],

            ['id' => 2256, 'template_id' => 62, 'key' => "ja-JP", 'value' => '～についてLinkedInにインスピレーションを与える投稿を書く:\n\n ##description## \n\n 結果の声のトーンは、:\n ##tone_language## \n\n'],

            ['id' => 2257, 'template_id' => 62, 'key' => "ko-KR", 'value' => '10개의 눈길을 끄는 알림 메시지 생성:\n\n ##description## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다.:\n ##tone_language## \n\n'],

            ['id' => 2258, 'template_id' => 62, 'key' => "ms-MY", 'value' => 'Hasilkan 10 mesej pemberitahuan yang menarik perhatian tentang:\n\n ##description## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n'],

            ['id' => 2259, 'template_id' => 62, 'key' => "nb-NO", 'value' => 'Generer 10 iøynefallende varslingsmeldinger om:\n\n ##description## \n\n Tone av stemmen til resultatet må være:\n ##tone_language## \n\n'],

            ['id' => 2260, 'template_id' => 62, 'key' => "pl-PL", 'value' => 'Wygeneruj 10 przyciągających wzrok powiadomień o:\n\n ##description## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n'],

            ['id' => 2261, 'template_id' => 62, 'key' => "pt-PT", 'value' => 'Gerar 10 mensagens de notificação atractivas sobre:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 2262, 'template_id' => 62, 'key' => "ru-RU", 'value' => 'Создайте 10 привлекающих внимание уведомлений о:\n\n ##description## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n'],

            ['id' => 2263, 'template_id' => 62, 'key' => "es-ES", 'value' => 'Genere 10 mensajes de notificación llamativos sobre:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n'],

            ['id' => 2264, 'template_id' => 62, 'key' => "sv-SE", 'value' => 'Generera 10 iögonfallande aviseringsmeddelanden om:\n\n ##description## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n'],

            ['id' => 2265, 'template_id' => 62, 'key' => "tr-TR", 'value' => 'Hakkında 10 göz alıcı bildirim mesajı oluşturun:\n\n ##description## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n'],

            ['id' => 2266, 'template_id' => 62, 'key' => "pt-BR", 'value' => 'Gerar 10 mensagens de notificação atraentes sobre:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 2267, 'template_id' => 62, 'key' => "ro-RO", 'value' => 'Generați 10 mesaje de notificare atrăgătoare despre:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 2268, 'template_id' => 62, 'key' => "vi-VN", 'value' => 'Tạo 10 tin nhắn thông báo bắt mắt về:\n\n ##description## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n'],

            ['id' => 2269, 'template_id' => 62, 'key' => "sw-KE", 'value' => 'Tengeneza arifa 10 za kuvutia macho kuhusu:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 2270, 'template_id' => 62, 'key' => "sl-SI", 'value' => 'Ustvarite 10 privlačnih obvestilnih sporočil o:\n\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n'],

            ['id' => 2271, 'template_id' => 62, 'key' => "th-TH", 'value' => 'สร้าง 10 ข้อความแจ้งเตือนที่สะดุดตาเกี่ยวกับ:\n\n ##description## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 2272, 'template_id' => 62, 'key' => "uk-UA", 'value' => 'Створіть 10 привабливих сповіщень про:\n\n ##description## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n'],

            ['id' => 2273, 'template_id' => 62, 'key' => "lt-LT", 'value' => 'Sukurkite 10 akį traukiančių pranešimų apie“ įrašus apie:\n\n ##description## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n'],

            ['id' => 2274, 'template_id' => 62, 'key' => "bg-BG", 'value' => 'Генерирайте 10 привличащи вниманието уведомителни съобщения за:\n\n ##description## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 2275, 'template_id' => 63, 'key' => "en-US", 'value' =>  "Write a professional and eye-catching description for the LinkedIn ads of the following product aimed at:\n\n ##audience## \n\n Product name:\n ##title## \n\n Product description:\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 2276, 'template_id' => 63, 'key' => "ar-AE", 'value' =>  "اكتب وصفًا احترافيًا ولافت للنظر لإعلانات LinkedIn للمنتج التالي الذي يهدف إلى:\n\n ##audience## \n\n اسم المنتج:\n ##title## \n\n وصف المنتج:\n ##description## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 2277, 'template_id' => 63, 'key' => "cmn-CN", 'value' =>  "為以下產品的 LinkedIn 廣告撰寫專業且引人注目的描述，旨在：\n\n ##audience## \n\n 產品名稱:\n ##title## \n\n 產品描述:\n ##description## \n\n 結果的語氣必須是:\n ##tone_language## \n\n"],

            ['id' => 2278, 'template_id' => 63, 'key' => "hr-HR", 'value' =>  "Napišite profesionalan i privlačan opis za LinkedIn oglase za sljedeći proizvod usmjeren na:\n\n ##audience## \n\n Ime proizvoda:\n ##title## \n\n Opis proizvoda:\n ##description## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2279, 'template_id' => 63, 'key' => "cs-CZ", 'value' =>  "Napište profesionální a poutavý popis pro reklamy LinkedIn na následující produkt zaměřený na:\n\n ##audience## \n\n Jméno výrobku:\n ##title## \n\n Popis výrobku:\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 2280, 'template_id' => 63, 'key' => "da-DK", 'value' =>  "Skriv en professionel og iøjnefaldende beskrivelse af LinkedIn-annoncerne for følgende produkt rettet mod:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produkt beskrivelse:\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 2281, 'template_id' => 63, 'key' => "nl-NL", 'value' =>  "Schrijf een professionele en opvallende beschrijving voor de LinkedIn-advertenties van het volgende product gericht op:\n\n ##audience## \n\n Productnaam:\n ##title## \n\n Product beschrijving:\n ##description## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 2282, 'template_id' => 63, 'key' => "et-EE", 'value' =>  "Kirjutage professionaalne ja pilkupüüdev kirjeldus järgmise toote LinkedIn reklaamidele, mis on suunatud:\n\n ##audience## \n\n Tootenimi:\n ##title## \n\n Tootekirjeldus:\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 2283, 'template_id' => 63, 'key' => "fi-FI", 'value' =>  "Kirjoita ammattimainen ja huomiota herättävä kuvaus seuraavan tuotteen LinkedIn-mainoksille, jotka on tarkoitettu:\n\n ##audience## \n\n Tuotteen nimi:\n ##title## \n\n Tuotteen Kuvaus:\n ##description## \n\n Äänesävyn tuloksen on oltava:\n ##tone_language## \n\n"],

            ['id' => 2284, 'template_id' => 63, 'key' => "fr-FR", 'value' =>  "Rédigez une description professionnelle et accrocheuse pour les publicités LinkedIn du produit suivant destiné à :\n\n ##audience## \n\n Nom du produit:\n ##title## \n\n Description du produit:\n ##description## \n\n Le ton de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 2285, 'template_id' => 63, 'key' => "de-DE", 'value' =>  "Verfassen Sie eine professionelle und auffällige Beschreibung für die LinkedIn-Anzeigen des folgenden Produkts mit deZielgruppe:\n\n ##audience## \n\n Produktname:\n ##title## \n\n Produktbeschreibung:\n ##description## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 2286, 'template_id' => 63, 'key' => "el-GR", 'value' =>  "Γράψτε μια επαγγελματική και εντυπωσιακή περιγραφή για τις διαφημίσεις LinkedIn του παρακάτω προϊόντος με στόχο:\n\n ##audience## \n\n Ονομασία προϊόντος:\n ##title## \n\n Περιγραφή προϊόντος:\n ##description## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 2287, 'template_id' => 63, 'key' => "he-IL", 'value' =>  "כתוב תיאור מקצועי ומושך את העין למודעות הלינקדאין של המוצר הבא המכוונות ל:\n\n ##audience## \n\n שם מוצר:\n ##title## \n\n תיאור מוצר:\n ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 2288, 'template_id' => 63, 'key' => "hi-IN", 'value' =>  "निम्नलिखित उत्पाद के लिंक्डइन विज्ञापनों के लिए एक पेशेवर और ध्यान आकर्षित करने वाला विवरण लिखें:\n\n ##audience## \n\n प्रोडक्ट का नाम:\n ##title## \n\n उत्पाद वर्णन:\n ##description## \n\n परिणाम का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 2289, 'template_id' => 63, 'key' => "hu-HU", 'value' =>  "Írjon professzionális és szemet gyönyörködtető leírást az alábbi termékek LinkedIn hirdetéseihez, amelyek célja:\n\n ##audience## \n\n Termék név:\n ##title## \n\n Termékleírás:\n ##description## \n\n Az eredmény hangnemének kell lennie:\n ##tone_language## \n\n"],

            ['id' => 2290, 'template_id' => 63, 'key' => "is-IS", 'value' =>  "Skrifaðu faglega og áberandi lýsingu fyrir LinkedIn auglýsingar á eftirfarandi vöru sem miðar að:\n\n ##audience## \n\n Vöru Nafn:\n ##title## \n\n Vörulýsing:\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 2291, 'template_id' => 63, 'key' => "id-ID", 'value' =>  "Tulis deskripsi profesional dan menarik untuk iklan LinkedIn dari produk berikut yang ditujukan untuk:\n\n ##audience## \n\n Nama Produk:\n ##title## \n\n Deskripsi Produk:\n ##description## \n\n Nada suara hasilnya harus:\n ##tone_language## \n\n"],

            ['id' => 2292, 'template_id' => 63, 'key' => "it-IT", 'value' =>  "Scrivi una descrizione professionale e accattivante per gli annunci LinkedIn del seguente prodotto mirato a:\n\n ##audience## \n\n Nome del prodotto:\n ##title## \n\n Descrizione del prodotto:\n ##description## \n\n Tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 2293, 'template_id' => 63, 'key' => "ja-JP", 'value' =>  "次の商品を対象とした LinkedIn 広告について、専門的で人目を引く説明を書いてください。\n\n ##audience## \n\n 商品名:\n ##title## \n\n 製品説明:\n ##description## \n\n 結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n"],

            ['id' => 2294, 'template_id' => 63, 'key' => "ko-KR", 'value' =>  "다음을 목표로 하는 다음 제품의 LinkedIn 광고에 대한 전문적이고 눈길을 끄는 설명을 작성하십시오.\n\n ##audience## \n\n 상품명:\n ##title## \n\n 제품 설명:\n ##description## \n\n 결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 2295, 'template_id' => 63, 'key' => "ms-MY", 'value' =>  "Tulis penerangan profesional dan menarik perhatian untuk iklan LinkedIn produk berikut yang bertujuan untuk:\n\n ##audience## \n\n Nama Produk:\n ##title## \n\n Penerangan Produk:\n ##description## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 2296, 'template_id' => 63, 'key' => "nb-NO", 'value' =>  "Skriv en profesjonell og iøynefallende beskrivelse for LinkedIn-annonsene for følgende produkt rettet mot:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 2297, 'template_id' => 63, 'key' => "pl-PL", 'value' =>  "Napisz profesjonalny i przyciągający uwagę opis reklamy LinkedIn następującego produktu skierowanej do:\n\n ##audience## \n\n Nazwa produktu:\n ##title## \n\n Opis produktu:\n ##description## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 2298, 'template_id' => 63, 'key' => "pt-PT", 'value' =>  "Escreva uma descrição profissional e atraente para os anúncios do LinkedIn do seguinte produto destinado a:\n\n ##audience## \n\n Nome do Produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2299, 'template_id' => 63, 'key' => "ru-RU", 'value' =>  "Напишите профессиональное и привлекательное описание для рекламы LinkedIn следующего продукта, нацеленного на:\n\n ##audience## \n\n Наименование товара:\n ##title## \n\n Описание продукта:\n ##description## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 2300, 'template_id' => 63, 'key' => "es-ES", 'value' =>  "Escriba una descripción profesional y llamativa para los anuncios de LinkedIn del siguiente producto dirigido a:\n\n ##audience## \n\n Nombre del producto:\n ##title## \n\n Descripción del Producto:\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 2301, 'template_id' => 63, 'key' => "sv-SE", 'value' =>  "Skriv en professionell och iögonfallande beskrivning för LinkedIn-annonserna för följande produkt som syftar till:\n\n ##audience## \n\n Produktnamn:\n ##title## \n\n Produktbeskrivning:\n ##description## \n\n Tonen i rösten för resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 2302, 'template_id' => 63, 'key' => "tr-TR", 'value' =>  "Aşağıdaki ürünün LinkedIn reklamları için profesyonel ve dikkat çekici bir açıklama yazın:\n\n ##audience## \n\n Ürün adı:\n ##title## \n\n Ürün Açıklaması:\n ##description## \n\n Sonucun ses tonu şöyle olmalıdır::\n ##tone_language## \n\n"],

            ['id' => 2303, 'template_id' => 63, 'key' => "pt-BR", 'value' =>  "Escreva uma descrição profissional e atraente para os anúncios do LinkedIn do seguinte produto destinado a:\n\n ##audience## \n\n Nome do Produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2304, 'template_id' => 63, 'key' => "ro-RO", 'value' =>  "Scrieți o descriere profesională și atrăgătoare pentru anunțurile LinkedIn ale următorului produs, care vizează:\n\n ##audience## \n\n Numele produsului:\n ##title## \n\n Descriere produs:\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 2305, 'template_id' => 63, 'key' => "vi-VN", 'value' =>  "Viết mô tả chuyên nghiệp và bắt mắt cho quảng cáo LinkedIn của sản phẩm sau nhằm mục đích:\n\n ##audience## \n\n Tên sản phẩm:\n ##title## \n\n Mô tả Sản phẩm:\n ##description## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 2306, 'template_id' => 63, 'key' => "sw-KE", 'value' =>  "Andika maelezo ya kitaalamu na ya kuvutia macho ya matangazo ya LinkedIn ya bidhaa ifuatayo yanayolenga:\n\n ##audience## \n\n Jina la bidhaa:\n ##title## \n\n Maelezo ya bidhaa:\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 2307, 'template_id' => 63, 'key' => "sl-SI", 'value' =>  "Napišite profesionalen in privlačen opis za oglase LinkedIn naslednjega izdelka, namenjenega:\n\n ##audience## \n\n Ime izdelka:\n ##title## \n\n Opis izdelka:\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2308, 'template_id' => 63, 'key' => "th-TH", 'value' =>  "เขียนคำอธิบายที่เป็นมืออาชีพและสะดุดตาสำหรับโฆษณา LinkedIn ของผลิตภัณฑ์ต่อไปนี้ซึ่งมุ่งเป้าไปที่:\n\n ##audience## \n\n ชื่อผลิตภัณฑ์:\n ##title## \n\n รายละเอียดสินค้า:\n ##description## \n\n น้ำเสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 2309, 'template_id' => 63, 'key' => "uk-UA", 'value' =>  "Напишіть професійний і привабливий опис для реклами LinkedIn наступного продукту, спрямованого на:\n\n ##audience## \n\n Назва продукту:\n ##title## \n\n Опис продукту:\n ##description## \n\n Тон голосу результату повинен бути:\n ##tone_language## \n\n"],

            ['id' => 2310, 'template_id' => 63, 'key' => "lt-LT", 'value' =>  "Parašykite profesionalų ir akį traukiantį šio produkto „LinkedIn“ skelbimų aprašą, skirtą:\n\n ##audience## \n\nProdukto pavadinimas:\n ##title## \n\n Prekės aprašymas:\n ##description## \n\n Turi būti rezultato balso tonas:\n ##tone_language## \n\n"],

            ['id' => 2311, 'template_id' => 63, 'key' => "bg-BG", 'value' =>  "Напишете професионално и привличащо вниманието описание за рекламите на LinkedIn на следния продукт, насочен към:\n\n ##audience## \n\n Име на продукта:\n ##title## \n\n Описание на продукта:\n ##description## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 2312, 'template_id' => 64, 'key' => "en-US", 'value' =>   "Write 10 catchy headlines for the LinkedIn ads of the following product aimed at:\n\n ##audience## \n\n Product name:\n ##title## \n\n Product description:\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 2313, 'template_id' => 64, 'key' => "ar-AE", 'value' =>   "اكتب 10 عناوين جذابة لإعلانات LinkedIn للمنتج التالي التي تستهدف:\n\n ##audience## \n\n اسم المنتج:\n ##title## \n\n وصف المنتج:\n ##description## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 2314, 'template_id' => 64, 'key' => "cmn-CN", 'value' =>   "為以下產品的 LinkedIn 廣告寫 10 個吸引人的標題，目的是：\n\n ##audience## \n\n 產品名稱:\n ##title## \n\n 產品描述:\n ##description## \n\n 結果的語氣必須是:\n ##tone_language## \n\n"],

            ['id' => 2315, 'template_id' => 64, 'key' => "hr-HR", 'value' =>   "Napišite 10 privlačnih naslova za LinkedIn oglase sljedećeg proizvoda koji ciljaju na:\n\n ##audience## \n\n Ime proizvoda:\n ##title## \n\n Opis proizvoda:\n ##description## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2316, 'template_id' => 64, 'key' => "cs-CZ", 'value' =>   "Napište 10 chytlavých titulků pro reklamy LinkedIn na následující produkt zaměřený na:\n\n ##audience## \n\n Jméno výrobku:\n ##title## \n\n Popis výrobku:\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 2317, 'template_id' => 64, 'key' => "da-DK", 'value' =>   "Skriv 10 fængende overskrifter til LinkedIn-annoncerne for følgende produkt rettet mod::\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produkt beskrivelse:\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 2318, 'template_id' => 64, 'key' => "nl-NL", 'value' =>   "Schrijf 10 pakkende koppen voor de LinkedIn-advertenties van het volgende product gericht op:\n\n ##audience## \n\n Productnaam:\n ##title## \n\n Product beschrijving:\n ##description## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 2319, 'template_id' => 64, 'key' => "et-EE", 'value' =>   "Kirjutage 10 meeldejäävat pealkirja järgmise toote LinkedIn reklaamidele, mis on suunatud:\n\n ##audience## \n\n Tootenimi:\n ##title## \n\n Tootekirjeldus:\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 2320, 'template_id' => 64, 'key' => "fi-FI", 'value' =>   "Kirjoita 10 tarttuvaa otsikkoa seuraavan tuotteen LinkedIn-mainoksille, jotka on tarkoitettu:\n\n ##audience## \n\n Tuotteen nimi:\n ##title## \n\n Tuotteen Kuvaus:\n ##description## \n\n Äänesävyn tuloksen on oltava:\n ##tone_language## \n\n"],

            ['id' => 2321, 'template_id' => 64, 'key' => "fr-FR", 'value' =>   "Rédigez 10 titres accrocheurs pour les publicités LinkedIn du produit suivant destiné à :\n\n ##audience## \n\n Nom du produit:\n ##title## \n\n Description du produit:\n ##description## \n\n Le ton de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 2322, 'template_id' => 64, 'key' => "de-DE", 'value' =>   "Schreiben Sie 10 einprägsame Schlagzeilen für die LinkedIn-Anzeigen des folgenden Produkts mit der Zielgruppe:\n\n ##audience## \n\n Produktname:\n ##title## \n\n Produktbeschreibung:\n ##description## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 2323, 'template_id' => 64, 'key' => "el-GR", 'value' =>   "Γράψτε 10 εντυπωσιακούς τίτλους για τις διαφημίσεις LinkedIn του παρακάτω προϊόντος που στοχεύουν:\n\n ##audience## \n\n  Ονομασία προϊόντος:\n ##title## \n\n Περιγραφή προϊόντος:\n ##description## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 2324, 'template_id' => 64, 'key' => "he-IL", 'value' =>   "כתוב 10 כותרות קליטות למודעות לינקדאין של המוצר הבא המיועדות ל:\n\n ##audience## \n\n שם מוצר:\n ##title## \n\n תיאור מוצר\n ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 2325, 'template_id' => 64, 'key' => "hi-IN", 'value' =>   "निम्नलिखित उत्पाद के लिंक्डइन विज्ञापनों के लिए 10 आकर्षक सुर्खियाँ लिखें जिनका उद्देश्य है:\n\n ##audience## \n\n प्रोडक्ट का नाम:\n ##title## \n\n उत्पाद वर्णन:\n ##description## \n\n परिणाम का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 2326, 'template_id' => 64, 'key' => "hu-HU", 'value' =>   "Írjon 10 fülbemászó címet a következő termék LinkedIn-hirdetéseihez, amelyek célja:\n\n ##audience## \n\n Termék név:\n ##title## \n\n  Termékleírás:\n ##description## \n\n Az eredmény hangnemének kell lennie:\n ##tone_language## \n\n"],

            ['id' => 2327, 'template_id' => 64, 'key' => "is-IS", 'value' =>   "Skrifaðu 10 grípandi fyrirsagnir fyrir LinkedIn auglýsingarnar fyrir eftirfarandi vöru sem miðar að:\n\n ##audience## \n\n Vöru Nafn:\n ##title## \n\n  Vörulýsing:\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 2328, 'template_id' => 64, 'key' => "id-ID", 'value' =>   "Tulis 10 tajuk utama yang menarik untuk iklan LinkedIn dari produk berikut yang ditujukan untuk:\n\n ##audience## \n\n Nama Produk:\n ##title## \n\n  Deskripsi Produk:\n ##description## \n\n Nada suara hasilnya harus:\n ##tone_language## \n\n"],

            ['id' => 2329, 'template_id' => 64, 'key' => "it-IT", 'value' =>   "Scrivi 10 titoli accattivanti per gli annunci LinkedIn del seguente prodotto mirato a:\n\n ##audience## \n\n Nome del prodotto:\n ##title## \n\n Descrizione del prodotto:\n ##description## \n\n Tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 2330, 'template_id' => 64, 'key' => "ja-JP", 'value' =>   "次の製品を対象とした LinkedIn 広告のキャッチーな見出しを 10 個書いてください。\n\n ##audience## \n\n 商品名:\n ##title## \n\n  製品説明:\n ##description## \n\n 結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n"],

            ['id' => 2331, 'template_id' => 64, 'key' => "ko-KR", 'value' =>   "다음을 목표로 하는 다음 제품의 LinkedIn 광고에 대한 10개의 눈길을 끄는 헤드라인을 작성하십시오.\n\n ##audience## \n\n 상품명:\n ##title## \n\n  제품 설명:\n ##description## \n\n 결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 2332, 'template_id' => 64, 'key' => "ms-MY", 'value' =>   "Tulis 10 tajuk yang menarik untuk iklan LinkedIn produk berikut bertujuan:\n\n ##audience## \n\n Nama Produk:\n ##title## \n\n  Penerangan Produk:\n ##description## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 2333, 'template_id' => 64, 'key' => "nb-NO", 'value' =>   "Skriv 10 fengende overskrifter for LinkedIn-annonsene for følgende produkt rettet mot:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n  Produktbeskrivelse:\n ##description## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 2334, 'template_id' => 64, 'key' => "pl-PL", 'value' =>   "Napisz 10 chwytliwych nagłówków do reklam LinkedIn następującego produktu, których celem jest:\n\n ##audience## \n\n Nazwa produktu:\n ##title## \n\n  Opis produktu:\n ##description## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 2335, 'template_id' => 64, 'key' => "pt-PT", 'value' =>   "Escreva 10 títulos cativantes para os anúncios do LinkedIn do seguinte produto destinado a:\n\n ##audience## \n\n Nome do Produto:\n ##title## \n\n  Descrição do produto:\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2336, 'template_id' => 64, 'key' => "ru-RU", 'value' =>   "Напишите 10 броских заголовков для рекламы LinkedIn следующего продукта, нацеленного на:\n\n ##audience## \n\n Наименование товара:\n ##title## \n\n  Описание продукта:\n ##description## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 2337, 'template_id' => 64, 'key' => "es-ES", 'value' =>   "Escriba 10 titulares atractivos para los anuncios de LinkedIn del siguiente producto dirigido a:\n\n ##audience## \n\n Nombre del producto:\n ##title## \n\n  Descripción del Producto:\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 2338, 'template_id' => 64, 'key' => "sv-SE", 'value' =>   "Skriv 10 catchy rubriker för LinkedIn-annonserna för följande produkt som syftar till:\n\n ##audience## \n\n Produktnamn:\n ##title## \n\n  Produktbeskrivning:\n ##description## \n\n Tonen i rösten för resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 2339, 'template_id' => 64, 'key' => "tr-TR", 'value' =>   "Aşağıdaki ürünün LinkedIn reklamları için akılda kalıcı 10 başlık yazın:\n\n ##audience## \n\n Ürün adı:\n ##title## \n\n  Ürün Açıklaması:\n ##description## \n\n Sonucun ses tonu şöyle olmalıdır::\n ##tone_language## \n\n"],

            ['id' => 2340, 'template_id' => 64, 'key' => "pt-BR", 'value' =>   "Escreva 10 títulos cativantes para os anúncios do LinkedIn do seguinte produto destinado a:\n\n ##audience## \n\n Nome do Produto:\n ##title## \n\n  Descrição do produto:\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2341, 'template_id' => 64, 'key' => "ro-RO", 'value' =>   "Scrieți 10 titluri captivante pentru reclamele LinkedIn ale următorului produs care vizează:\n\n ##audience## \n\n Numele produsului:\n ##title## \n\n  Descriere produs:\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 2342, 'template_id' => 64, 'key' => "vi-VN", 'value' =>   "Viết 10 tiêu đề hấp dẫn cho quảng cáo LinkedIn của sản phẩm sau nhằm mục đích:\n\n ##audience## \n\n Tên sản phẩm:\n ##title## \n\n  Mô tả Sản phẩm:\n ##description## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 2343, 'template_id' => 64, 'key' => "sw-KE", 'value' =>   "Andika vichwa 10 vya habari vya kuvutia vya matangazo ya LinkedIn vya bidhaa ifuatayo vinavyolenga:\n\n ##audience## \n\n Jina la bidhaa:\n ##title## \n\n  Maelezo ya bidhaa:\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 2344, 'template_id' => 64, 'key' => "sl-SI", 'value' =>   "Napišite 10 privlačnih naslovov za oglase LinkedIn naslednjega izdelka, namenjenega:\n\n ##audience## \n\n Ime izdelka:\n ##title## \n\n  Opis izdelka:\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2345, 'template_id' => 64, 'key' => "th-TH", 'value' =>   "เขียนหัวข้อข่าว 10 หัวข้อที่น่าสนใจสำหรับโฆษณา LinkedIn ของผลิตภัณฑ์ต่อไปนี้โดยมุ่งเป้าไปที่:\n\n ##audience## \n\n ชื่อผลิตภัณฑ์:\n ##title## \n\n  รายละเอียดสินค้า:\n ##description## \n\n เสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 2346, 'template_id' => 64, 'key' => "uk-UA", 'value' =>   "Напишіть 10 привабливих заголовків для реклами LinkedIn наступного продукту, спрямованого на:\n\n ##audience## \n\n Назва продукту:\n ##title## \n\n  Опис продукту:\n ##description## \n\n Тон голосу результату повинен бути:\n ##tone_language## \n\n"],

            ['id' => 2347, 'template_id' => 64, 'key' => "lt-LT", 'value' =>   "Parašykite 10 patrauklių antraščių šio produkto „LinkedIn“ skelbimams, skirtiems:\n\n ##audience## \n\n Produkto pavadinimas:\n ##title## \n\n  Prekės aprašymas:\n ##description## \n\n Turi būti rezultato balso tonas:\n ##tone_language## \n\n"],

            ['id' => 2348, 'template_id' => 64, 'key' => "bg-BG", 'value' =>   "Напишете 10 закачливи заглавия за рекламите на LinkedIn на следния продукт, насочен към:\n\n ##audience## \n\n Име на продукта:\n ##title## \n\n Описание на продукта:\n ##description## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 2349, 'template_id' => 65, 'key' => "en-US", 'value' =>   "Write interesting outlines for a Youtube video about:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 2350, 'template_id' => 65, 'key' => "ar-AE", 'value' =>   "اكتب مخططات شيقة لفيديو يوتيوب حول:\n\n ##description## \n\n  Tالوحيدة من صوت النتيجة يجب أن تكون:\n ##tone_language## \n\n"],

            ['id' => 2351, 'template_id' => 65, 'key' => "cmn-CN", 'value' =>   "為 Youtube 視訊撰寫有趣的大綱:\n\n ##description## \n\n 必須要有聲音的聲音:\n ##tone_language## \n\n"],

            ['id' => 2352, 'template_id' => 65, 'key' => "hr-HR", 'value' =>   "Napišite zanimljive nacrte za Youtube video o:\n\n ##description## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n"],

            ['id' => 2353, 'template_id' => 65, 'key' => "cs-CZ", 'value' =>   "Napište zajímavé osnovy pro video na YouTube o:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 2354, 'template_id' => 65, 'key' => "da-DK", 'value' =>   "Skriv interessante oplæg til en Youtube-video om:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 2355, 'template_id' => 65, 'key' => "nl-NL", 'value' =>   "Schrijf interessante contouren voor een YouTube-video over:\n\n ##description## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 2356, 'template_id' => 65, 'key' => "et-EE", 'value' =>   "Kirjutage huvitavaid konspekte Youtube'i video jaoks:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 2357, 'template_id' => 65, 'key' => "fi-FI", 'value' =>   "Kirjoita mielenkiintoisia pääpiirteitä Youtube-videolle aiheesta:\n\n ##description## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n"],

            ['id' => 2358, 'template_id' => 65, 'key' => "fr-FR", 'value' =>   "Rédigez des plans intéressants pour une vidéo Youtube sur:\n\n ##description## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 2359, 'template_id' => 65, 'key' => "de-DE", 'value' =>   "Schreiben Sie interessante Skizzen für ein YouTube-Video darüber:\n\n ##description## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n"],

            ['id' => 2360, 'template_id' => 65, 'key' => "el-GR", 'value' =>   "Γράψτε ενδιαφέροντα περιγράμματα για ένα βίντεο στο Youtube:\n\n ##description## \n\n Η φωνή του αποτελέσματος πρέπει να είναι ...:\n ##tone_language## \n\n"],

            ['id' => 2361, 'template_id' => 65, 'key' => "he-IL", 'value' =>   "כתוב קווי מתאר מעניינים לסרטון יוטיוב על:\n\n ##description## \n\n טון הדיבור של הפסקאות חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 2362, 'template_id' => 65, 'key' => "hi-IN", 'value' =>   "के बारे में एक Youtube वीडियो के लिए रोचक रूपरेखा लिखें:\n\n ##description## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n"],

            ['id' => 2363, 'template_id' => 65, 'key' => "hu-HU", 'value' =>   "Írj érdekes vázlatokat egy Youtube-videóhoz, amelyről szól:\n\n ##description## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n"],

            ['id' => 2364, 'template_id' => 65, 'key' => "is-IS", 'value' =>   "Skrifaðu áhugaverðar útlínur fyrir Youtube myndband um:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 2365, 'template_id' => 65, 'key' => "id-ID", 'value' =>   "Tulis garis besar yang menarik untuk video Youtube tentang:\n\n ##description## \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n"],

            ['id' => 2366, 'template_id' => 65, 'key' => "it-IT", 'value' =>   "Scrivi schemi interessanti per un video di Youtube su:\n\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 2367, 'template_id' => 65, 'key' => "ja-JP", 'value' =>   "～についての YouTube ビデオの興味深い概要を書きます:\n\n ##description## \n\n 結果の声のトーンは、:\n ##tone_language## \n\n"],

            ['id' => 2368, 'template_id' => 65, 'key' => "ko-KR", 'value' =>   "YouTube 비디오에 대한 흥미로운 개요를 작성하십시오.:\n\n ##description## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 2369, 'template_id' => 65, 'key' => "ms-MY", 'value' =>   "Tulis garis besar yang menarik untuk video Youtube tentang:\n\n ##description## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n"],

            ['id' => 2370, 'template_id' => 65, 'key' => "nb-NO", 'value' =>   "Skriv interessante skisser for en Youtube-video om:\n\n ##description## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 2371, 'template_id' => 65, 'key' => "pl-PL", 'value' =>   "Napisz ciekawe konspekty do filmu na Youtube o:\n\n ##description## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 2372, 'template_id' => 65, 'key' => "pt-PT", 'value' =>   "Escreva esboços interessantes para um vídeo do Youtube sobre:\n\n ##description## \n\n Tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2373, 'template_id' => 65, 'key' => "ru-RU", 'value' =>   "Напишите интересные наброски для видео на Youtube о:\n\n ##description## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 2374, 'template_id' => 65, 'key' => "es-ES", 'value' =>   "Escriba esquemas interesantes para un video de Youtube sobre:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 2375, 'template_id' => 65, 'key' => "sv-SE", 'value' =>   "Skriv intressanta konturer för en Youtube-video om:\n\n ##description## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 2376, 'template_id' => 65, 'key' => "tr-TR", 'value' =>   "Hakkında bir Youtube videosu için ilginç ana hatlar yazın:\n\n ##description## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n"],

            ['id' => 2377, 'template_id' => 65, 'key' => "pt-BR", 'value' =>   "Escreva esboços interessantes para um vídeo do Youtube sobre:\n\n ##description## \n\n Tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2378, 'template_id' => 65, 'key' => "ro-RO", 'value' =>   "Scrieți schițe interesante pentru un videoclip YouTube despre:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 2379, 'template_id' => 65, 'key' => "vi-VN", 'value' =>   "Viết dàn ý thú vị cho một video Youtube về:\n\n ##description## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 2380, 'template_id' => 65, 'key' => "sw-KE", 'value' =>   "Andika muhtasari wa kuvutia wa video ya Youtube kuhusu:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 2381, 'template_id' => 65, 'key' => "sl-SI", 'value' =>   "Napišite zanimive osnutke za Youtube video o:\n\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2382, 'template_id' => 65, 'key' => "th-TH", 'value' =>   "เขียนโครงร่างที่น่าสนใจสำหรับวิดีโอ Youtube เกี่ยวกับ:\n\n ##description## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 2383, 'template_id' => 65, 'key' => "uk-UA", 'value' =>   "Напишіть цікаві плани для відео на Youtube про:\n\n ##description## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n"],

            ['id' => 2384, 'template_id' => 65, 'key' => "lt-LT", 'value' =>   "Parašykite įdomius „YouTube“ vaizdo įrašo kontūrus:\n\n ##description## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n"],

            ['id' => 2385, 'template_id' => 65, 'key' => "bg-BG", 'value' =>   "Напишете интересни очертания за видеоклип в Youtube за:\n\n ##description## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 2386, 'template_id' => 66, 'key' => "en-US", 'value' =>   "Generate engaging twitter threads based on a ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 2387, 'template_id' => 66, 'key' => "ar-AE", 'value' =>   "إنشاء مواضيع تويتر جذابة على أساس أ ##description## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 2388, 'template_id' => 66, 'key' => "cmn-CN", 'value' =>   "基于 ##description## \n\n 结果的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 2389, 'template_id' => 66, 'key' => "hr-HR", 'value' =>   "Generirajte zanimljive teme na Twitteru na temelju a ##description## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2390, 'template_id' => 66, 'key' => "cs-CZ", 'value' =>   "Generujte poutavá twitterová vlákna na základě a ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 2391, 'template_id' => 66, 'key' => "da-DK", 'value' =>   "Generer engagerende twitter-tråde baseret på en ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 2392, 'template_id' => 66, 'key' => "nl-NL", 'value' =>   "Genereer boeiende Twitter-threads op basis van een ##description## \n\n Tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 2393, 'template_id' => 66, 'key' => "et-EE", 'value' =>   "Looge köitvaid Twitteri lõime, mis põhinevad a ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 2394, 'template_id' => 66, 'key' => "fi-FI", 'value' =>   "Luo kiinnostavia twitter-säikeitä a ##description## \n\n Tuloksen äänensävyn tulee olla:\n ##tone_language## \n\n"],

            ['id' => 2395, 'template_id' => 66, 'key' => "fr-FR", 'value' =>   "Générez des fils Twitter attrayants basés sur un ##description## \n\n Le ton de voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 2396, 'template_id' => 66, 'key' => "de-DE", 'value' =>   "Generieren Sie ansprechende Twitter-Threads basierend auf a ##description## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 2397, 'template_id' => 66, 'key' => "el-GR", 'value' =>   "Δημιουργήστε ελκυστικά νήματα στο twitter με βάση το α ##description## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 2398, 'template_id' => 66, 'key' => "he-IL", 'value' =>   "צור שרשורי טוויטר מרתקים המבוססים על א ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 2399, 'template_id' => 66, 'key' => "hi-IN", 'value' =>   "एक के आधार पर आकर्षक ट्विटर सूत्र उत्पन्न करें ##description## \n\n परिणाम की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 2400, 'template_id' => 66, 'key' => "hu-HU", 'value' =>   "Lebilincselő twitter-szálak létrehozása a ##description## \n\n Az eredmény hangnemének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 2401, 'template_id' => 66, 'key' => "is-IS", 'value' =>   "Búðu til grípandi twitter þræði byggða á a ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 2402, 'template_id' => 66, 'key' => "id-ID", 'value' =>   "Hasilkan utas twitter yang menarik berdasarkan a ##description## \n\n Nada suara hasil harus:\n ##tone_language## \n\n"],

            ['id' => 2403, 'template_id' => 66, 'key' => "it-IT", 'value' =>   "Genera thread Twitter coinvolgenti basati su a ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 2404, 'template_id' => 66, 'key' => "ja-JP", 'value' =>   "に基づいて魅力的な Twitter スレッドを生成します ##description## \n\n 結果の口調は次のようになります:\n ##tone_language## \n\n"],

            ['id' => 2405, 'template_id' => 66, 'key' => "ko-KR", 'value' =>   "기반으로 매력적인 트위터 스레드 생성 ##description## \n\n 결과의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 2406, 'template_id' => 66, 'key' => "ms-MY", 'value' =>   "Hasilkan benang twitter yang menarik berdasarkan a ##description## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 2407, 'template_id' => 66, 'key' => "nb-NO", 'value' =>   "Generer engasjerende twitter-tråder basert på en ##description## \n\n Stemmetonen for resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 2408, 'template_id' => 66, 'key' => "pl-PL", 'value' =>   "Generuj angażujące wątki na Twitterze w oparciu o ##description## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 2409, 'template_id' => 66, 'key' => "pt-PT", 'value' =>   "Gere tópicos envolventes no Twitter com base em um ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2410, 'template_id' => 66, 'key' => "ru-RU", 'value' =>   "Создавайте привлекательные темы в Твиттере на основе ##description## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 2411, 'template_id' => 66, 'key' => "es-ES", 'value' =>   "Genere hilos de twitter atractivos basados ​​en un ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 2412, 'template_id' => 66, 'key' => "sv-SE", 'value' =>   "Skapa engagerande twittertrådar baserat på en ##description## \n\n Tonen i resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 2413, 'template_id' => 66, 'key' => "tr-TR", 'value' =>   "Bir temele dayalı ilgi çekici twitter ileti dizileri oluşturun ##description## \n\n Sonucun ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 2414, 'template_id' => 66, 'key' => "pt-BR", 'value' =>   "Gerar tópicos envolventes no Twitter com base em um ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2415, 'template_id' => 66, 'key' => "ro-RO", 'value' =>   "Generați fire de twitter captivante pe baza a ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 2416, 'template_id' => 66, 'key' => "vi-VN", 'value' =>   "Tạo chủ đề twitter hấp dẫn dựa trên ##description## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 2417, 'template_id' => 66, 'key' => "sw-KE", 'value' =>   "Tengeneza nyuzi zinazovutia za twitter kulingana na a ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 2418, 'template_id' => 66, 'key' => "sl-SI", 'value' =>   "Ustvarite privlačne niti na Twitterju na podlagi a ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2419, 'template_id' => 66, 'key' => "th-TH", 'value' =>   "สร้างเธรด Twitter ที่น่าสนใจตาม ##description## \n\n โทนเสียงของผลลัพธ์ต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 2420, 'template_id' => 66, 'key' => "uk-UA", 'value' =>   "Створюйте захоплюючі теми Twitter на основі a ##description## \n\n Тон голосу результату повинен бути таким:\n ##tone_language## \n\n"],

            ['id' => 2421, 'template_id' => 66, 'key' => "lt-LT", 'value' =>   "Kurkite patrauklias Twitter gijas pagal a ##description## \n\n Rezultato balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 2422, 'template_id' => 66, 'key' => "bg-BG", 'value' =>   "Генерирайте ангажиращи нишки в Twitter въз основа на a ##description## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 2423, 'template_id' => 67, 'key' => "en-US", 'value' =>   "Generate social post captions ready to grab attention adbout:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 2424, 'template_id' => 67, 'key' => "ar-AE", 'value' =>   "أنشئ تعليقات منشورات اجتماعية جاهزة لجذب الانتباه حول:\n\n ##description## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 2425, 'template_id' => 67, 'key' => "cmn-CN", 'value' =>   "生成社交帖子标题以吸引注意力：\n\n ##description## \n\n 结果的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 2426, 'template_id' => 67, 'key' => "hr-HR", 'value' =>   "Generirajte naslove postova na društvenim mrežama spremne da privuku pozornost o:\n\n ##description## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2427, 'template_id' => 67, 'key' => "cs-CZ", 'value' =>   "Vytvářejte titulky sociálních příspěvků připravené upoutat pozornost na:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 2428, 'template_id' => 67, 'key' => "da-DK", 'value' =>   "Generer sociale indlægstekster klar til at fange opmærksomhed om:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 2429, 'template_id' => 67, 'key' => "nl-NL", 'value' =>   "Genereer bijschriften voor sociale berichten die klaar zijn om de aandacht te trekken over:\n\n ##description## \n\n Tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 2430, 'template_id' => 67, 'key' => "et-EE", 'value' =>   "Looge sotsiaalsete postituste subtiitreid, mis on valmis tähelepanu köitma järgmistel teemadel:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 2431, 'template_id' => 67, 'key' => "fi-FI", 'value' =>   "Luo sosiaalisten viestien kuvatekstejä, jotka ovat valmiita kiinnittämään huomiota:\n\n ##description## \n\n Tuloksen äänensävyn tulee olla:\n ##tone_language## \n\n"],

            ['id' => 2432, 'template_id' => 67, 'key' => "fr-FR", 'value' =>   "Générez des légendes de publications sociales prêtes à attirer l'attention sur:\n\n ##description## \n\n Le ton de voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 2433, 'template_id' => 67, 'key' => "de-DE", 'value' =>   "Erstellen Sie Bildunterschriften für Social-Media-Beiträge, die Aufmerksamkeit erregen:\n\n ##description## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 2434, 'template_id' => 67, 'key' => "el-GR", 'value' =>   "Δημιουργήστε υπότιτλους αναρτήσεων κοινωνικής δικτύωσης έτοιμοι να τραβήξουν την προσοχή σχετικά με:\n\n ##description## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 2435, 'template_id' => 67, 'key' => "he-IL", 'value' =>   "צור כתוביות לפוסטים חברתיים מוכנים למשוך תשומת לב לגבי:\n\n ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 2436, 'template_id' => 67, 'key' => "hi-IN", 'value' =>   "इस बारे में ध्यान आकर्षित करने के लिए तैयार सामाजिक पोस्ट कैप्शन तैयार करें:\n\n ##description## \n\n परिणाम की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 2437, 'template_id' => 67, 'key' => "hu-HU", 'value' =>   "Hozzon létre közösségi bejegyzések feliratait, amelyek felkelthetik a figyelmet:\n\n ##description## \n\n Az eredmény hangnemének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 2438, 'template_id' => 67, 'key' => "is-IS", 'value' =>   "Búðu til myndatexta fyrir félagslegar færslur tilbúnar til að vekja athygli á:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 2439, 'template_id' => 67, 'key' => "id-ID", 'value' =>   "Hasilkan teks pos sosial yang siap menarik perhatian tentang:\n\n ##description## \n\n Nada suara hasil harus:\n ##tone_language## \n\n"],

            ['id' => 2440, 'template_id' => 67, 'key' => "it-IT", 'value' =>   "Genera didascalie per post social pronte ad attirare l'attenzione su:\n\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 2441, 'template_id' => 67, 'key' => "ja-JP", 'value' =>   "以下について注目を集めるソーシャル投稿のキャプションを生成します:\n\n ##description## \n\n 結果の口調は次のようになります:\n ##tone_language## \n\n"],

            ['id' => 2442, 'template_id' => 67, 'key' => "ko-KR", 'value' =>   "관심을 끌 준비가 된 소셜 게시물 캡션 생성:\n\n ##description## \n\n 결과의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 2443, 'template_id' => 67, 'key' => "ms-MY", 'value' =>   "Hasilkan kapsyen siaran sosial sedia untuk menarik perhatian tentang:\n\n ##description## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 2444, 'template_id' => 67, 'key' => "nb-NO", 'value' =>   "Generer sosiale innleggstekster klare til å fange oppmerksomhet om:\n\n ##description## \n\n Stemmetonen for resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 2445, 'template_id' => 67, 'key' => "pl-PL", 'value' =>   "Generuj podpisy postów w mediach społecznościowych gotowe do przyciągnięcia uwagi na temat:\n\n ##description## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 2446, 'template_id' => 67, 'key' => "pt-PT", 'value' =>   "Gere legendas de postagens sociais prontas para chamar a atenção sobre:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2447, 'template_id' => 67, 'key' => "ru-RU", 'value' =>   "Создавайте подписи к постам в соцсетях, готовые привлечь внимание:\n\n ##description## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 2448, 'template_id' => 67, 'key' => "es-ES", 'value' =>   "Genere subtítulos de publicaciones sociales listos para llamar la atención sobre:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 2449, 'template_id' => 67, 'key' => "sv-SE", 'value' =>   "Skapa sociala inläggstexter redo att fånga uppmärksamhet om:\n\n ##description## \n\n Tonen i resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 2450, 'template_id' => 67, 'key' => "tr-TR", 'value' =>   "Aşağıdaki konularda dikkat çekmeye hazır sosyal gönderi altyazıları oluşturun:\n\n ##description## \n\n Sonucun ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 2451, 'template_id' => 67, 'key' => "pt-BR", 'value' =>   "Gere legendas de publicações sociais prontas para chamar a atenção:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2452, 'template_id' => 67, 'key' => "ro-RO", 'value' =>   "Generați subtitrări pentru postările sociale gata să atragă atenția despre:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 2453, 'template_id' => 67, 'key' => "vi-VN", 'value' =>   "Tạo chú thích bài đăng xã hội sẵn sàng thu hút sự chú ý về:\n\n ##description## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 2454, 'template_id' => 67, 'key' => "sw-KE", 'value' =>   "Tengeneza vichwa vya machapisho ya kijamii tayari kuvutia umakini kuhusu:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 2455, 'template_id' => 67, 'key' => "sl-SI", 'value' =>   "Ustvarite napise družabnih objav, ki bodo pripravljeni pritegniti pozornost na:\n\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2456, 'template_id' => 67, 'key' => "th-TH", 'value' =>   "สร้างคำอธิบายภาพโพสต์โซเชียลพร้อมที่จะดึงดูดความสนใจเกี่ยวกับ:\n\n ##description## \n\n โทนเสียงของผลลัพธ์ต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 2457, 'template_id' => 67, 'key' => "uk-UA", 'value' =>   "Створюйте підписи до публікацій у соціальних мережах, щоб привернути увагу до:\n\n ##description## \n\n Тон голосу результату повинен бути таким:\n ##tone_language## \n\n"],

            ['id' => 2458, 'template_id' => 67, 'key' => "lt-LT", 'value' =>   "Generuokite socialinių pranešimų antraštes, paruoštas atkreipti dėmesį į:\n\n ##description## \n\n Rezultato balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 2459, 'template_id' => 67, 'key' => "bg-BG", 'value' =>   "Генерирайте надписи за социални публикации, готови да привлекат вниманието за:\n\n ##description## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 2460, 'template_id' => 68, 'key' => "en-US", 'value' =>   "Generate youtube channel intro to grab attention adbout:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 2461, 'template_id' => 68, 'key' => "ar-AE", 'value' =>   "أنشئ مقدمة لقناة youtube لجذب الانتباه حول:\n\n ##description## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 2462, 'template_id' => 68, 'key' => "cmn-CN", 'value' =>   "生成 youtube 频道介绍以吸引关注：\n\n ##description## \n\n 结果的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 2463, 'template_id' => 68, 'key' => "hr-HR", 'value' =>   "Generirajte uvod za youtube kanal kako biste privukli pozornost na:\n\n ##description## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2464, 'template_id' => 68, 'key' => "cs-CZ", 'value' =>   "Vygenerujte úvod kanálu youtube, abyste upoutali pozornost na:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 2465, 'template_id' => 68, 'key' => "da-DK", 'value' =>   "Generer YouTube-kanalintro for at fange opmærksomhed om:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 2466, 'template_id' => 68, 'key' => "nl-NL", 'value' =>   "Genereer YouTube-kanaalintro om de aandacht te trekken over:\n\n ##description## \n\n Tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 2467, 'template_id' => 68, 'key' => "et-EE", 'value' =>   "Looge YouTube'i kanali tutvustus, et köita tähelepanu järgmistel teemadel:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 2468, 'template_id' => 68, 'key' => "fi-FI", 'value' =>   "Luo YouTube-kanavan esittely herättääksesi huomion:\n\n ##description## \n\n Tuloksen äänensävyn tulee olla:\n ##tone_language## \n\n"],

            ['id' => 2469, 'template_id' => 68, 'key' => "fr-FR", 'value' =>   "Générez une introduction de chaîne YouTube pour attirer l'attention sur :\n\n ##description## \n\n Le ton de voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 2470, 'template_id' => 68, 'key' => "de-DE", 'value' =>   "Erstellen Sie ein YouTube-Kanal-Intro, um Aufmerksamkeit zu erregen auf:\n\n ##description## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 2471, 'template_id' => 68, 'key' => "el-GR", 'value' =>   "Δημιουργήστε εισαγωγή καναλιού YouTube για να τραβήξετε την προσοχή σχετικά με:\n\n ##description## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 2472, 'template_id' => 68, 'key' => "he-IL", 'value' =>   "צור מבוא ערוץ יוטיוב כדי למשוך תשומת לב לגבי:\n\n ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 2473, 'template_id' => 68, 'key' => "hi-IN", 'value' =>   "इस बारे में ध्यान आकर्षित करने के लिए यूट्यूब चैनल का परिचय तैयार करें:\n\n ##description## \n\n परिणाम की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 2474, 'template_id' => 68, 'key' => "hu-HU", 'value' =>   "Hozzon létre egy YouTube-csatorna bevezetőt, hogy felhívja magára a figyelmet:\n\n ##description## \n\n Az eredmény hangnemének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 2475, 'template_id' => 68, 'key' => "is-IS", 'value' =>   "Búðu til kynningu á YouTube rás til að vekja athygli á:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 2476, 'template_id' => 68, 'key' => "id-ID", 'value' =>   "Hasilkan intro saluran youtube untuk menarik perhatian tentang:\n\n ##description## \n\n Nada suara hasil harus:\n ##tone_language## \n\n"],

            ['id' => 2477, 'template_id' => 68, 'key' => "it-IT", 'value' =>   "Genera l'introduzione del canale YouTube per attirare l'attenzione su:\n\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 2478, 'template_id' => 68, 'key' => "ja-JP", 'value' =>   "YouTube チャンネルのイントロを生成して、次の点について注目を集めます:\n\n ##description## \n\n 結果の口調は次のようになります:\n ##tone_language## \n\n"],

            ['id' => 2479, 'template_id' => 68, 'key' => "ko-KR", 'value' =>   "다음에 대한 관심을 끌기 위해 YouTube 채널 소개를 생성합니다:\n\n ##description## \n\n 결과의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 2480, 'template_id' => 68, 'key' => "ms-MY", 'value' =>   "Hasilkan pengenalan saluran youtube untuk menarik perhatian tentang:\n\n ##description## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 2481, 'template_id' => 68, 'key' => "nb-NO", 'value' =>   "Generer youtube-kanalintro for å fange oppmerksomhet om:\n\n ##description## \n\n Stemmetonen for resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 2482, 'template_id' => 68, 'key' => "pl-PL", 'value' =>   "Wygeneruj wprowadzenie do kanału YouTube, aby zwrócić uwagę na:\n\n ##description## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 2483, 'template_id' => 68, 'key' => "pt-PT", 'value' =>   "Gere a introdução do canal do youtube para chamar a atenção sobre:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2484, 'template_id' => 68, 'key' => "ru-RU", 'value' =>   "Создайте интро для канала YouTube, чтобы привлечь внимание:\n\n ##description## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 2485, 'template_id' => 68, 'key' => "es-ES", 'value' =>   "Genere la introducción del canal de YouTube para llamar la atención sobre:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 2486, 'template_id' => 68, 'key' => "sv-SE", 'value' =>   "Skapa youtube-kanalintro för att fånga uppmärksamhet om:\n\n ##description## \n\n Tonen i resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 2487, 'template_id' => 68, 'key' => "tr-TR", 'value' =>   "Dikkat çekmek için youtube kanalı tanıtımı oluşturun:\n\n ##description## \n\n Sonucun ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 2488, 'template_id' => 68, 'key' => "pt-BR", 'value' =>   "Gerar uma introdução de canal do YouTube para chamar a atenção:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2489, 'template_id' => 68, 'key' => "ro-RO", 'value' =>   "Generați introducerea canalului YouTube pentru a atrage atenția despre:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 2490, 'template_id' => 68, 'key' => "vi-VN", 'value' =>   "Tạo phần giới thiệu kênh youtube để thu hút sự chú ý về:\n\n ##description## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 2491, 'template_id' => 68, 'key' => "sw-KE", 'value' =>   "Tengeneza utangulizi wa kituo cha youtube ili kuvutia umakini kuhusu:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 2492, 'template_id' => 68, 'key' => "sl-SI", 'value' =>   "Ustvarite uvod v youtube kanal, da pritegnete pozornost na:\n\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2493, 'template_id' => 68, 'key' => "th-TH", 'value' =>   "สร้างบทนำช่อง YouTube เพื่อดึงดูดความสนใจเกี่ยวกับ:\n\n ##description## \n\n โทนเสียงของผลลัพธ์ต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 2494, 'template_id' => 68, 'key' => "uk-UA", 'value' =>   "Створіть заставку для каналу YouTube, щоб привернути увагу до:\n\n ##description## \n\n Тон голосу результату повинен бути таким:\n ##tone_language## \n\n"],

            ['id' => 2495, 'template_id' => 68, 'key' => "lt-LT", 'value' =>   "Sukurkite „YouTube“ kanalo įvadą, kad patrauktumėte dėmesį:\n\n ##description## \n\n Rezultato balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 2496, 'template_id' => 68, 'key' => "bg-BG", 'value' =>   "Генерирайте въведение в канал в YouTube, за да привлечете вниманието към:\n\n ##description## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 2497, 'template_id' => 69, 'key' => "en-US", 'value' =>   "Write trendy hashtags for video  about:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 2498, 'template_id' => 69, 'key' => "ar-AE", 'value' =>   "اكتب علامات التجزئة العصرية للفيديو حول:\n\n ##description## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 2499, 'template_id' => 69, 'key' => "cmn-CN", 'value' =>   "撰寫有關視訊的趨勢雜湊標籤:\n\n ##description## \n\n 必須要有聲音的聲音:\n ##tone_language## \n\n"],

            ['id' => 2500, 'template_id' => 69, 'key' => "hr-HR", 'value' =>   "Napišite trendi hashtagove za video o tome:\n\n ##description## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2501, 'template_id' => 69, 'key' => "cs-CZ", 'value' =>   "Napište trendy hashtagy pro video o:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 2502, 'template_id' => 69, 'key' => "da-DK", 'value' =>   "Skriv trendy hashtags til video om:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 2503, 'template_id' => 69, 'key' => "nl-NL", 'value' =>   "Schrijf trendy hashtags voor video over:\n\n ##description## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 2504, 'template_id' => 69, 'key' => "et-EE", 'value' =>   "Kirjutage trendikaid räsimärke video jaoks:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 2505, 'template_id' => 69, 'key' => "fi-FI", 'value' =>   "Kirjoita trendikkäitä hashtageja videoon aiheesta:\n\n ##description## \n\n Äänesävyn tuloksen on oltava:\n ##tone_language## \n\n"],

            ['id' => 2506, 'template_id' => 69, 'key' => "fr-FR", 'value' =>   "Écrivez des hashtags à la mode pour la vidéo sur:\n\n ##description## \n\n Le ton de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 2507, 'template_id' => 69, 'key' => "de-DE", 'value' =>   "Schreiben Sie trendige Hashtags für Videos darüber:\n\n ##description## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 2508, 'template_id' => 69, 'key' => "el-GR", 'value' =>   "Γράψτε μοντέρνα hashtags για βίντεο:\n\n ##description## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 2509, 'template_id' => 69, 'key' => "he-IL", 'value' =>   "כתוב האשטאגים אופנתיים לסרטון על:\n\n ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 2510, 'template_id' => 69, 'key' => "hi-IN", 'value' =>   "वीडियो के बारे में ट्रेंडी हैशटैग लिखें:\n\n ##description## \n\n परिणाम का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 2511, 'template_id' => 69, 'key' => "hu-HU", 'value' =>   "Írj divatos hashtageket a videóhoz:\n\n ##description## \n\n Az eredmény hangnemének kell lennie:\n ##tone_language## \n\n"],

            ['id' => 2512, 'template_id' => 69, 'key' => "is-IS", 'value' =>   "Skrifaðu töff hashtags fyrir myndband um:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 2513, 'template_id' => 69, 'key' => "id-ID", 'value' =>   "Tulis tagar trendi untuk video tentang:\n\n ##description## \n\n Nada suara hasilnya harus:\n ##tone_language## \n\n"],

            ['id' => 2514, 'template_id' => 69, 'key' => "it-IT", 'value' =>   "Scrivi hashtag alla moda per i video su:\n\n ##description## \n\n Tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 2515, 'template_id' => 69, 'key' => "ja-JP", 'value' =>   "についての動画に流行のハッシュタグを書きます:\n\n ##description## \n\n 結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n"],

            ['id' => 2516, 'template_id' => 69, 'key' => "ko-KR", 'value' =>   "동영상에 대한 최신 해시태그를 작성하세요.:\n\n ##description## \n\n 결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 2517, 'template_id' => 69, 'key' => "ms-MY", 'value' =>   "Tulis hashtag yang bergaya untuk video tentang:\n\n ##description## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 2518, 'template_id' => 69, 'key' => "nb-NO", 'value' =>   "Skriv trendy hashtags for video om:\n\n ##description## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 2519, 'template_id' => 69, 'key' => "pl-PL", 'value' =>   "Napisz modne hashtagi do filmu o:\n\n ##description## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 2520, 'template_id' => 69, 'key' => "pt-PT", 'value' =>   "Escreva hashtags da moda para vídeos sobre:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2521, 'template_id' => 69, 'key' => "ru-RU", 'value' =>   "Пишите модные хэштеги для видео о:\n\n ##description## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 2522, 'template_id' => 69, 'key' => "es-ES", 'value' =>   "Escribe hashtags de moda para videos sobre:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 2523, 'template_id' => 69, 'key' => "sv-SE", 'value' =>   "Skriv trendiga hashtags för video om:\n\n ##description## \n\n Tonen i rösten för resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 2524, 'template_id' => 69, 'key' => "tr-TR", 'value' =>   "hakkında video için modaya uygun hashtag'ler yazın:\n\n ##description## \n\n Sonucun ses tonu şöyle olmalıdır::\n ##tone_language## \n\n"],

            ['id' => 2525, 'template_id' => 69, 'key' => "pt-BR", 'value' =>   "Escreva hashtags da moda para vídeos sobre:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2526, 'template_id' => 69, 'key' => "ro-RO", 'value' =>   "Scrie hashtag-uri la modă pentru videoclipuri despre:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 2527, 'template_id' => 69, 'key' => "vi-VN", 'value' =>   "Viết các thẻ bắt đầu bằng # hợp thời trang cho video về:\n\n ##description## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 2528, 'template_id' => 69, 'key' => "sw-KE", 'value' =>   "Andika lebo za reli maarufu za video kuhusu:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 2529, 'template_id' => 69, 'key' => "sl-SI", 'value' =>   "Napišite trendovske hashtage za video o:\n\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2530, 'template_id' => 69, 'key' => "th-TH", 'value' =>   "เขียนแฮชแท็กยอดนิยมสำหรับวิดีโอเกี่ยวกับ:\n\n ##description## \n\n เสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 2531, 'template_id' => 69, 'key' => "uk-UA", 'value' =>   "Напишіть модні хештеги для відео про:\n\n ##description## \n\n Тон голосу результату повинен бути:\n ##tone_language## \n\n"],

            ['id' => 2532, 'template_id' => 69, 'key' => "lt-LT", 'value' =>   "Rašykite madingas žymas su grotelėmis vaizdo įrašams apie:\n\n ##description## \n\n Turi būti rezultato balso tonas:\n ##tone_language## \n\n"],

            ['id' => 2533, 'template_id' => 69, 'key' => "bg-BG", 'value' =>   "Write trendy hashtags for video  about:\n\n ##description## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 2534, 'template_id' => 70, 'key' => "en-US", 'value' =>   "Write 10 short and simple article outlines about:\n\n ##description## \n\nBlog article title:\n ##title## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 2535, 'template_id' => 70, 'key' => "ar-AE", 'value' =>   "اكتب 10 مقال موجز وبسيط الخطوط العريضة عنه:\n\n ##description## \n\nعنوان مقال المدونة:\n ##title## \n\n Tالوحيدة من صوت النتيجة يجب أن تكون:\n ##tone_language## \n\n"],

            ['id' => 2536, 'template_id' => 70, 'key' => "cmn-CN", 'value' =>   "撰寫 10 篇簡短且簡單的文章概述:\n\n ##description## \n\n部落格文章標題:\n ##title## \n\n 必須要有聲音的聲音:\n ##tone_language## \n\n"],

            ['id' => 2537, 'template_id' => 70, 'key' => "hr-HR", 'value' =>   "Napišite 10 kratkih i jednostavnih nacrta članaka o:\n\n ##description## \n\nNaslov članka na blogu:\n ##title## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n"],

            ['id' => 2538, 'template_id' => 70, 'key' => "cs-CZ", 'value' =>   "Napište 10 krátkých a jednoduchých nástin článku o:\n\n ##description## \n\nNázev článku na blogu:\n ##title## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 2539, 'template_id' => 70, 'key' => "da-DK", 'value' =>   "Skriv 10 korte og enkle artikeloversigter om:\n\n ##description## \n\nBlog artiklens titel:\n ##title## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 2540, 'template_id' => 70, 'key' => "nl-NL", 'value' =>   "Schrijf 10 korte en eenvoudige artikeloverzichten over:\n\n ##description## \n\nTitel blogartikele:\n ##title## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 2541, 'template_id' => 70, 'key' => "et-EE", 'value' =>   "Kirjutage 10 lühikest ja lihtsat artikli ülevaadet:\n\n ##description## \n\nBlogi artikli pealkiri:\n ##title## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 2542, 'template_id' => 70, 'key' => "fi-FI", 'value' =>   "Kirjoita 10 lyhyttä ja yksinkertaista artikkelin päättelyä aiheesta:\n\n ##description## \n\nBlogin artikkelin otsikko:\n ##title## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n"],

            ['id' => 2543, 'template_id' => 70, 'key' => "fr-FR", 'value' =>   "Rédigez 10 résumés d'articles courts et simples sur:\n\n ##description## \n\nBTitre de l'article du blog:\n ##title## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 2544, 'template_id' => 70, 'key' => "de-DE", 'value' =>   "Schreiben Sie 10 kurze und einfache Artikelskizzen zum Thema:\n\n ##description## \n\nTitel des Blogartikels:\n ##title## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n"],

            ['id' => 2545, 'template_id' => 70, 'key' => "el-GR", 'value' =>   "Γράψτε 10 σύντομες και απλές περιγραφές άρθρων για:\n\n ##description## \n\nΤίτλος άρθρου ιστολογίου:\n ##title## \n\n Η φωνή του αποτελέσματος πρέπει να είναι ...:\n ##tone_language## \n\n"],

            ['id' => 2546, 'template_id' => 70, 'key' => "he-IL", 'value' =>   "כתוב 10 קווי מתאר קצרים ופשוטים של מאמרים בנושא:\n\n ##description## \n\nכותרת המאמר בבלוג:\n ##title## \n\n טון הדיבור של הפסקאות חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 2547, 'template_id' => 70, 'key' => "hi-IN", 'value' =>   "के बारे में 10 छोटे और सरल लेख की रूपरेखा लिखें:\n\n ##description## \n\nब्लॉग लेख का शीर्षक:\n ##title## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n"],

            ['id' => 2548, 'template_id' => 70, 'key' => "hu-HU", 'value' =>   "Írj 10 rövid és egyszerű cikkvázlatot erről:\n\n ##description## \n\nBlog cikk címe:\n ##title## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n"],

            ['id' => 2549, 'template_id' => 70, 'key' => "is-IS", 'value' =>   "Skrifaðu 10 stuttar og einfaldar greinar um:\n\n ##description## \n\nTitill blogggreinar:\n ##title## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 2550, 'template_id' => 70, 'key' => "id-ID", 'value' =>   "Tulis 10 garis besar artikel pendek dan sederhana tentang:\n\n ##description## \n\nTitolo dell'articolo del blog:\n ##title## \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n"],

            ['id' => 2551, 'template_id' => 70, 'key' => "it-IT", 'value' =>   "Scrivi 10 brevi e semplici schemi di articoli su:\n\n ##description## \n\nブログ記事タイトル:\n ##title## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 2552, 'template_id' => 70, 'key' => "ja-JP", 'value' =>   "～についての短くて簡単な記事の概要を 10 個書きます:\n\n ##description## \n\nBlog article title:\n ##title## \n\n 結果の声のトーンは、:\n ##tone_language## \n\n"],

            ['id' => 2553, 'template_id' => 70, 'key' => "ko-KR", 'value' =>   "10개의 짧고 간단한 기사 개요를 작성하십시오.:\n\n ##description## \n\n블로그 기사 제목:\n ##title## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 2554, 'template_id' => 70, 'key' => "ms-MY", 'value' =>   "Tulis 10 rangka artikel ringkas dan ringkas tentang:\n\n ##description## \n\nTajuk artikel blog:\n ##title## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n"],

            ['id' => 2555, 'template_id' => 70, 'key' => "nb-NO", 'value' =>   "Skriv 10 korte og enkle artikkelskisser om:\n\n ##description## \n\nBloggartikkeltittel:\n ##title## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 2556, 'template_id' => 70, 'key' => "pl-PL", 'value' =>   "Napisz 10 krótkich i prostych konspektów artykułów nt:\n\n ##description## \n\nTytuł artykułu na blogu:\n ##title## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 2557, 'template_id' => 70, 'key' => "pt-PT", 'value' =>   "Escreva 10 esboços de artigos curtos e simples sobre:\n\n ##description## \n\nTítulo do artigo do blog:\n ##title## \n\n Tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2558, 'template_id' => 70, 'key' => "ru-RU", 'value' =>   "Напишите 10 коротких и простых статей о:\n\n ##description## \n\nНазвание статьи в блоге:\n ##title## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 2559, 'template_id' => 70, 'key' => "es-ES", 'value' =>   "Escribe 10 reseñas breves y sencillas de artículos sobre:\n\n ##description## \n\nTítulo del artículo del blog:\n ##title## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 2560, 'template_id' => 70, 'key' => "sv-SE", 'value' =>   "Skriv 10 korta och enkla artikelskisser om:\n\n ##description## \n\nBloggartikelns titel:\n ##title## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 2561, 'template_id' => 70, 'key' => "tr-TR", 'value' =>   "Hakkında 10 kısa ve basit makale özeti yazın:\n\n ##description## \n\nBlog makalesi başlığı:\n ##title## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n"],

            ['id' => 2562, 'template_id' => 70, 'key' => "pt-BR", 'value' =>   "Escreva 10 esboços de artigos curtos e simples sobre:\n\n ##description## \n\nTítulo do artigo do blog:\n ##title## \n\n Tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2563, 'template_id' => 70, 'key' => "ro-RO", 'value' =>   "Scrieți 10 contururi scurte și simple despre articole:\n\n ##description## \n\nTitlul articolului de pe blog:\n ##title## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 2564, 'template_id' => 70, 'key' => "vi-VN", 'value' =>   "Viết 10 dàn ý bài viết ngắn và đơn giản về:\n\n ##description## \n\nTiêu đề bài viết trên blog:\n ##title## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 2565, 'template_id' => 70, 'key' => "sw-KE", 'value' =>   "Andika muhtasari wa makala 10 fupi na rahisi kuhusu:\n\n ##description## \n\nKichwa cha makala ya blogu:\n ##title## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 2566, 'template_id' => 70, 'key' => "sl-SI", 'value' =>   "Napišite 10 kratkih in preprostih orisov člankov o:\n\n ##description## \n\nNaslov članka v blogu:\n ##title## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2567, 'template_id' => 70, 'key' => "th-TH", 'value' =>   "เขียนโครงร่างบทความสั้นๆ ง่ายๆ 10 บทความเกี่ยวกับ:\n\n ##description## \n\nชื่อบทความบล็อก:\n ##title## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 2568, 'template_id' => 70, 'key' => "uk-UA", 'value' =>   "Напишіть 10 коротких і простих нарисів статей про:\n\n ##description## \n\nНазва статті блогу:\n ##title## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n"],

            ['id' => 2569, 'template_id' => 70, 'key' => "lt-LT", 'value' =>   "Parašykite 10 trumpų ir paprastų straipsnio apybraižų apie:\n\n ##description## \n\nTinklaraščio straipsnio pavadinimas:\n ##title## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n"],

            ['id' => 2570, 'template_id' => 70, 'key' => "bg-BG", 'value' =>   "Напишете 10 кратки и прости очертания на статии за:\n\n ##description## \n\nЗаглавие на статията в блога:\n ##title## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 2571, 'template_id' => 71, 'key' => "en-US", 'value' =>   "Write SEO meta title and description for a blog post about:\n\n ##description## \n\n Blog title:\n ##title## \n\n Seed Words:\n ##keywords## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 2572, 'template_id' => 71, 'key' => "ar-AE", 'value' =>   "اكتب عنوان تعريف SEO ووصفًا لمنشور مدونة حول:\n\n ##description## \n\n عنوان المدونة:\n ##title## \n\n كلمات البذور:\n ##keywords## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 2573, 'template_id' => 71, 'key' => "cmn-CN", 'value' =>   "為有關以下內容的博客文章編寫 SEO 元標題和描述：\n\n ##description## \n\n 博客標題:\n ##title## \n\n 種子詞:\n ##keywords## \n\n 結果的語氣必須是:\n ##tone_language## \n\n"],

            ['id' => 2574, 'template_id' => 71, 'key' => "hr-HR", 'value' =>   "Napišite SEO meta naslov i opis za blog post o:\n\n ##description## \n\n Naslov bloga:\n ##title## \n\n Riječi sjemena:\n ##keywords## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2575, 'template_id' => 71, 'key' => "cs-CZ", 'value' =>   "Napište SEO meta název a popis pro blogový příspěvek o:\n\n ##description## \n\n Název blogu:\n ##title## \n\n Seed Slova:\n ##keywords## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 2576, 'template_id' => 71, 'key' => "da-DK", 'value' =>   "Skriv SEO meta titel og beskrivelse til et blogindlæg om:\n\n ##description## \n\n Blog titel:\n ##title## \n\n Frøord:\n ##keywords## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 2577, 'template_id' => 71, 'key' => "nl-NL", 'value' =>   "Schrijf een SEO-metatitel en -beschrijving voor een blogpost over:\n\n ##description## \n\n Blog Titel:\n ##title## \n\n Zaad woorden:\n ##keywords## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 2578, 'template_id' => 71, 'key' => "et-EE", 'value' =>   "Kirjutage SEO metapealkiri ja kirjeldus blogipostituse jaoks, mis käsitleb:\n\n ##description## \n\n Blogi pealkiri:\n ##title## \n\n Seemne sõnad:\n ##keywords## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 2579, 'template_id' => 71, 'key' => "fi-FI", 'value' =>   "Kirjoita SEO-sisällönkuvausotsikko ja kuvaus blogitekstille aiheesta:\n\n ##description## \n\n Blogin otsikko:\n ##title## \n\n Siemensanat:\n ##keywords## \n\n Äänesävyn tuloksen on oltava:\n ##tone_language## \n\n"],

            ['id' => 2580, 'template_id' => 71, 'key' => "fr-FR", 'value' =>   "Rédigez un méta-titre et une description SEO pour un article de blog sur :\n\n ##description## \n\n Titre du Blog:\n ##title## \n\n Mots de départ:\n ##keywords## \n\n Le ton de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 2581, 'template_id' => 71, 'key' => "de-DE", 'value' =>   "Schreiben Sie einen SEO-Metatitel und eine Beschreibung für einen Blogbeitrag über:\n\n ##description## \n\n Blog Titel:\n ##title## \n\n Samenwörter:\n ##keywords## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 2582, 'template_id' => 71, 'key' => "el-GR", 'value' =>   "Γράψτε τον μετα-τίτλο SEO και την περιγραφή για μια ανάρτηση ιστολογίου σχετικά με:\n\n ##description## \n\n Τίτλος Ιστολογίου:\n ##title## \n\n Σπόροι Λέξεις:\n ##keywords## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 2583, 'template_id' => 71, 'key' => "he-IL", 'value' =>   "כתוב מטא כותרת ותיאור של SEO עבור פוסט בבלוג על:\n\n ##description## \n\n כותרת הבלוג:\n ##title## \n\n מילות זרעים:\n ##keywords## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 2584, 'template_id' => 71, 'key' => "hi-IN", 'value' =>   "निम्नलिखित के बारे में ब्लॉग पोस्ट के लिए SEO मेटा शीर्षक और विवरण लिखें:\n\n ##description## \n\n ब्लॉग का शीर्षक:\n ##title## \n\n बीज शब्द:\n ##keywords## \n\n परिणाम का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 2585, 'template_id' => 71, 'key' => "hu-HU", 'value' =>   "Írjon SEO metacímet és leírást egy blogbejegyzéshez:\n\n ##description## \n\n Blog cím:\n ##title## \n\n Magszavak:\n ##keywords## \n\n Az eredmény hangnemének kell lennie:\n ##tone_language## \n\n"],

            ['id' => 2586, 'template_id' => 71, 'key' => "is-IS", 'value' =>   "Skrifaðu SEO meta titil og lýsingu fyrir bloggfærslu um:\n\n ##description## \n\n Titill bloggsins:\n ##title## \n\n Fræorð:\n ##keywords## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 2587, 'template_id' => 71, 'key' => "id-ID", 'value' =>   "Tulis judul dan deskripsi meta SEO untuk posting blog tentang:\n\n ##description## \n\n Judul blog:\n ##title## \n\n Kata Benih:\n ##keywords## \n\n Nada suara hasilnya harus:\n ##tone_language## \n\n"],

            ['id' => 2588, 'template_id' => 71, 'key' => "it-IT", 'value' =>   "Scrivi il meta titolo e la descrizione SEO per un post sul blog su:\n\n ##description## \n\n Titolo del Blog:\n ##title## \n\n Parole seme:\n ##keywords## \n\n Tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 2589, 'template_id' => 71, 'key' => "ja-JP", 'value' =>   "以下に関するブログ投稿の SEO メタ タイトルと説明を作成します。\n\n ##description## \n\n ブログのタイトル:\n ##title## \n\n シードワード:\n ##keywords## \n\n 結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n"],

            ['id' => 2590, 'template_id' => 71, 'key' => "ko-KR", 'value' =>   "다음에 대한 블로그 게시물의 SEO 메타 제목 및 설명 작성:\n\n ##description## \n\n 블로그 제목:\n ##title## \n\n 시드 단어:\n ##keywords## \n\n 결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 2591, 'template_id' => 71, 'key' => "ms-MY", 'value' =>   "Tulis tajuk dan penerangan meta SEO untuk catatan blog tentang:\n\n ##description## \n\n Tajuk blog:\n ##title## \n\n Kata Benih:\n ##keywords## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 2592, 'template_id' => 71, 'key' => "nb-NO", 'value' =>   "Skriv SEO-metatittel og beskrivelse for et blogginnlegg om:\n\n ##description## \n\n   Bloggtittel:\n ##title## \n\n Frøord:\n ##keywords## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 2593, 'template_id' => 71, 'key' => "pl-PL", 'value' =>   "Napisz meta tytuł i opis SEO dla posta na blogu na temat:\n\n ##description## \n\n Tytuł bloga:\n ##title## \n\n Słowa nasion:\n ##keywords## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 2594, 'template_id' => 71, 'key' => "pt-PT", 'value' =>   "Escreva meta título e descrição de SEO para um post de blog sobre:\n\n ##description## \n\n Título do Blog:\n ##title## \n\n palavras-semente:\n ##keywords## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],
                
            ['id' => 2595, 'template_id' => 71, 'key' => "ru-RU", 'value' =>   "Напишите SEO мета-заголовок и описание для сообщения в блоге о:\n\n ##description## \n\n Название блога:\n ##title## \n\n Исходные слова:\n ##keywords## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 2596, 'template_id' => 71, 'key' => "es-ES", 'value' =>   "Escriba el metatítulo y la descripción de SEO para una publicación de blog sobre:\n\n ##description## \n\n Titulo de Blog:\n ##title## \n\n Palabras semilla:\n ##keywords## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 2597, 'template_id' => 71, 'key' => "sv-SE", 'value' =>   "Skriv SEO-metatitel och beskrivning för ett blogginlägg om:\n\n ##description## \n\n Bloggtitel:\n ##title## \n\n Frö ord:\n ##keywords## \n\n Tonen i rösten för resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 2598, 'template_id' => 71, 'key' => "tr-TR", 'value' =>   "Aşağıdakilerle ilgili bir blog yazısı için SEO meta başlığı ve açıklaması yazın:\n\n ##description## \n\n Blog başlığı:\n ##title## \n\n tohum kelimeler:\n ##keywords## \n\n Sonucun ses tonu şöyle olmalıdır::\n ##tone_language## \n\n"],

            ['id' => 2599, 'template_id' => 71, 'key' => "pt-BR", 'value' =>   "Escreva meta título e descrição de SEO para um post de blog sobre:\n\n ##description## \n\n Título do Blog:\n ##title## \n\n palavras-semente:\n ##keywords## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2600, 'template_id' => 71, 'key' => "ro-RO", 'value' =>   "Scrieți meta titlul și descrierea SEO pentru o postare de blog despre:\n\n ##description## \n\n Titlu de Blog:\n ##title## \n\n Cuvinte sămânță:\n ##keywords## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 2601, 'template_id' => 71, 'key' => "vi-VN", 'value' =>   "Viết tiêu đề và mô tả meta SEO cho một bài đăng trên blog về:\n\n ##description## \n\n Tiêu đề Blog:\n ##title## \n\n từ hạt giống:\n ##keywords## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 2602, 'template_id' => 71, 'key' => "sw-KE", 'value' =>   "Andika kichwa cha meta cha SEO na maelezo ya chapisho la blogi kuhusu:\n\n ##description## \n\n Jina la blogi:\n ##title## \n\n Maneno ya mbegu:\n ##keywords## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 2603, 'template_id' => 71, 'key' => "sl-SI", 'value' =>   "Napišite meta naslov in opis SEO za objavo v spletnem dnevniku o:\n\n ##description## \n\n Naslov bloga:\n ##title## \n\n Seed Words:\n ##keywords## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],
                
            ['id' => 2604, 'template_id' => 71, 'key' => "th-TH", 'value' =>   "เขียนชื่อเมตา SEO และคำอธิบายสำหรับโพสต์บล็อกเกี่ยวกับ:\n\n ##description## \n\n ชื่อบล็อก:\n ##title## \n\n คำเมล็ด:\n ##keywords## \n\n เสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 2605, 'template_id' => 71, 'key' => "uk-UA", 'value' =>   "Напишіть метазаголовок і опис SEO для публікації в блозі про:\n\n ##description## \n\n Назва блогу:\n ##title## \n\n Насіннєві слова:\n ##keywords## \n\n Тон голосу результату повинен бути:\n ##tone_language## \n\n"],

            ['id' => 2606, 'template_id' => 71, 'key' => "lt-LT", 'value' =>   "Parašykite SEO meta pavadinimą ir aprašą tinklaraščio įrašui apie:\n\n ##description## \n\n Tinklaraščio pavadinimas:\n ##title## \n\n Sėklų žodžiai:\n ##keywords## \n\n Turi būti rezultato balso tonas:\n ##tone_language## \n\n"],

            ['id' => 2607, 'template_id' => 71, 'key' => "bg-BG", 'value' =>   "Напишете мета заглавие и описание на SEO за публикация в блог за:\n\n ##description## \n\n Заглавие на блога:\n ##title## \n\n Семенни думи:\n ##keywords## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 2608, 'template_id' => 72, 'key' => "en-US", 'value' =>   "Write SEO meta title and description for a website about:\n ##description## \n\nWebsite Name:\n ##title## \n\nSeed Words:\n ##keywords## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 2609, 'template_id' => 72, 'key' => "ar-AE", 'value' =>   "اكتب عنوان تعريف SEO ووصفًا لموقع ويب حول:\n ##description## \n\nاسم الموقع:\n ##title## \n\nكلمات البذور:\n ##keywords## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 2610, 'template_id' => 72, 'key' => "cmn-CN", 'value' =>   "為網站編寫 SEO 元標題和描述：\n ##description## \n\n網站名稱:\n ##title## \n\n種子詞:\n ##keywords## \n\n 結果的語氣必須是:\n ##tone_language## \n\n"],

            ['id' => 2611, 'template_id' => 72, 'key' => "hr-HR", 'value' =>   "Napišite SEO meta naslov i opis za web stranicu o:\n ##description## \n\nNaziv web stranice:\n ##title## \n\nRiječi sjemena:\n ##keywords## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2612, 'template_id' => 72, 'key' => "cs-CZ", 'value' =>   "Napište SEO meta název a popis pro web o:\n ##description## \n\nNázev webu:\n ##title## \n\nSeed Slova:\n ##keywords## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 2613, 'template_id' => 72, 'key' => "da-DK", 'value' =>   "Skriv SEO meta titel og beskrivelse til en hjemmeside om:\n ##description## \n\nHjemmesidenavn:\n ##title## \n\nFrøord:\n ##keywords## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 2614, 'template_id' => 72, 'key' => "nl-NL", 'value' =>   "Schrijf een SEO-metatitel en -beschrijving voor een website over:\n ##description## \n\nwebsite naam:\n ##title## \n\nZaad woorden:\n ##keywords## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 2615, 'template_id' => 72, 'key' => "et-EE", 'value' =>   "Kirjutage SEO metapealkiri ja kirjeldus veebisaidile, mis käsitleb:\n ##description## \n\nVeebisaidi nimi:\n ##title## \n\nSeemne sõnad:\n ##keywords## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 2616, 'template_id' => 72, 'key' => "fi-FI", 'value' =>   "Kirjoita SEO-sisällönkuvausotsikko ja kuvaus verkkosivustolle aiheesta:\n ##description## \n\nVerkkosivuston nimi:\n ##title## \n\nSiemensanat:\n ##keywords## \n\n Äänesävyn tuloksen on oltava:\n ##tone_language## \n\n"],

            ['id' => 2617, 'template_id' => 72, 'key' => "fr-FR", 'value' =>   "Rédigez un méta-titre et une description SEO pour un site Web sur :\n ##description## \n\nNom du site Web:\n ##title## \n\nMots de départ:\n ##keywords## \n\n Le ton de la voix du résultat doit être:\n ##tone_language## \n\n"],
                
            ['id' => 2618, 'template_id' => 72, 'key' => "de-DE", 'value' =>   "Schreiben Sie einen SEO-Metatitel und eine Beschreibung für eine Website über:\n ##description## \n\nWebseiten-Name:\n ##title## \n\nSamenwörter:\n ##keywords## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 2619, 'template_id' => 72, 'key' => "el-GR", 'value' =>   "Γράψτε μετα-τίτλο SEO και περιγραφή για έναν ιστότοπο σχετικά με:\n ##description## \n\nΌνομα ιστότοπου:\n ##title## \n\nΣπόροι Λέξεις:\n ##keywords## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 2620, 'template_id' => 72, 'key' => "he-IL", 'value' =>   "כתוב מטא כותרת ותיאור של SEO עבור אתר אינטרנט על:\n ##description## \n\nשם האתר:\n ##title## \n\nמילות זרעים:\n ##keywords## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 2621, 'template_id' => 72, 'key' => "hi-IN", 'value' =>   "किसी वेबसाइट के लिए SEO मेटा शीर्षक और विवरण लिखें:\n ##description## \n\nवेबसाइट का नाम:\n ##title## \n\nबीज शब्द:\n ##keywords## \n\n परिणाम का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 2622, 'template_id' => 72, 'key' => "hu-HU", 'value' =>   "Írjon SEO metacímet és leírást egy webhelyhez:\n ##description## \n\nWebhely neve:\n ##title## \n\nMagszavak:\n ##keywords## \n\n Az eredmény hangnemének kell lennie:\n ##tone_language## \n\n"],

            ['id' => 2623, 'template_id' => 72, 'key' => "is-IS", 'value' =>   "Skrifaðu SEO meta titil og lýsingu fyrir vefsíðu um:\n ##description## \n\nHeiti vefsíðu:\n ##title## \n\nFræorð:\n ##keywords## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 2624, 'template_id' => 72, 'key' => "id-ID", 'value' =>   "Tulis judul dan deskripsi meta SEO untuk situs web tentang:\n ##description## \n\nNama Situs Web:\n ##title## \n\nKata Benih:\n ##keywords## \n\n Nada suara hasilnya harus:\n ##tone_language## \n\n"],

            ['id' => 2625, 'template_id' => 72, 'key' => "it-IT", 'value' =>   "Scrivi meta titolo e descrizione SEO per un sito web su:\n ##description## \n\nNome del sito web:\n ##title## \n\nParole seme:\n ##keywords## \n\n Tono di voce del risultato deve essere:\n ##tone_language## \n\n"],
                
            ['id' => 2626, 'template_id' => 72, 'key' => "ja-JP", 'value' =>   "Web サイトの SEO メタ タイトルと説明を以下について書きます。\n ##description## \n\nウェブサイト名:\n ##title## \n\nシードワード:\n ##keywords## \n\n 結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n"],

            ['id' => 2627, 'template_id' => 72, 'key' => "ko-KR", 'value' =>   "다음에 대한 웹사이트의 SEO 메타 제목 및 설명 작성:\n ##description## \n\n웹사이트 이름:\n ##title## \n\n시드 단어:\n ##keywords## \n\n 결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 2628, 'template_id' => 72, 'key' => "ms-MY", 'value' =>   "Tulis tajuk dan penerangan meta SEO untuk tapak web tentang:\n ##description## \n\nNama Laman Web:\n ##title## \n\nKata Benih:\n ##keywords## \n\n  Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 2629, 'template_id' => 72, 'key' => "nb-NO", 'value' =>   "Skriv SEO-metatittel og beskrivelse for et nettsted om:\n ##description## \n\nNettstedets navn:\n ##title## \n\nFrøord:\n ##keywords## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 2630, 'template_id' => 72, 'key' => "pl-PL", 'value' =>   "Napisz meta tytuł i opis SEO dla strony internetowej o:\n ##description## \n\nNazwa strony:\n ##title## \n\n Słowa nasion:\n ##keywords## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 2631, 'template_id' => 72, 'key' => "pt-PT", 'value' =>   "Escreva meta título e descrição de SEO para um site sobre:\n ##description## \n\nNome do site:\n ##title## \n\npalavras-semente:\n ##keywords## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2632, 'template_id' => 72, 'key' => "ru-RU", 'value' =>   "Напишите мета-заголовок и описание SEO для веб-сайта о:\n ##description## \n\nНазвание веб-сайта:\n ##title## \n\nИсходные слова:\n ##keywords## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 2633, 'template_id' => 72, 'key' => "es-ES", 'value' =>   "Escriba el metatítulo y la descripción de SEO para un sitio web sobre:\n ##description## \n\nNombre del Sitio Web:\n ##title## \n\nPalabras semilla:\n ##keywords## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 2634, 'template_id' => 72, 'key' => "sv-SE", 'value' =>   "Skriv SEO-metatitel och beskrivning för en webbplats om:\n ##description## \n\nWebbplatsens namn:\n ##title## \n\nFrö ord:\n ##keywords## \n\n Tonen i rösten för resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 2635, 'template_id' => 72, 'key' => "tr-TR", 'value' =>   "Aşağıdakilerle ilgili bir web sitesi için SEO meta başlığı ve açıklaması yazın:\n ##description## \n\nWeb Sitesi Adı:\n ##title## \n\ntohum kelimeler:\n ##keywords## \n\n Sonucun ses tonu şöyle olmalıdır::\n ##tone_language## \n\n"],

            ['id' => 2636, 'template_id' => 72, 'key' => "pt-BR", 'value' =>   "Escreva meta título e descrição de SEO para um site sobre:\n ##description## \n\nNome do site:\n ##title## \n\npalavras-semente:\n ##keywords## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2637, 'template_id' => 72, 'key' => "ro-RO", 'value' =>   "Scrieți meta titlul și descrierea SEO pentru un site web despre:\n ##description## \n\nNumele site-ului:\n ##title## \n\nCuvinte sămânță:\n ##keywords## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 2638, 'template_id' => 72, 'key' => "vi-VN", 'value' =>   "Viết tiêu đề và mô tả meta SEO cho một trang web về:\n ##description## \n\nTên trang web:\n ##title## \n\n từ hạt giống:\n ##keywords## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 2639, 'template_id' => 72, 'key' => "sw-KE", 'value' =>   "Andika kichwa cha meta cha SEO na maelezo ya tovuti kuhusu:\n ##description## \n\nJina la Tovuti:\n ##title## \n\nManeno ya mbegu:\n ##keywords## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 2640, 'template_id' => 72, 'key' => "sl-SI", 'value' =>   "Napišite meta naslov in opis SEO za spletno mesto o:\n ##description## \n\nIme spletnega mesta:\n ##title## \n\n Seed Words:\n ##keywords## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2641, 'template_id' => 72, 'key' => "th-TH", 'value' =>   "เขียนชื่อเมตา SEO และคำอธิบายสำหรับเว็บไซต์เกี่ยวกับ:\n ##description## \n\nชื่อเว็บไซต์:\n ##title## \n\nคำเมล็ด:\n ##keywords## \n\n โทนเสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 2642, 'template_id' => 72, 'key' => "uk-UA", 'value' =>   "Напишіть мета-заголовок і опис SEO для веб-сайту про:\n ##description## \n\n Назва сайту:\n ##title## \n\nНасіннєві слова:\n ##keywords## \n\n Тон голосу результату повинен бути:\n ##tone_language## \n\n"],

            ['id' => 2643, 'template_id' => 72, 'key' => "lt-LT", 'value' =>   "Parašykite svetainės SEO metapavadinimą ir aprašą apie:\n ##description## \n\nSvetainės pavadinimas:\n ##title## \n\nSėklų žodžiai:\n ##keywords## \n\n Turi būti rezultato balso tonas:\n ##tone_language## \n\n"],

            ['id' => 2644, 'template_id' => 72, 'key' => "bg-BG", 'value' =>   "Напишете SEO мета заглавие и описание за уебсайт за:\n ##description## \n\nИме на уебсайт:\n ##title## \n\nСеменни думи:\n ##keywords## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 2645, 'template_id' => 73, 'key' => "en-US", 'value' =>   "Write SEO meta title and description for a product page about:\n\n ##description## \n\n Product Title:\n ##title## \n\n Company Name:\n ##company_name## \n\n Seed Words:\n ##keywords## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 2646, 'template_id' => 73, 'key' => "ar-AE", 'value' =>   "اكتب عنوان تعريف SEO ووصفًا لصفحة منتج عنه:\n\n ##description## \n\n Product Title:\n ##title## \n\n اسم الشركة:\n ##company_name## \n\n كلمات البذور:\n ##keywords## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 2647, 'template_id' => 73, 'key' => "cmn-CN", 'value' =>   "為產品頁面編寫 SEO 元標題和描述:\n\n ##description## \n\n Product Title:\n ##title## \n\n 公司名稱:\n ##company_name## \n\n 種子詞:\n ##keywords## \n\n 結果的語氣必須是:\n ##tone_language## \n\n"],

            ['id' => 2648, 'template_id' => 73, 'key' => "hr-HR", 'value' =>   "Napišite SEO meta naslov i opis za stranicu proizvoda o:\n\n ##description## \n\n Product Title:\n ##title## \n\n Naziv tvrtke:\n ##company_name## \n\n Riječi sjemena:\n ##keywords## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2649, 'template_id' => 73, 'key' => "cs-CZ", 'value' =>   "Napište SEO meta název a popis pro stránku produktu o:\n\n ##description## \n\n Product Title:\n ##title## \n\n Jméno společnosti:\n ##company_name## \n\n Seed Slova:\n ##keywords## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 2650, 'template_id' => 73, 'key' => "da-DK", 'value' =>   "Skriv SEO meta titel og beskrivelse til en produktside om:\n\n ##description## \n\n Product Title:\n ##title## \n\n firmanavn:\n ##company_name## \n\n Frøord:\n ##keywords## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 2651, 'template_id' => 73, 'key' => "nl-NL", 'value' =>   "Schrijf een SEO-metatitel en -beschrijving voor een productpagina over:\n\n ##description## \n\n Product Title:\n ##title## \n\n Bedrijfsnaam:\n ##company_name## \n\n Zaad woorden:\n ##keywords## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 2652, 'template_id' => 73, 'key' => "et-EE", 'value' =>   "Kirjutage SEO metapealkiri ja kirjeldus tootelehele:\n\n ##description## \n\n Product Title:\n ##title## \n\n Ettevõtte nimi:\n ##company_name## \n\n Seemne sõnad:\n ##keywords## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],
                
            ['id' => 2653, 'template_id' => 73, 'key' => "fi-FI", 'value' =>   "Kirjoita SEO-sisällönkuvausotsikko ja kuvaus tuotesivulle:\n\n ##description## \n\n Product Title:\n ##title## \n\n Yrityksen nimi:\n ##company_name## \n\n Siemensanat:\n ##keywords## \n\n Äänesävyn tuloksen on oltava:\n ##tone_language## \n\n"],

            ['id' => 2654, 'template_id' => 73, 'key' => "fr-FR", 'value' =>   "Rédigez un méta-titre et une description SEO pour une page de produit sur:\n\n ##description## \n\n Product Title:\n ##title## \n\n Nom de l'entreprise:\n ##company_name## \n\n Mots de départ:\n ##keywords## \n\n Le ton de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 2655, 'template_id' => 73, 'key' => "de-DE", 'value' =>   "Schreiben Sie einen SEO-Metatitel und eine Beschreibung für eine Produktseite:\n\n ##description## \n\n Product Title:\n ##title## \n\n Name der Firma:\n ##company_name## \n\n Samenwörter:\n ##keywords## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 2656, 'template_id' => 73, 'key' => "el-GR", 'value' =>   "Γράψτε τον μετα-τίτλο SEO και την περιγραφή για μια σελίδα προϊόντος σχετικά με:\n\n ##description## \n\n Product Title:\n ##title## \n\n Όνομα εταιρείας:\n ##company_name## \n\n Σπόροι Λέξεις:\n ##keywords## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 2657, 'template_id' => 73, 'key' => "he-IL", 'value' =>   "כתוב SEO מטא כותרת ותיאור עבור דף מוצר על:\n\n ##description## \n\n Product Title:\n ##title## \n\n שם החברה:\n ##company_name## \n\n מילות זרעים:\n ##keywords## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 2658, 'template_id' => 73, 'key' => "hi-IN", 'value' =>   "किसी उत्पाद पृष्ठ के बारे में SEO मेटा शीर्षक और विवरण लिखें:\n\n ##description## \n\n Product Title:\n ##title## \n\n कंपनी का नाम:\n ##company_name## \n\n बीज शब्द:\n ##keywords## \n\n परिणाम का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 2659, 'template_id' => 73, 'key' => "hu-HU", 'value' =>   "Írjon SEO metacímet és leírást egy termékoldalhoz:\n\n ##description## \n\n Product Title:\n ##title## \n\n Cégnév:\n ##company_name## \n\n Magszavak:\n ##keywords## \n\n Az eredmény hangnemének kell lennie:\n ##tone_language## \n\n"],

            ['id' => 2660, 'template_id' => 73, 'key' => "is-IS", 'value' =>   "Skrifaðu SEO meta titil og lýsingu fyrir vörusíðu um:\n\n ##description## \n\n Product Title:\n ##title## \n\n nafn fyrirtækis:\n ##company_name## \n\n Fræorð:\n ##keywords## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 2661, 'template_id' => 73, 'key' => "id-ID", 'value' =>   "Tulis judul dan deskripsi meta SEO untuk halaman produk tentang:\n\n ##description## \n\n Product Title:\n ##title## \n\n Nama perusahaan:\n ##company_name## \n\n Kata Benih:\n ##keywords## \n\n Nada suara hasilnya harus:\n ##tone_language## \n\n"],

            ['id' => 2662, 'template_id' => 73, 'key' => "it-IT", 'value' =>   "Scrivi il meta titolo e la descrizione SEO per una pagina di prodotto:\n\n ##description## \n\n Product Title:\n ##title## \n\n Nome della ditta:\n ##company_name## \n\n Parole seme:\n ##keywords## \n\n Tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 2663, 'template_id' => 73, 'key' => "ja-JP", 'value' =>   "製品ページの SEO メタ タイトルと説明を書きます。:\n\n ##description## \n\n Product Title:\n ##title## \n\n 会社名:\n ##company_name## \n\n シードワード:\n ##keywords## \n\n 結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n"],

            ['id' => 2664, 'template_id' => 73, 'key' => "ko-KR", 'value' =>   "제품 페이지에 대한 SEO 메타 제목 및 설명 작성:\n\n ##description## \n\n Product Title:\n ##title## \n\n 회사 이름:\n ##company_name## \n\n 시드 단어:\n ##keywords## \n\n 결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 2665, 'template_id' => 73, 'key' => "ms-MY", 'value' =>   "Tulis tajuk dan penerangan meta SEO untuk halaman produk tentang:\n\n ##description## \n\n Product Title:\n ##title## \n\n nama syarikat:\n ##company_name## \n\n Kata Benih:\n ##keywords## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],
                
            ['id' => 2666, 'template_id' => 73, 'key' => "nb-NO", 'value' =>   "Skriv SEO meta tittel og beskrivelse for en produktside om:\n\n ##description## \n\n Product Title:\n ##title## \n\n selskapsnavn:\n ##company_name## \n\n Frøord:\n ##keywords## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 2667, 'template_id' => 73, 'key' => "pl-PL", 'value' =>   "Napisz tytuł i opis meta SEO dla strony produktu:\n\n ##description## \n\n Product Title:\n ##title## \n\n Nazwa firmy:\n ##company_name## \n\n Słowa nasion:\n ##keywords## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 2668, 'template_id' => 73, 'key' => "pt-PT", 'value' =>   "Escreva meta título e descrição de SEO para uma página de produto sobre:\n\n ##description## \n\n Product Title:\n ##title## \n\n nome da empresa:\n ##company_name## \n\n palavras-semente:\n ##keywords## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2669, 'template_id' => 73, 'key' => "ru-RU", 'value' =>   "Напишите SEO-мета-заголовок и описание для страницы продукта о:\n\n ##description## \n\n Product Title:\n ##title## \n\n Название компании:\n ##company_name## \n\n Исходные слова:\n ##keywords## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 2670, 'template_id' => 73, 'key' => "es-ES", 'value' =>   "Escriba el metatítulo y la descripción de SEO para una página de producto sobre:\n\n ##description## \n\n Product Title:\n ##title## \n\n nombre de empresa:\n ##company_name## \n\n Palabras semilla:\n ##keywords## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 2671, 'template_id' => 73, 'key' => "sv-SE", 'value' =>   "Skriv SEO-metatitel och beskrivning för en produktsida om:\n\n ##description## \n\n Product Title:\n ##title## \n\n Företagsnamn:\n ##company_name## \n\n Frö ord:\n ##keywords## \n\n Tonen i rösten för resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 2672, 'template_id' => 73, 'key' => "tr-TR", 'value' =>   "Hakkında bir ürün sayfası için SEO meta başlığı ve açıklaması yazın:\n\n ##description## \n\n Product Title:\n ##title## \n\n Firma Adı:\n ##company_name## \n\n tohum kelimeler:\n ##keywords## \n\n Sonucun ses tonu şöyle olmalıdır::\n ##tone_language## \n\n"],

            ['id' => 2673, 'template_id' => 73, 'key' => "pt-BR", 'value' =>   "Escreva meta título e descrição de SEO para uma página de produto sobre:\n\n ##description## \n\n Product Title:\n ##title## \n\n nome da empresa:\n ##company_name## \n\n palavras-semente:\n ##keywords## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 2674, 'template_id' => 73, 'key' => "ro-RO", 'value' =>   "Scrieți meta titlu SEO și descriere pentru o pagină de produs despre:\n\n ##description## \n\n Product Title:\n ##title## \n\n Numele companiei:\n ##company_name## \n\n Cuvinte sămânță:\n ##keywords## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 2675, 'template_id' => 73, 'key' => "vi-VN", 'value' =>   "Viết tiêu đề và mô tả meta SEO cho trang sản phẩm về:\n\n ##description## \n\n Product Title:\n ##title## \n\n Tên công ty:\n ##company_name## \n\n từ hạt giống:\n ##keywords## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 2676, 'template_id' => 73, 'key' => "sw-KE", 'value' =>   "Andika kichwa cha meta cha SEO na maelezo ya ukurasa wa bidhaa kuhusu:\n\n ##description## \n\n Product Title:\n ##title## \n\n jina la kampuni:\n ##company_name## \n\n Maneno ya mbegu:\n ##keywords## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 2677, 'template_id' => 73, 'key' => "sl-SI", 'value' =>   "Napišite metanaslov SEO in opis za stran izdelka o:\n\n ##description## \n\n Product Title:\n ##title## \n\n ime podjetja:\n ##company_name## \n\n Seed Words:\n ##keywords## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 2678, 'template_id' => 73, 'key' => "th-TH", 'value' =>   "เขียนชื่อเมตา SEO และคำอธิบายสำหรับหน้าผลิตภัณฑ์เกี่ยวกับ:\n\n ##description## \n\n Product Title:\n ##title## \n\n ชื่อ บริษัท:\n ##company_name## \n\n คำเมล็ด:\n ##keywords## \n\n น้ำเสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 2679, 'template_id' => 73, 'key' => "uk-UA", 'value' =>   "Напишіть мета-заголовок і опис SEO для сторінки продукту:\n\n ##description## \n\n Product Title:\n ##title## \n\n Назва компанії:\n ##company_name## \n\n Насіннєві слова:\n ##keywords## \n\n Тон голосу результату повинен бути:\n ##tone_language## \n\n"],
                
            ['id' => 2680, 'template_id' => 73, 'key' => "lt-LT", 'value' =>   "Parašykite produkto puslapio SEO meta pavadinimą ir aprašą:\n\n ##description## \n\n Product Title:\n ##title## \n\n Įmonės pavadinimas:\n ##company_name## \n\n Sėklų žodžiai:\n ##keywords## \n\n Turi būti rezultato balso tonas:\n ##tone_language## \n\n"],

            ['id' => 2681, 'template_id' => 73, 'key' => "bg-BG", 'value' =>   "Напишете SEO мета заглавие и описание за продуктова страница за:\n\n ##description## \n\n Product Title:\n ##title## \n\n Име на фирмата:\n ##company_name## \n\n Семенни думи:\n ##keywords## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 2682, 'template_id' => 74, 'key' => "en-US", 'value' =>   "Write 10 unique product titles to gain more sells on Amazon of the following product aimed at:\n\n ##audience## \n\n Product name:\n ##title## \n\n Product description:\n ##description## \n\n Tone of voice of the article must be:\n ##tone_language## \n\n" ],

            ['id' => 2683, 'template_id' => 74, 'key' => "ar-AE", 'value' =>   "اكتب 10 عناوين منتجات فريدة لكسب المزيد من عمليات البيع على أمازون للمنتج التالي الذي يستهدفه:\n\n ##audience## \n\n اسم المنتج:\n ##title## \n\n وصف المنتج:\n ##description## \n\n يجب أن تكون نبرة صوت المقال:\n ##tone_language## \n\n" ],

            ['id' => 2684, 'template_id' => 74, 'key' => "cmn-CN", 'value' =>   "寫 10 個獨特的產品標題，以在亞馬遜上獲得更多針對以下產品的銷售:\n\n ##audience## \n\n 產品名稱:\n ##title## \n\n 產品描述:\n ##description## \n\n 文章的語氣必須是:\n ##tone_language## \n\n" ],

            ['id' => 2685, 'template_id' => 74, 'key' => "hr-HR", 'value' =>   "Napišite 10 jedinstvenih naslova proizvoda kako biste ostvarili veću prodaju na Amazonu za sljedeći ciljni proizvod:\n\n ##audience## \n\n Ime proizvoda:\n ##title## \n\n Opis proizvoda:\n ##description## \n\n Ton glasa članka mora biti:\n ##tone_language## \n\n" ],

            ['id' => 2686, 'template_id' => 74, 'key' => "cs-CZ", 'value' =>   "Napište 10 jedinečných názvů produktů, abyste získali více prodejů na Amazonu následujícího produktu, na který je zaměřen:\n\n ##audience## \n\n Jméno výrobku:\n ##title## \n\n Popis výrobku:\n ##description## \n\n Tón hlasu článku musí být:\n ##tone_language## \n\n" ],

            ['id' => 2687, 'template_id' => 74, 'key' => "da-DK", 'value' =>   "Skriv 10 unikke produkttitler for at få flere salg på Amazon af følgende produkt rettet mod:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produkt beskrivelse:\n ##description## \n\n Tonen i artiklen skal være:\n ##tone_language## \n\n" ],

            ['id' => 2688, 'template_id' => 74, 'key' => "nl-NL", 'value' =>   "Schrijf 10 unieke producttitels om meer verkopen op Amazon te krijgen van het volgende beoogde product:\n\n ##audience## \n\n Productnaam:\n ##title## \n\n Product beschrijving:\n ##description## \n\n De toon van het artikel moet zijn:\n ##tone_language## \n\n" ],

            ['id' => 2689, 'template_id' => 74, 'key' => "et-EE", 'value' =>   "Kirjutage 10 ainulaadset tootenimetust, et saada Amazonis rohkem müüki järgmistest toodetest, millele on suunatud:\n\n ##audience## \n\n Tootenimi:\n ##title## \n\n Tootekirjeldus:\n ##description## \n\n Artikli hääletoon peab olema:\n ##tone_language## \n\n" ],

            ['id' => 2690, 'template_id' => 74, 'key' => "fi-FI", 'value' =>   "Kirjoita 10 ainutlaatuista tuotenimikettä saadaksesi enemmän myyntiä Amazonissa seuraavista tuotteista:\n\n ##audience## \n\n Tuotteen nimi:\n ##title## \n\n Tuotteen Kuvaus:\n ##description## \n\n Artikkelin äänensävyn on oltava:\n ##tone_language## \n\n" ],

            ['id' => 2691, 'template_id' => 74, 'key' => "fr-FR", 'value' =>   "Écrivez 10 titres de produits uniques pour obtenir plus de ventes sur Amazon du produit suivant destiné à:\n\n ##audience## \n\n Nom du produit:\n ##title## \n\n Description du produit:\n ##description## \n\n Le ton de la voix de l'article doit être:\n ##tone_language## \n\n" ],

            ['id' => 2692, 'template_id' => 74, 'key' => "de-DE", 'value' =>   "Schreiben Sie 10 einzigartige Produkttitel, um bei Amazon mehr Verkäufe des folgenden Produkts zu erzielen:\n\n ##audience## \n\n Produktname:\n ##title## \n\n Produktbeschreibung:\n ##description## \n\n Der Tonfall des Artikels muss sein:\n ##tone_language## \n\n" ],

            ['id' => 2693, 'template_id' => 74, 'key' => "el-GR", 'value' =>   "Γράψτε 10 μοναδικούς τίτλους προϊόντων για να κερδίσετε περισσότερες πωλήσεις στο Amazon για το παρακάτω προϊόν που στοχεύει:\n\n ##audience## \n\n Ονομασία προϊόντος:\n ##title## \n\n Περιγραφή προϊόντος:\n ##description## \n\n Ο τόνος της φωνής του άρθρου πρέπει να είναι:\n ##tone_language## \n\n" ],

            ['id' => 2694, 'template_id' => 74, 'key' => "he-IL", 'value' =>   "כתוב 10 כותרות מוצר ייחודיות כדי להשיג יותר מכירות באמזון של המוצר הבא שמיועד אליו:\n\n ##audience## \n\n שם מוצר:\n ##title## \n\n תיאור מוצר:\n ##description## \n\n טון הדיבור של המאמר חייב להיות:\n ##tone_language## \n\n" ],

            ['id' => 2695, 'template_id' => 74, 'key' => "hi-IN", 'value' =>   "निम्नलिखित उत्पाद के Amazon पर अधिक बिक्री हासिल करने के लिए 10 अद्वितीय उत्पाद शीर्षक लिखें:\n\n ##audience## \n\n प्रोडक्ट का नाम:\n ##title## \n\n उत्पाद वर्णन:\n ##description## \n\n लेख का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n" ],

            ['id' => 2696, 'template_id' => 74, 'key' => "hu-HU", 'value' =>   "Írjon 10 egyedi termékcímet, hogy több eladást szerezzen az Amazonon a következő, megcélzott termékből:\n\n ##audience## \n\n Termék név:\n ##title## \n\n Termékleírás:\n ##description## \n\n A cikk hangnemének kell lennie:\n ##tone_language## \n\n" ],

            ['id' => 2697, 'template_id' => 74, 'key' => "is-IS", 'value' =>   "Skrifaðu 10 einstaka vörutitla til að ná meiri sölu á Amazon af eftirfarandi vöru sem miðar að:\n\n ##audience## \n\n Vöru Nafn:\n ##title## \n\n Vörulýsing:\n ##description## \n\n Rödd í greininni verður að vera:\n ##tone_language## \n\n" ],

            ['id' => 2698, 'template_id' => 74, 'key' => "id-ID", 'value' =>   "Tulis 10 judul produk unik untuk mendapatkan lebih banyak penjualan di Amazon dari produk berikut yang dituju:\n\n ##audience## \n\n Nama Produk:\n ##title## \n\n Deskripsi Produk:\n ##description## \n\n Nada suara artikel harus:\n ##tone_language## \n\n" ],

            ['id' => 2699, 'template_id' => 74, 'key' => "it-IT", 'value' =>   "Scrivi 10 titoli di prodotti unici per ottenere più vendite su Amazon del seguente prodotto mirato:\n\n ##audience## \n\n Nome del prodotto:\n ##title## \n\n Descrizione del prodotto:\n ##description## \n\n Il tono di voce dell'articolo deve essere:\n ##tone_language## \n\n" ],

            ['id' => 2700, 'template_id' => 74, 'key' => "ja-JP", 'value' =>   "ユニークな製品タイトルを 10 個書いて、次の製品を Amazon でより多く販売してください。:\n\n ##audience## \n\n 商品名:\n ##title## \n\n 製品説明:\n ##description## \n\n 記事の口調は次のようにする必要があります。:\n ##tone_language## \n\n" ],

            ['id' => 2701, 'template_id' => 74, 'key' => "ko-KR", 'value' =>   "아마존에서 목표로 하는 다음 제품의 더 많은 판매를 얻기 위해 10개의 고유한 제품 제목을 작성하십시오.:\n\n ##audience## \n\n 상품명:\n ##title## \n\n 제품 설명:\n ##description## \n\n 기사의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n" ],
                
            ['id' => 2702, 'template_id' => 74, 'key' => "ms-MY", 'value' =>   "Tulis 10 tajuk produk unik untuk mendapatkan lebih banyak jualan di Amazon untuk produk berikut yang disasarkan:\n\n ##audience## \n\n Nama Produk:\n ##title## \n\n Penerangan Produk:\n ##description## \n\n Nada suara artikel mestilah:\n ##tone_language## \n\n" ],

            ['id' => 2703, 'template_id' => 74, 'key' => "nb-NO", 'value' =>   "Skriv 10 unike produkttitler for å få flere salg på Amazon av følgende produkt rettet mot:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen i artikkelen må være:\n ##tone_language## \n\n" ],

            ['id' => 2704, 'template_id' => 74, 'key' => "pl-PL", 'value' =>   "Napisz 10 unikalnych tytułów produktów, aby uzyskać więcej sprzedaży na Amazon następującego produktu, do którego jest skierowany:\n\n ##audience## \n\n Nazwa produktu:\n ##title## \n\n Opis produktu:\n ##description## \n\n Ton artykułu musi być:\n ##tone_language## \n\n" ],

            ['id' => 2705, 'template_id' => 74, 'key' => "pt-PT", 'value' =>   "Escreva 10 títulos de produtos exclusivos para obter mais vendas na Amazon do seguinte produto destinado a:\n\n ##audience## \n\n Nome do Produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do artigo deve ser:\n ##tone_language## \n\n" ],

            ['id' => 2706, 'template_id' => 74, 'key' => "ru-RU", 'value' =>   "Напишите 10 уникальных названий продуктов, чтобы увеличить продажи на Amazon следующего продукта, предназначенного для:\n\n ##audience## \n\n Наименование товара:\n ##title## \n\n Описание продукта:\n ##description## \n\n Тон статьи должен быть:\n ##tone_language## \n\n" ],

            ['id' => 2707, 'template_id' => 74, 'key' => "es-ES", 'value' =>   "Escriba 10 títulos de productos únicos para obtener más ventas en Amazon del siguiente producto dirigido a:\n\n ##audience## \n\n Nombre del producto:\n ##title## \n\n Descripción del Producto:\n ##description## \n\n El tono de voz del artículo debe ser:\n ##tone_language## \n\n" ],

            ['id' => 2708, 'template_id' => 74, 'key' => "sv-SE", 'value' =>   "Skriv 10 unika produkttitlar för att få fler försäljningar på Amazon av följande produkt som syftar till:\n\n ##audience## \n\n Produktnamn:\n ##title## \n\n Produktbeskrivning:\n ##description## \n\n Tonen i artikeln måste vara:\n ##tone_language## \n\n" ],

            ['id' => 2709, 'template_id' => 74, 'key' => "tr-TR", 'value' =>   "Hedeflenen aşağıdaki üründen Amazon'da daha fazla satış elde etmek için 10 benzersiz ürün başlığı yazın:\n\n ##audience## \n\n Ürün adı:\n ##title## \n\n Ürün Açıklaması:\n ##description## \n\n Makalenin ses tonu şu şekilde olmalıdır::\n ##tone_language## \n\n" ],

            ['id' => 2710, 'template_id' => 74, 'key' => "pt-BR", 'value' =>   "Escreva 10 títulos de produtos exclusivos para obter mais vendas na Amazon do seguinte produto destinado a:\n\n ##audience## \n\n Nome do Produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do artigo deve ser:\n ##tone_language## \n\n" ],
                
            ['id' => 2711, 'template_id' => 74, 'key' => "ro-RO", 'value' =>   "Scrieți 10 titluri de produse unice pentru a câștiga mai multe vânzări pe Amazon pentru următorul produs vizat:\n\n ##audience## \n\n Numele produsului:\n ##title## \n\n Descriere produs:\n ##description## \n\n Tonul vocii articolului trebuie să fie:\n ##tone_language## \n\n" ],

            ['id' => 2712, 'template_id' => 74, 'key' => "vi-VN", 'value' =>   "Viết 10 tiêu đề sản phẩm độc đáo để bán được nhiều hơn trên Amazon cho sản phẩm sau nhằm mục đích:\n\n ##audience## \n\n Tên sản phẩm:\n ##title## \n\n Mô tả Sản phẩm:\n ##description## \n\n Giọng điệu của bài viết phải:\n ##tone_language## \n\n" ],

            ['id' => 2713, 'template_id' => 74, 'key' => "sw-KE", 'value' =>   "Andika majina 10 ya kipekee ya bidhaa ili kupata mauzo zaidi kwenye Amazon ya bidhaa zifuatazo zinazolenga:\n\n ##audience## \n\n Jina la bidhaa:\n ##title## \n\n Maelezo ya bidhaa:\n ##description## \n\n Toni ya sauti ya makala lazima iwe:\n ##tone_language## \n\n" ],

            ['id' => 2714, 'template_id' => 74, 'key' => "sl-SI", 'value' =>   "Napišite 10 edinstvenih naslovov izdelkov, da pridobite večjo prodajo na Amazonu za naslednji izdelek, ki je namenjen:\n\n ##audience## \n\n Ime izdelka:\n ##title## \n\n Opis izdelka:\n ##description## \n\n Ton glasu članka mora biti:\n ##tone_language## \n\n" ],

            ['id' => 2715, 'template_id' => 74, 'key' => "th-TH", 'value' =>   "เขียนชื่อผลิตภัณฑ์ที่ไม่ซ้ำกัน 10 รายการเพื่อเพิ่มยอดขายใน Amazon ของผลิตภัณฑ์ต่อไปนี้:\n\n ##audience## \n\n ชื่อผลิตภัณฑ์:\n ##title## \n\n รายละเอียดสินค้า:\n ##description## \n\n น้ำเสียงของบทความต้องเป็น:\n ##tone_language## \n\n" ],

            ['id' => 2716, 'template_id' => 74, 'key' => "uk-UA", 'value' =>   "Напишіть 10 унікальних назв продукту, щоб отримати більше продажів на Amazon наступного продукту, на який націлено:\n\n ##audience## \n\n Назва продукту:\n ##title## \n\n Опис продукту:\n ##description## \n\n Тон голосу статті повинен бути:\n ##tone_language## \n\n" ],

            ['id' => 2717, 'template_id' => 74, 'key' => "lt-LT", 'value' =>   "Parašykite 10 unikalių produktų pavadinimų, kad „Amazon“ daugiau parduotų toliau nurodytų produktų:\n\n ##audience## \n\n Produkto pavadinimas:\n ##title## \n\n Prekės aprašymas:\n ##description## \n\n Straipsnio balso tonas turi būti:\n ##tone_language## \n\n" ],

            ['id' => 2718, 'template_id' => 74, 'key' => "bg-BG", 'value' =>   "Напишете 10 уникални продуктови заглавия, за да спечелите повече продажби в Amazon на следния целеви продукт:\n\n ##audience## \n\n Име на продукта:\n ##title## \n\n Описание на продукта:\n ##description## \n\n Тонът на гласа на статията трябва да бъде:\n ##tone_language## \n\n" ],

            ['id' => 2719, 'template_id' => 75, 'key' => "en-US", 'value' =>    "Write 10 advantages and features to gain more sells on Amazon of the following product aimed at:\n\n ##audience## \n\n Product name:\n ##title## \n\n Product description:\n ##description## \n\n Tone of voice of the article must be:\n ##tone_language## \n\n" ],

            ['id' => 2720, 'template_id' => 75, 'key' => "ar-AE", 'value' =>    "اكتب 10 مزايا وميزات لكسب المزيد من عمليات البيع على Amazon للمنتج التالي المستهدف:\n\n ##audience## \n\n اسم المنتج:\n ##title## \n\n وصف المنتج:\n ##description## \n\n يجب أن تكون نبرة صوت المقال:\n ##tone_language## \n\n" ],

            ['id' => 2721, 'template_id' => 75, 'key' => "cmn-CN", 'value' =>    "寫下 10 個優勢和特點，以在亞馬遜上獲得更多針對以下產品的銷售:\n\n ##audience## \n\n 產品名稱:\n ##title## \n\n 產品描述:\n ##description## \n\n 文章的語氣應該是:\n ##tone_language## \n\n" ],

            ['id' => 2722, 'template_id' => 75, 'key' => "hr-HR", 'value' =>    "Napišite 10 prednosti i značajki kako biste ostvarili veću prodaju na Amazonu sljedećeg ciljanog proizvoda:\n\n ##audience## \n\n Ime proizvoda:\n ##title## \n\n Opis proizvoda:\n ##description## \n\n Ton glasa članka mora biti:\n ##tone_language## \n\n" ],

            ['id' => 2723, 'template_id' => 75, 'key' => "cs-CZ", 'value' =>    "Napište 10 výhod a funkcí, abyste získali více prodejů na Amazonu následujícího produktu:\n\n ##audience## \n\n Jméno výrobku:\n ##title## \n\n Popis výrobku:\n ##description## \n\n Tón hlasu článku musí být:\n ##tone_language## \n\n" ],

            ['id' => 2724, 'template_id' => 75, 'key' => "da-DK", 'value' =>    "Skriv 10 fordele og funktioner for at få flere salg på Amazon af følgende produkt rettet mod:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produkt beskrivelse:\n ##description## \n\n Tonen i artiklen skal være:\n ##tone_language## \n\n" ],

            ['id' => 2725, 'template_id' => 75, 'key' => "nl-NL", 'value' =>    "Schrijf 10 voordelen en functies op om meer verkopen op Amazon te krijgen van het volgende beoogde product:\n\n ##audience## \n\n Productnaam:\n ##title## \n\n Product beschrijving:\n ##description## \n\n De toon van het artikel moet zijn:\n ##tone_language## \n\n" ],

            ['id' => 2726, 'template_id' => 75, 'key' => "et-EE", 'value' =>    "Kirjutage 10 eelist ja funktsiooni, et saada Amazonis rohkem müüki järgmistele mõeldud toodetele:\n\n ##audience## \n\n Tootenimi:\n ##title## \n\n Tootekirjeldus:\n ##description## \n\n Artikli hääletoon peab olema:\n ##tone_language## \n\n" ],

            ['id' => 2727, 'template_id' => 75, 'key' => "fi-FI", 'value' =>    "Kirjoita 10 etua ja ominaisuutta saadaksesi enemmän myyntiä Amazonissa seuraavista tuotteista:\n\n ##audience## \n\n Tuotteen nimi:\n ##title## \n\n Tuotteen Kuvaus:\n ##description## \n\n Artikkelin äänensävyn on oltava:\n ##tone_language## \n\n" ],

            ['id' => 2728, 'template_id' => 75, 'key' => "fr-FR", 'value' =>    "Rédigez 10 avantages et fonctionnalités pour obtenir plus de ventes sur Amazon du produit suivant destiné à:\n\n ##audience## \n\n Nom du produit:\n ##title## \n\n Description du produit:\n ##description## \n\n Le ton de la voix de l'article doit être:\n ##tone_language## \n\n" ],

            ['id' => 2729, 'template_id' => 75, 'key' => "de-DE", 'value' =>    "Schreiben Sie 10 Vorteile und Funktionen auf, um bei Amazon mehr Verkäufe des folgenden Produkts zu erzielen:\n\n ##audience## \n\n Produktname:\n ##title## \n\n Produktbeschreibung:\n ##description## \n\n Der Tonfall des Artikels muss sein:\n ##tone_language## \n\n" ],

            ['id' => 2730, 'template_id' => 75, 'key' => "el-GR", 'value' =>    "Γράψτε 10 πλεονεκτήματα και χαρακτηριστικά για να κερδίσετε περισσότερες πωλήσεις στο Amazon του παρακάτω προϊόντος στο οποίο στοχεύουν:\n\n ##audience## \n\n Ονομασία προϊόντος:\n ##title## \n\n Περιγραφή προϊόντος:\n ##description## \n\n Ο τόνος της φωνής του άρθρου πρέπει να είναι:\n ##tone_language## \n\n" ],

            ['id' => 2731, 'template_id' => 75, 'key' => "he-IL", 'value' =>    "כתוב 10 יתרונות ותכונות כדי להשיג יותר מכירות באמזון של המוצר הבא שמיועד אליו:\n\n ##audience## \n\n שם מוצר:\n ##title## \n\n תיאור מוצר:\n ##description## \n\n טון הדיבור של המאמר חייב להיות:\n ##tone_language## \n\n" ],

            ['id' => 2732, 'template_id' => 75, 'key' => "hi-IN", 'value' =>    "निम्नलिखित उत्पाद के Amazon पर अधिक बिक्री हासिल करने के लिए 10 फायदे और विशेषताएं लिखें:\n\n ##audience## \n\n प्रोडक्ट का नाम:\n ##title## \n\n उत्पाद वर्णन:\n ##description## \n\n लेख का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n" ],

            ['id' => 2733, 'template_id' => 75, 'key' => "hu-HU", 'value' =>    "Írjon 10 előnyt és funkciót, amelyekkel több eladást érhet el az Amazonon a következő, megcélzott termékből:\n\n ##audience## \n\n Termék név:\n ##title## \n\n Termékleírás:\n ##description## \n\n A cikk hangnemének kell lennie:\n ##tone_language## \n\n" ],

            ['id' => 2734, 'template_id' => 75, 'key' => "is-IS", 'value' =>    "Skrifaðu 10 kosti og eiginleika til að ná meiri sölu á Amazon á eftirfarandi vöru sem miðar að:\n\n ##audience## \n\n Vöru Nafn:\n ##title## \n\n Vörulýsing:\n ##description## \n\n Rödd í greininni verður að vera:\n ##tone_language## \n\n" ],

            ['id' => 2735, 'template_id' => 75, 'key' => "id-ID", 'value' =>    "Tulis 10 keunggulan dan fitur untuk mendapatkan lebih banyak penjualan di Amazon dari produk berikut yang ditujukan:\n\n ##audience## \n\n Nama Produk:\n ##title## \n\n Deskripsi Produk:\n ##description## \n\n Nada suara artikel harus:\n ##tone_language## \n\n" ],

            ['id' => 2736, 'template_id' => 75, 'key' => "it-IT", 'value' =>    "Scrivi 10 vantaggi e caratteristiche per ottenere più vendite su Amazon del seguente prodotto mirato:\n\n ##audience## \n\n Nome del prodotto:\n ##title## \n\n Descrizione del prodotto:\n ##description## \n\n Il tono di voce dell'articolo deve essere:\n ##tone_language## \n\n" ],

            ['id' => 2737, 'template_id' => 75, 'key' => "ja-JP", 'value' =>    "次の商品を Amazon でより多く売るための 10 の利点と特徴を書いてください。:\n\n ##audience## \n\n 商品名:\n ##title## \n\n 製品説明:\n ##description## \n\n 記事の口調は次のようにする必要があります。:\n ##tone_language## \n\n" ],

            ['id' => 2738, 'template_id' => 75, 'key' => "ko-KR", 'value' =>    "아마존에서 다음 제품을 더 많이 판매할 수 있는 10가지 장점과 기능을 작성하십시오.:\n\n ##audience## \n\n 상품명:\n ##title## \n\n 제품 설명:\n ##description## \n\n 기사의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n" ],

            ['id' => 2739, 'template_id' => 75, 'key' => "ms-MY", 'value' =>    "Tulis 10 kelebihan dan ciri untuk mendapatkan lebih banyak jualan di Amazon bagi produk berikut yang disasarkan:\n\n ##audience## \n\n Nama Produk:\n ##title## \n\n Penerangan Produk:\n ##description## \n\n Nada suara artikel mestilah:\n ##tone_language## \n\n" ],

            ['id' => 2740, 'template_id' => 75, 'key' => "nb-NO", 'value' =>    "Skriv 10 fordeler og funksjoner for å få flere salg på Amazon av følgende produkt rettet mot:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen i artikkelen må være:\n ##tone_language## \n\n" ],

            ['id' => 2741, 'template_id' => 75, 'key' => "pl-PL", 'value' =>    "Napisz 10 zalet i funkcji, aby uzyskać więcej sprzedaży na Amazon następującego produktu, do którego jest skierowany:\n\n ##audience## \n\n Nazwa produktu:\n ##title## \n\n Opis produktu:\n ##description## \n\n Ton artykułu musi być:\n ##tone_language## \n\n" ],

            ['id' => 2742, 'template_id' => 75, 'key' => "pt-PT", 'value' =>    "Escreva 10 vantagens e recursos para obter mais vendas na Amazon do seguinte produto destinado a:\n\n ##audience## \n\n Nome do Produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do artigo deve ser:\n ##tone_language## \n\n" ],

            ['id' => 2743, 'template_id' => 75, 'key' => "ru-RU", 'value' =>    "Напишите 10 преимуществ и функций, чтобы увеличить продажи на Amazon следующего продукта, предназначенного для:\n\n ##audience## \n\n Наименование товара:\n ##title## \n\n Описание продукта:\n ##description## \n\n Тон статьи должен быть:\n ##tone_language## \n\n" ],

            ['id' => 2744, 'template_id' => 75, 'key' => "es-ES", 'value' =>    "Escriba 10 ventajas y características para ganar más ventas en Amazon del siguiente producto dirigido a:\n\n ##audience## \n\n Nombre del producto:\n ##title## \n\n Descripción del Producto:\n ##description## \n\n El tono de voz del artículo debe ser:\n ##tone_language## \n\n" ],

            ['id' => 2745, 'template_id' => 75, 'key' => "sv-SE", 'value' =>    "Skriv 10 fördelar och funktioner för att få fler försäljningar på Amazon av följande produkt som syftar till:\n\n ##audience## \n\n Produktnamn:\n ##title## \n\n Produktbeskrivning:\n ##description## \n\n Tonen i artikeln måste vara:\n ##tone_language## \n\n" ],

            ['id' => 2746, 'template_id' => 75, 'key' => "tr-TR", 'value' =>    "Hedeflenen aşağıdaki üründen Amazon'da daha fazla satış elde etmek için 10 avantaj ve özellik yazın:\n\n ##audience## \n\n Ürün adı:\n ##title## \n\n Ürün Açıklaması:\n ##description## \n\n Makalenin ses tonu şu şekilde olmalıdır::\n ##tone_language## \n\n" ],

            ['id' => 2747, 'template_id' => 75, 'key' => "pt-BR", 'value' =>    "Escreva 10 vantagens e recursos para obter mais vendas na Amazon do seguinte produto destinado a:\n\n ##audience## \n\n Nome do Produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do artigo deve ser:\n ##tone_language## \n\n" ],

            ['id' => 2748, 'template_id' => 75, 'key' => "ro-RO", 'value' =>    "Scrieți 10 avantaje și caracteristici pentru a obține mai multe vânzări pe Amazon pentru următorul produs vizat:\n\n ##audience## \n\n Numele produsului:\n ##title## \n\n Descriere produs:\n ##description## \n\n Tonul vocii articolului trebuie să fie:\n ##tone_language## \n\n" ],

            ['id' => 2749, 'template_id' => 75, 'key' => "vi-VN", 'value' =>    "Viết 10 ưu điểm và tính năng để bán được nhiều hơn trên Amazon của sản phẩm sau nhằm mục đích:\n\n ##audience## \n\n Tên sản phẩm:\n ##title## \n\n Mô tả Sản phẩm:\n ##description## \n\n Giọng điệu của bài viết phải:\n ##tone_language## \n\n" ],

            ['id' => 2750, 'template_id' => 75, 'key' => "sw-KE", 'value' =>    "Andika faida na vipengele 10 ili kupata mauzo zaidi kwenye Amazon ya bidhaa zifuatazo zinazolenga:\n\n ##audience## \n\n Jina la bidhaa:\n ##title## \n\n Maelezo ya bidhaa:\n ##description## \n\n Toni ya sauti ya makala lazima iwe:\n ##tone_language## \n\n" ],

            ['id' => 2751, 'template_id' => 75, 'key' => "sl-SI", 'value' =>    "Napišite 10 prednosti in lastnosti, s katerimi boste dosegli večjo prodajo na Amazonu za naslednji izdelek, ki je namenjen:\n\n ##audience## \n\n Ime izdelka:\n ##title## \n\n Opis izdelka:\n ##description## \n\n Ton glasu članka mora biti:\n ##tone_language## \n\n" ],

            ['id' => 2752, 'template_id' => 75, 'key' => "th-TH", 'value' =>    "เขียนข้อดีและคุณสมบัติ 10 ข้อเพื่อเพิ่มยอดขายใน Amazon ของผลิตภัณฑ์ต่อไปนี้:\n\n ##audience## \n\n ชื่อผลิตภัณฑ์:\n ##title## \n\n รายละเอียดสินค้า:\n ##description## \n\n น้ำเสียงของบทความต้องเป็น:\n ##tone_language## \n\n" ],

            ['id' => 2753, 'template_id' => 75, 'key' => "uk-UA", 'value' =>    "Напишіть 10 переваг і особливостей, щоб отримати більше продажів на Amazon наступного продукту, націленого на:\n\n ##audience## \n\n Назва продукту:\n ##title## \n\n Опис продукту:\n ##description## \n\n Тон голосу статті повинен бути:\n ##tone_language## \n\n" ],

            ['id' => 2754, 'template_id' => 75, 'key' => "lt-LT", 'value' =>    "Parašykite 10 pranašumų ir savybių, kad „Amazon“ būtų daugiau parduota toliau nurodytas produktas:\n\n ##audience## \n\n Produkto pavadinimas:\n ##title## \n\n Prekės aprašymas:\n ##description## \n\n Straipsnio balso tonas turi būti:\n ##tone_language## \n\n" ],

            ['id' => 2755, 'template_id' => 75, 'key' => "bg-BG", 'value' =>    "Напишете 10 предимства и функции, за да спечелите повече продажби в Amazon на следния продукт, към който сте насочени:\n\n ##audience## \n\n Име на продукта:\n ##title## \n\n Описание на продукта:\n ##description## \n\n Тонът на гласа на статията трябва да бъде:\n ##tone_language## \n\n" ],

            ['id' => 2756, 'template_id' => 76, 'key' => "en-US", 'value' =>     "Write a creative advertisement idea at:\n\n ##audience## \n\n Product name:\n ##title## \n\n Product description:\n ##description## \n\n Tone of voice of the article must be:\n ##tone_language## \n\n" ] ,

            ['id' => 2757, 'template_id' => 76, 'key' => "ar-AE", 'value' =>   "اكتب فكرة إعلان إبداعي في:\n\n ##audience## \n\n اسم المنتج:\n ##title## \n\n وصف المنتج:\n ##description## \n\n يجب أن تكون نبرة صوت المقال:\n ##tone_language## \n\n" ] ,

            ['id' => 2758, 'template_id' => 76, 'key' => "cmn-CN", 'value' =>   "撰寫創意廣告創意:\n\n ##audience## \n\n 產品名稱:\n ##title## \n\n 產品說明:\n ##description## \n\n 這篇文章的聲音一定是:\n ##tone_language## \n\n" ] ,

            ['id' => 2759, 'template_id' => 76, 'key' => "hr-HR", 'value' =>   "Napišite kreativnu ideju za oglas na:\n\n ##audience## \n\n Ime proizvoda:\n ##title## \n\n Opis proizvoda:\n ##description## \n\n Ton glasa članka mora biti:\n ##tone_language## \n\n" ] ,

            ['id' => 2760, 'template_id' => 76, 'key' => "cs-CZ", 'value' =>   "Napište nápad na kreativní reklamu na:\n\n ##audience## \n\n Jméno výrobku:\n ##title## \n\n Popis výrobku:\n ##description## \n\n Tón hlasu článku musí být:\n ##tone_language## \n\n" ] ,

            ['id' => 2761, 'template_id' => 76, 'key' => "da-DK", 'value' =>   "Skriv en kreativ annonceidé på:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produkt beskrivelse:\n ##description## \n\n Tonen i artiklen skal være:\n ##tone_language## \n\n" ] ,

            ['id' => 2762, 'template_id' => 76, 'key' => "nl-NL", 'value' =>   "Schrijf een creatief advertentie-idee op:\n\n ##audience## \n\n Productnaam:\n ##title## \n\n Product beschrijving:\n ##description## \n\n De toon van het artikel moet zijn:\n ##tone_language## \n\n" ] ,

            ['id' => 2763, 'template_id' => 76, 'key' => "et-EE", 'value' =>   "Kirjutage loominguline reklaamiidee aadressil:\n\n ##audience## \n\n Tootenimi:\n ##title## \n\n Tootekirjeldus:\n ##description## \n\n Artikli hääletoon peab olema:\n ##tone_language## \n\n" ] ,

            ['id' => 2764, 'template_id' => 76, 'key' => "fi-FI", 'value' =>   "Kirjoita luova mainosidea osoitteessa:\n\n ##audience## \n\n Tuotteen nimi:\n ##title## \n\n Tuotteen Kuvaus:\n ##description## \n\n Artikkelin äänensävyn on oltava:\n ##tone_language## \n\n" ] ,

            ['id' => 2765, 'template_id' => 76, 'key' => "fr-FR", 'value' =>   "Rédigez une idée de publicité créative sur:\n\n ##audience## \n\n Nom du produit:\n ##title## \n\n Description du produit:\n ##description## \n\n Le ton de la voix de l'article doit être:\n ##tone_language## \n\n" ] ,

            ['id' => 2766, 'template_id' => 76, 'key' => "de-DE", 'value' =>   "Schreiben Sie eine kreative Werbeidee unter:\n\n ##audience## \n\n Produktname:\n ##title## \n\n Produktbeschreibung:\n ##description## \n\n Der Tonfall des Artikels muss sein:\n ##tone_language## \n\n" ] ,

            ['id' => 2767, 'template_id' => 76, 'key' => "el-GR", 'value' =>   "Γράψτε μια δημιουργική ιδέα διαφήμισης στο:\n\n ##audience## \n\n Ονομασία προϊόντος:\n ##title## \n\n Περιγραφή προϊόντος:\n ##description## \n\n Ο τόνος της φωνής του άρθρου πρέπει να είναι:\n ##tone_language## \n\n" ] ,

            ['id' => 2768, 'template_id' => 76, 'key' => "he-IL", 'value' =>   "כתוב רעיון פרסומת יצירתי ב:\n\n ##audience## \n\n שם מוצר:\n ##title## \n\n תיאור מוצר:\n ##description## \n\n טון הדיבור של המאמר חייב להיות:\n ##tone_language## \n\n" ] ,

            ['id' => 2769, 'template_id' => 76, 'key' => "hi-IN", 'value' =>   "पर एक रचनात्मक विज्ञापन विचार लिखें:\n\n ##audience## \n\n प्रोडक्ट का नाम:\n ##title## \n\n Product description:\n ##description## \n\n लेख का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n" ] ,

            ['id' => 2770, 'template_id' => 76, 'key' => "hu-HU", 'value' =>   "Írjon kreatív hirdetési ötletet a címen:\n\n ##audience## \n\n Termék név:\n ##title## \n\n Termékleírás:\n ##description## \n\n A cikk hangnemének kell lennie:\n ##tone_language## \n\n" ] ,

            ['id' => 2771, 'template_id' => 76, 'key' => "is-IS", 'value' =>   "Skrifaðu skapandi auglýsingahugmynd á:\n\n ##audience## \n\n Vöru Nafn:\n ##title## \n\n Vörulýsing:\n ##description## \n\n Rödd í greininni verður að vera:\n ##tone_language## \n\n" ] ,

            ['id' => 2772, 'template_id' => 76, 'key' => "id-ID", 'value' =>   "Tulis ide iklan kreatif di:\n\n ##audience## \n\n Nama Produk:\n ##title## \n\n Deskripsi Produk:\n ##description## \n\n Nada suara artikel harus:\n ##tone_language## \n\n" ] ,

            ['id' => 2773, 'template_id' => 76, 'key' => "it-IT", 'value' =>   "Scrivi un'idea pubblicitaria creativa su:\n\n ##audience## \n\n Nome del prodotto:\n ##title## \n\n Descrizione del prodotto:\n ##description## \n\n Il tono di voce dell'articolo deve essere:\n ##tone_language## \n\n" ] ,

            ['id' => 2774, 'template_id' => 76, 'key' => "ja-JP", 'value' =>   "創造的な広告のアイデアを次の場所に書いてください:\n\n ##audience## \n\n 商品名:\n ##title## \n\n Product description:\n ##description## \n\n 記事の口調は次のようにする必要があります。:\n ##tone_language## \n\n" ] ,

            ['id' => 2775, 'template_id' => 76, 'key' => "ko-KR", 'value' =>   "창의적인 광고 아이디어를 작성하세요.:\n\n ##audience## \n\n 상품명:\n ##title## \n\n 製品説明:\n ##description## \n\n 기사의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n" ] ,

            ['id' => 2776, 'template_id' => 76, 'key' => "ms-MY", 'value' =>   "Tulis idea iklan kreatif di:\n\n ##audience## \n\n Nama Produk:\n ##title## \n\n Penerangan Produk:\n ##description## \n\n Nada suara artikel mestilah:\n ##tone_language## \n\n" ] ,

            ['id' => 2777, 'template_id' => 76, 'key' => "nb-NO", 'value' =>   "Skriv en kreativ annonseidé på:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produktbeskrivelse:\n ##description## \n\n Tonen i artikkelen må være:\n ##tone_language## \n\n" ] ,

            ['id' => 2778, 'template_id' => 76, 'key' => "pl-PL", 'value' =>   "Napisz kreatywny pomysł na reklamę na:\n\n ##audience## \n\n Nazwa produktu:\n ##title## \n\n Opis produktu:\n ##description## \n\n Ton artykułu musi być:\n ##tone_language## \n\n" ] ,

            ['id' => 2779, 'template_id' => 76, 'key' => "pt-PT", 'value' =>   "Escreva uma ideia de anúncio criativo em:\n\n ##audience## \n\n Nome do Produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do artigo deve ser:\n ##tone_language## \n\n" ] ,

            ['id' => 2780, 'template_id' => 76, 'key' => "ru-RU", 'value' =>   "Напишите креативную рекламную идею на:\n\n ##audience## \n\n Наименование товара:\n ##title## \n\n Описание продукта:\n ##description## \n\n Тон статьи должен быть:\n ##tone_language## \n\n" ] ,

            ['id' => 2781, 'template_id' => 76, 'key' => "es-ES", 'value' =>   "Escribe una idea publicitaria creativa en:\n\n ##audience## \n\n Nombre del producto:\n ##title## \n\n Descripción del Producto:\n ##description## \n\n El tono de voz del artículo debe ser:\n ##tone_language## \n\n" ] ,

            ['id' => 2782, 'template_id' => 76, 'key' => "sv-SE", 'value' =>   "Skriv en kreativ annonsidé på:\n\n ##audience## \n\n Produktnamn:\n ##title## \n\n Produktbeskrivning:\n ##description## \n\n Tonen i artikeln måste vara:\n ##tone_language## \n\n" ] ,

            ['id' => 2783, 'template_id' => 76, 'key' => "tr-TR", 'value' =>   "yaratıcı bir reklam fikri yazın:\n\n ##audience## \n\n Ürün adı:\n ##title## \n\n Ürün Açıklaması:\n ##description## \n\n Makalenin ses tonu şu şekilde olmalıdır::\n ##tone_language## \n\n" ] ,

            ['id' => 2784, 'template_id' => 76, 'key' => "pt-BR", 'value' =>   "Escreva uma ideia de anúncio criativo em:\n\n ##audience## \n\n Nome do Produto:\n ##title## \n\n Descrição do produto:\n ##description## \n\n O tom de voz do artigo deve ser:\n ##tone_language## \n\n" ] ,

            ['id' => 2785, 'template_id' => 76, 'key' => "ro-RO", 'value' =>   "Scrie o idee de publicitate creativă la:\n\n ##audience## \n\n Numele produsului:\n ##title## \n\n Descriere produs:\n ##description## \n\n Tonul vocii articolului trebuie să fie:\n ##tone_language## \n\n" ] ,

            ['id' => 2786, 'template_id' => 76, 'key' => "vi-VN", 'value' =>   "Viết ý tưởng quảng cáo sáng tạo tại:\n\n ##audience## \n\n Tên sản phẩm:\n ##title## \n\n Mô tả Sản phẩm:\n ##description## \n\n Giọng điệu của bài viết phải:\n ##tone_language## \n\n" ] ,

            ['id' => 2787, 'template_id' => 76, 'key' => "sw-KE", 'value' =>   "Andika wazo la tangazo la ubunifu kwenye:\n\n ##audience## \n\n Jina la bidhaa:\n ##title## \n\n Maelezo ya bidhaa:\n ##description## \n\n Toni ya sauti ya makala lazima iwe:\n ##tone_language## \n\n" ] ,

            ['id' => 2788, 'template_id' => 76, 'key' => "sl-SI", 'value' =>   "Napišite kreativno oglasno idejo na:\n\n ##audience## \n\n Ime izdelka:\n ##title## \n\n Opis izdelka:\n ##description## \n\n Ton glasu članka mora biti:\n ##tone_language## \n\n" ] ,

            ['id' => 2789, 'template_id' => 76, 'key' => "th-TH", 'value' =>   "เขียนไอเดียโฆษณาสร้างสรรค์ได้ที่:\n\n ##audience## \n\n ชื่อผลิตภัณฑ์:\n ##title## \n\n รายละเอียดสินค้า:\n ##description## \n\n น้ำเสียงของบทความต้องเป็น:\n ##tone_language## \n\n" ] ,

            ['id' => 2790, 'template_id' => 76, 'key' => "uk-UA", 'value' =>   "Напишіть креативну рекламну ідею на:\n\n ##audience## \n\n Назва продукту:\n ##title## \n\n Опис продукту:\n ##description## \n\n Тон голосу статті повинен бути:\n ##tone_language## \n\n" ] ,

            ['id' => 2791, 'template_id' => 76, 'key' => "lt-LT", 'value' =>   "Parašykite kūrybinę reklamos idėją adresu:\n\n ##audience## \n\n Produkto pavadinimas:\n ##title## \n\n Prekės aprašymas:\n ##description## \n\n Straipsnio balso tonas turi būti:\n ##tone_language## \n\n" ] ,

            ['id' => 2792, 'template_id' => 76, 'key' => "bg-BG", 'value' =>   "Напишете креативна идея за реклама на:\n\n ##audience## \n\n Име на продукта:\n ##title## \n\n Описание на продукта:\n ##description## \n\n Тонът на гласа на статията трябва да бъде:\n ##tone_language## \n\n" ] ,
                
            ['id' => 2793, 'template_id' => 77, 'key' => "en-US", 'value' =>  "Write creative start up idea  for the following:\n\n  ##description##"],

            ['id' => 2794, 'template_id' => 77, 'key' => "ar-AE", 'value' =>  "اكتب فكرة بدء إبداعية لما يلي:\n\n  ##description##"],

            ['id' => 2795, 'template_id' => 77, 'key' => "cmn-CN", 'value' => "撰寫創意起始構想，以取得下列項目:\n\n  ##description##"],

            ['id' => 2796, 'template_id' => 77, 'key' => "hr-HR", 'value' =>  "Napišite kreativnu početnu ideju za sljedeće:\n\n  ##description##"],

            ['id' => 2797, 'template_id' => 77, 'key' => "cs-CZ", 'value' =>  "Napište kreativní počáteční nápad pro následující:\n\n  ##description##"],

            ['id' => 2798, 'template_id' => 77, 'key' => "da-DK", 'value' =>  "Skriv kreativ start-up idé til følgende:\n\n  ##description##"],

            ['id' => 2799, 'template_id' => 77, 'key' => "nl-NL", 'value' =>  "Schrijf een creatief opstartidee voor het volgende:\n\n  ##description##"],

            ['id' => 2800, 'template_id' => 77, 'key' => "et-EE", 'value' =>  "Kirjutage loov käivitamise idee järgmiseks:\n\n  ##description##"],

            ['id' => 2801, 'template_id' => 77, 'key' => "fi-FI", 'value' =>  "Kirjoita luova aloitusidea seuraavaa varten:\n\n  ##description##"],

            ['id' => 2802, 'template_id' => 77, 'key' => "fr-FR", 'value' =>  "Rédigez une idée de démarrage créative pour les éléments suivants:\n\n  ##description##"],

            ['id' => 2803, 'template_id' => 77, 'key' => "de-DE", 'value' =>  "Schreiben Sie eine kreative Startup-Idee für Folgendes:\n\n  ##description##"],

            ['id' => 2804, 'template_id' => 77, 'key' => "el-GR", 'value' =>  "Γράψτε μια δημιουργική ιδέα εκκίνησης για τα παρακάτω:\n\n  ##description##"],

            ['id' => 2805, 'template_id' => 77, 'key' => "he-IL", 'value' =>  "כתוב רעיון התחלה יצירתי עבור הדברים הבאים:\n\n  ##description##"],

            ['id' => 2806, 'template_id' => 77, 'key' => "hi-IN", 'value' =>  "निम्नलिखित के लिए रचनात्मक स्टार्ट अप विचार लिखें:\n\n  ##description##"],

            ['id' => 2807, 'template_id' => 77, 'key' => "hu-HU", 'value' =>  "Írjon kreatív indulási ötletet a következőkhöz:\n\n  ##description##"],

            ['id' => 2808, 'template_id' => 77, 'key' => "is-IS", 'value' =>  "Skrifaðu skapandi upphafshugmynd fyrir eftirfarandi:\n\n  ##description##"],

            ['id' => 2809, 'template_id' => 77, 'key' => "id-ID", 'value' =>  "Tulis ide awal kreatif untuk berikut:\n\n  ##description##"],

            ['id' => 2810, 'template_id' => 77, 'key' => "it-IT", 'value' =>  "Scrivi un'idea di avvio creativa per quanto segue:\n\n  ##description##"],

            ['id' => 2811, 'template_id' => 77, 'key' => "ja-JP", 'value' =>  "次のクリエイティブなスタートアップのアイデアを書いてください:\n\n  ##description##"],

            ['id' => 2812, 'template_id' => 77, 'key' => "ko-KR", 'value' =>   "다음에 대한 창의적인 시작 아이디어를 작성하십시오.:\n\n  ##description##"],

            ['id' => 2813, 'template_id' => 77, 'key' => "ms-MY", 'value' =>   "Tulis idea permulaan kreatif untuk perkara berikut:\n\n  ##description##"],

            ['id' => 2814, 'template_id' => 77, 'key' => "nb-NO", 'value' =>   "Skriv kreativ oppstartside for følgende:\n\n  ##description##"],

            ['id' => 2815, 'template_id' => 77, 'key' => "pl-PL", 'value' =>   "Napisz kreatywny pomysł na rozpoczęcie działalności w następujący sposób:\n\n  ##description##"],

            ['id' => 2816, 'template_id' => 77, 'key' => "pt-PT", 'value' =>   "Escreva uma ideia criativa de inicialização para o seguinte:\n\n  ##description##"],

            ['id' => 2817, 'template_id' => 77, 'key' => "ru-RU", 'value' =>   "Напишите творческую идею стартапа для следующего:\n\n  ##description##"],

            ['id' => 2818, 'template_id' => 77, 'key' => "es-ES", 'value' =>   "Escriba una idea creativa de puesta en marcha para lo siguiente:\n\n  ##description##"],

            ['id' => 2819, 'template_id' => 77, 'key' => "sv-SE", 'value' =>   "Skriv en kreativ startidé för följande:\n\n  ##description##"],

            ['id' => 2820, 'template_id' => 77, 'key' => "tr-TR", 'value' =>   "Aşağıdakiler için yaratıcı başlangıç fikri yazın:\n\n  ##description##"],

            ['id' => 2821, 'template_id' => 77, 'key' => "pt-BR", 'value' =>   "Escreva uma ideia criativa de inicialização para o seguinte:\n\n  ##description##"],

            ['id' => 2822, 'template_id' => 77, 'key' => "ro-RO", 'value' =>   "Scrieți o idee creativă de pornire pentru următoarele:\n\n  ##description##"],

            ['id' => 2823, 'template_id' => 77, 'key' => "vi-VN", 'value' =>   "Viết ý tưởng khởi nghiệp sáng tạo cho phần sau:\n\n  ##description##"],

            ['id' => 2824, 'template_id' => 77, 'key' => "sw-KE", 'value' =>   "Andika wazo bunifu la kuanzisha kwa yafuatayo:\n\n  ##description##"],

            ['id' => 2825, 'template_id' => 77, 'key' => "sl-SI", 'value' =>   "Napišite kreativno začetno idejo za naslednje:\n\n  ##description##"],

            ['id' => 2826, 'template_id' => 77, 'key' => "th-TH", 'value' =>   "เขียนแนวคิดเริ่มต้นที่สร้างสรรค์สำหรับสิ่งต่อไปนี้:\n\n  ##description##"],

            ['id' => 2827, 'template_id' => 77, 'key' => "uk-UA", 'value' =>   "Напишіть творчу ідею запуску для наступного:\n\n  ##description##"],

            ['id' => 2828, 'template_id' => 77, 'key' => "lt-LT", 'value' =>   "Parašykite kūrybinės pradžios idėją šiam tikslui:\n\n  ##description##"],

            ['id' => 2829, 'template_id' => 77, 'key' => "bg-BG", 'value' =>   "Напишете творческа стартова идея за следното:\n\n  ##description##"],

            ['id' => 2830, 'template_id' => 78, 'key' => "en-US", 'value' =>   "generator job post description for the following position : \n ##description## "],

            ['id' => 2831, 'template_id' => 78, 'key' => "ar-AE", 'value' =>   "وصف وظيفة المولد للوظيفة التالية : \n ##description## "],

            ['id' => 2832, 'template_id' => 78, 'key' => "cmn-CN", 'value' =>   "下列位置的產生器工作後置說明 : \n ##description## "],

            ['id' => 2833, 'template_id' => 78, 'key' => "hr-HR", 'value' =>   "generator opis radnog mjesta za sljedeću poziciju : \n ##description## "],

            ['id' => 2834, 'template_id' => 78, 'key' => "cs-CZ", 'value' =>   "generátor popisu pracovní pozice pro následující pozici : \n ##description## "],

            ['id' => 2835, 'template_id' => 78, 'key' => "da-DK", 'value' =>   "generator stillingsbeskrivelse for følgende stilling : \n ##description## "],

            ['id' => 2836, 'template_id' => 78, 'key' => "nl-NL", 'value' =>   "generator functiebeschrijving voor de volgende functie : \n ##description## "],

            ['id' => 2837, 'template_id' => 78, 'key' => "et-EE", 'value' =>   "generaatori tööpostituse kirjeldus järgmise ametikoha jaoks : \n ##description## "],

            ['id' => 2838, 'template_id' => 78, 'key' => "fi-FI", 'value' =>   "generaattorin työpaikan kuvaus seuraavalle työlle : \n ##description## "],

            ['id' => 2839, 'template_id' => 78, 'key' => "fr-FR", 'value' =>   "générateur de description de poste pour le poste suivant : \n ##description## "],

            ['id' => 2840, 'template_id' => 78, 'key' => "de-DE", 'value' =>   "Beschreibung des Generatorjobs für die folgende Position : \n ##description## "],

            ['id' => 2841, 'template_id' => 78, 'key' => "el-GR", 'value' =>   "περιγραφή θέσης εργασίας γεννήτριας για την παρακάτω θέση : \n ##description## "],

            ['id' => 2842, 'template_id' => 78, 'key' => "he-IL", 'value' =>   "תיאור פוסט המשרה של מחולל עבור התפקיד הבא : \n ##description## "],

            ['id' => 2843, 'template_id' => 78, 'key' => "hi-IN", 'value' =>   "निम्नलिखित पद के लिए जेनरेटर जॉब पोस्ट विवरण : \n ##description## "],

            ['id' => 2844, 'template_id' => 78, 'key' => "hu-HU", 'value' =>   "generátor állásleírás a következő pozícióhoz : \n ##description## "],

            ['id' => 2845, 'template_id' => 78, 'key' => "is-IS", 'value' =>   "lýsing á starfspósti fyrir eftirfarandi stöðu : \n ##description## "],

            ['id' => 2846, 'template_id' => 78, 'key' => "id-ID", 'value' =>   "deskripsi lowongan pekerjaan generator untuk posisi berikut : \n ##description## "],

            ['id' => 2847, 'template_id' => 78, 'key' => "it-IT", 'value' =>   "descrizione dell'annuncio di lavoro del generatore per la seguente posizione : \n ##description## "],

            ['id' => 2848, 'template_id' => 78, 'key' => "ja-JP", 'value' =>   "次のポジションのジェネレーターの求人説明 : \n ##description## "],

            ['id' => 2849, 'template_id' => 78, 'key' => "ko-KR", 'value' =>   "다음 위치에 대한 생성기 작업 게시물 설명 : \n ##description## "],

            ['id' => 2850, 'template_id' => 78, 'key' => "ms-MY", 'value' =>   "penerangan jawatan penjana untuk jawatan berikut : \n ##description## "],

            ['id' => 2851, 'template_id' => 78, 'key' => "nb-NO", 'value' =>   "generator stillingsbeskrivelse for følgende stilling : \n ##description## "],

            ['id' => 2852, 'template_id' => 78, 'key' => "pl-PL", 'value' =>   "generator opis stanowiska pracy dla następującego stanowiska : \n ##description## "],

            ['id' => 2853, 'template_id' => 78, 'key' => "pt-PT", 'value' =>   "descrição do posto de trabalho do gerador para a seguinte posição : \n ##description## "],

            ['id' => 2854, 'template_id' => 78, 'key' => "ru-RU", 'value' =>   "описание должности генератора для следующей должности : \n ##description## "],

            ['id' => 2855, 'template_id' => 78, 'key' => "es-ES", 'value' =>   "descripción del puesto de trabajo del generador para el siguiente puesto : \n ##description## "],

            ['id' => 2856, 'template_id' => 78, 'key' => "sv-SE", 'value' =>   "generator arbetsinläggsbeskrivning för följande position : \n ##description## "],

            ['id' => 2857, 'template_id' => 78, 'key' => "tr-TR", 'value' =>   "Aşağıdaki pozisyon için jeneratör iş ilanı açıklaması : \n ##description## "],

            ['id' => 2858, 'template_id' => 78, 'key' => "pt-BR", 'value' =>   "descrição do posto de trabalho do gerador para a seguinte posição : \n ##description## "],

            ['id' => 2859, 'template_id' => 78, 'key' => "ro-RO", 'value' =>   "descrierea postului de generator pentru următorul post : \n ##description## "],

            ['id' => 2860, 'template_id' => 78, 'key' => "vi-VN", 'value' =>   "mô tả bài đăng công việc máy phát điện cho vị trí sau : \n ##description## "],

            ['id' => 2861, 'template_id' => 78, 'key' => "sw-KE", 'value' =>   "maelezo ya chapisho la kazi ya jenereta kwa nafasi ifuatayo : \n ##description## "],

            ['id' => 2862, 'template_id' => 78, 'key' => "sl-SI", 'value' =>   "generator opis delovnega mesta za naslednje delovno mesto : \n ##description## "],

            ['id' => 2863, 'template_id' => 78, 'key' => "th-TH", 'value' =>   "รายละเอียดประกาศรับสมัครงานสำหรับตำแหน่งต่อไปนี้ : \n ##description## "],

            ['id' => 2864, 'template_id' => 78, 'key' => "uk-UA", 'value' =>   "генератор опис посади для наступної посади : \n ##description## "],

            ['id' => 2865, 'template_id' => 78, 'key' => "lt-LT", 'value' =>   "generatoriaus darbo etato aprašymas šiai pozicijai : \n ##description## "],

            ['id' => 2866, 'template_id' => 78, 'key' => "bg-BG", 'value' =>   "генератор описание на длъжността за следната позиция : \n ##description## "],
                
            ['id' => 2867, 'template_id' => 79, 'key' => "en-US", 'value' =>   "generator Resume for the following position : \n ##description## "],

            ['id' => 2868, 'template_id' => 79, 'key' => "ar-AE", 'value' =>   "مولد يستأنف للوظيفة التالية : \n ##description## "],

            ['id' => 2869, 'template_id' => 79, 'key' => "cmn-CN", 'value' =>   "產生器回復下列位置 : \n ##description## "],

            ['id' => 2870, 'template_id' => 79, 'key' => "hr-HR", 'value' =>   "generator Životopis za sljedeću poziciju : \n ##description## "],

            ['id' => 2871, 'template_id' => 79, 'key' => "cs-CZ", 'value' =>   "generátor Obnovit pro následující pozici : \n ##description## "],

            ['id' => 2872, 'template_id' => 79, 'key' => "da-DK", 'value' =>   "generator Genoptag for følgende position : \n ##description## "],

            ['id' => 2873, 'template_id' => 79, 'key' => "nl-NL", 'value' =>   "generator CV voor de volgende functie : \n ##description## "],

            ['id' => 2874, 'template_id' => 79, 'key' => "et-EE", 'value' =>   "generaator Jätka järgmisel ametikohal : \n ##description## "],

            ['id' => 2875, 'template_id' => 79, 'key' => "fi-FI", 'value' =>   "generaattori Jatka seuraavaan kohtaan : \n ##description## "],

            ['id' => 2876, 'template_id' => 79, 'key' => "fr-FR", 'value' =>   "générateur CV pour le poste suivant : \n ##description## "],

            ['id' => 2877, 'template_id' => 79, 'key' => "de-DE", 'value' =>   "Generator Lebenslauf für die folgende Position : \n ##description## "],

            ['id' => 2878, 'template_id' => 79, 'key' => "el-GR", 'value' =>   "γεννήτρια Συνεχίστε για την παρακάτω θέση : \n ##description## "],

            ['id' => 2879, 'template_id' => 79, 'key' => "he-IL", 'value' =>   "גנרטור קורות חיים לתפקיד הבא : \n ##description## "],

            ['id' => 2880, 'template_id' => 79, 'key' => "hi-IN", 'value' =>   "जनरेटर निम्नलिखित स्थिति के लिए फिर से शुरू करें : \n ##description## "],

            ['id' => 2881, 'template_id' => 79, 'key' => "hu-HU", 'value' =>   "generátor Folytatás a következő pozícióhoz : \n ##description## "],

            ['id' => 2882, 'template_id' => 79, 'key' => "is-IS", 'value' =>   "rafall Haltu áfram fyrir eftirfarandi stöðu : \n ##description## "],

            ['id' => 2883, 'template_id' => 79, 'key' => "id-ID", 'value' =>   "generator Lanjutkan untuk posisi berikut : \n ##description## "],

            ['id' => 2884, 'template_id' => 79, 'key' => "it-IT", 'value' =>   "generatore Riprendi per la posizione successiva : \n ##description## "],

            ['id' => 2885, 'template_id' => 79, 'key' => "ja-JP", 'value' =>   "発電機次のポジションの再開 : \n ##description## "],

            ['id' => 2886, 'template_id' => 79, 'key' => "ko-KR", 'value' =>   "다음 위치에 대한 발전기 이력서 : \n ##description## "],

            ['id' => 2887, 'template_id' => 79, 'key' => "ms-MY", 'value' =>   "generator Resume untuk kedudukan berikut : \n ##description## "],

            ['id' => 2888, 'template_id' => 79, 'key' => "nb-NO", 'value' =>   "generator Fortsett for følgende posisjon : \n ##description## "],

            ['id' => 2889, 'template_id' => 79, 'key' => "pl-PL", 'value' =>   "generator Wznów dla następującego stanowiska : \n ##description## "],

            ['id' => 2890, 'template_id' => 79, 'key' => "pt-PT", 'value' =>   "gerador Currículo para a seguinte posição : \n ##description## "],

            ['id' => 2891, 'template_id' => 79, 'key' => "ru-RU", 'value' =>   "генератор Резюме на следующую позицию : \n ##description## "],

            ['id' => 2892, 'template_id' => 79, 'key' => "es-ES", 'value' =>   "curriculum vitae del generador para la siguiente posición : \n ##description## "],

            ['id' => 2893, 'template_id' => 79, 'key' => "sv-SE", 'value' =>   "generator Återuppta för följande position : \n ##description## "],

            ['id' => 2894, 'template_id' => 79, 'key' => "tr-TR", 'value' =>   "Jeneratör Aşağıdaki konum için devam ettirin : \n ##description## "],

            ['id' => 2895, 'template_id' => 79, 'key' => "pt-BR", 'value' =>   "gerador Currículo para a seguinte posição : \n ##description## "],

            ['id' => 2896, 'template_id' => 79, 'key' => "ro-RO", 'value' =>   "generator Reluați pentru următoarea poziție : \n ##description## "],

            ['id' => 2897, 'template_id' => 79, 'key' => "vi-VN", 'value' =>   "Trình tạo Tiếp tục cho vị trí sau : \n ##description## "],

            ['id' => 2898, 'template_id' => 79, 'key' => "sw-KE", 'value' =>   "Jenereta Rejea kwa nafasi ifuatayo : \n ##description## "],

            ['id' => 2899, 'template_id' => 79, 'key' => "sl-SI", 'value' =>   "generator Življenjepis za naslednje delovno mesto : \n ##description## "],

            ['id' => 2900, 'template_id' => 79, 'key' => "th-TH", 'value' =>   "เครื่องกำเนิด Resume สำหรับตำแหน่งต่อไปนี้ : \n ##description## "],

            ['id' => 2901, 'template_id' => 79, 'key' => "uk-UA", 'value' =>   "generator Резюме на наступну посаду : \n ##description## "],

            ['id' => 2902, 'template_id' => 79, 'key' => "lt-LT", 'value' =>   "generatorius Tęskite šią poziciją : \n ##description## "],

            ['id' => 2903, 'template_id' => 79, 'key' => "bg-BG", 'value' =>   "генератор Резюме за следната позиция : \n ##description## "],

            ['id' => 2904, 'template_id' => 80, 'key' => "en-US", 'value' => "Write creative recipe  for the following  dish :\n\n  ##description###"],

            ['id' => 2905, 'template_id' => 80, 'key' => "ar-AE", 'value' => "اكتب وصفة إبداعية للطبق التالي:\n\n ##description##"],

            ['id' => 2906, 'template_id' => 80, 'key' => "cmn-CN", 'value' => "为下列菜肴写出创意食谱:\n\n ##description##"],

            ['id' => 2907, 'template_id' => 80, 'key' => "hr-HR", 'value' => "Napiši kreativni recept za sljedeće jelo:\n\n ##description##" ],

            ['id' => 2908, 'template_id' => 80, 'key' => "cs-CZ", 'value' => "Napište kreativní recept na následující jídlo:\n\n ##description##"],

            ['id' => 2909, 'template_id' => 80, 'key' => "da-DK", 'value' => 'Skriv kreativ opskrift på følgende ret:\n\n ##description##' ],

            ['id' => 2910, 'template_id' => 80, 'key' => "nl-NL", 'value' => 'Schrijf creatief recept voor het volgende gerecht:\n\n ##description##' ],

            ['id' => 2911, 'template_id' => 80, 'key' => "et-EE", 'value' => 'Kirjutage järgmise roa loominguline retsept:\n\n ##description## '],

            ['id' => 2912, 'template_id' => 80, 'key' => "fi-FI", 'value' => 'Kirjoita luova resepti seuraavaan ruokalajiin:\n\n ##description## '],

            ['id' => 2913, 'template_id' => 80, 'key' => "fr-FR", 'value' => 'Écrivez une recette créative pour le plat suivant:\n\n ##description## '],

            ['id' => 2914, 'template_id' => 80, 'key' => "de-DE", 'value' => 'Schreiben Sie ein kreatives Rezept für das folgende Gericht:\n\n ##description## '],

            ['id' => 2915, 'template_id' => 80, 'key' => "el-GR", 'value' => 'Γράψε δημιουργική συνταγή για το παρακάτω πιάτο:\n\n ##description## '],

            ['id' => 2916, 'template_id' => 80, 'key' => "he-IL", 'value' => 'כתבו מתכון יצירתי למנה הבאה:\n\n ##description## '],

            ['id' => 2917, 'template_id' => 80, 'key' => "hi-IN", 'value' => 'निम्नलिखित व्यंजन के लिए रचनात्मक व्यंजन विधि लिखिए:\n\n ##description## '],

            ['id' => 2918, 'template_id' => 80, 'key' => "hu-HU", 'value' => 'Írj kreatív receptet a következő ételhez!:\n\n ##description## '],

            ['id' => 2919, 'template_id' => 80, 'key' => "is-IS", 'value' => 'Skrifaðu skapandi uppskrift að eftirfarandi rétti:\n\n ##description## '],

            ['id' => 2920, 'template_id' => 80, 'key' => "id-ID", 'value' => 'Tulis resep kreatif untuk hidangan berikut:\n\n ##description##'],

            ['id' => 2921, 'template_id' => 80, 'key' => "it-IT", 'value' => 'Scrivi una ricetta creativa per il seguente piatto:\n\n ##description## '],

            ['id' => 2922, 'template_id' => 80, 'key' => "ja-JP", 'value' => '次の料理の独創的なレシピを書いてください:\n\n ##description## '],

            ['id' => 2923, 'template_id' => 80, 'key' => "ko-KR", 'value' => '다음 요리에 대한 독창적인 레시피 작성:\n\n ##description##' ],

            ['id' => 2924, 'template_id' => 80, 'key' => "ms-MY", 'value' => 'Tulis resipi kreatif untuk hidangan berikut:\n\n ##description## '],

            ['id' => 2925, 'template_id' => 80, 'key' => "nb-NO", 'value' => 'Skriv kreativ oppskrift på følgende rett:\n\n ##description## '],

            ['id' => 2926, 'template_id' => 80, 'key' => "pl-PL", 'value' => 'Napisz kreatywny przepis na poniższe danie:\n\n ##description## '],

            ['id' => 2927, 'template_id' => 80, 'key' => "pt-PT", 'value' => 'Escreva uma receita criativa para o seguinte prato:\n\n ##description## '],

            ['id' => 2928, 'template_id' => 80, 'key' => "ru-RU", 'value' => 'Напишите креативный рецепт следующего блюда:\n\n ##description## '],

            ['id' => 2929, 'template_id' => 80, 'key' => "es-ES", 'value' => 'Escribe una receta creativa para el siguiente plato.:\n\n ##description## '],

            ['id' => 2930, 'template_id' => 80, 'key' => "sv-SE", 'value' => 'Skriv kreativt recept för följande maträtt:\n\n ##description##' ],

            ['id' => 2931, 'template_id' => 80, 'key' => "tr-TR", 'value' => 'Aşağıdaki yemek için yaratıcı bir tarif yazın:\n\n ##description## '],

            ['id' => 2932, 'template_id' => 80, 'key' => "pt-BR", 'value' => 'Escreve uma receita criativa para o seguinte prato:\n\n ##description## '],

            ['id' => 2933, 'template_id' => 80, 'key' => "ro-RO", 'value' => 'Scrieți o rețetă creativă pentru următorul fel de mâncare:\n\n ##description## '],

            ['id' => 2934, 'template_id' => 80, 'key' => "vi-VN", 'value' => 'Viết công thức sáng tạo cho món ăn sau:\n\n ##description##' ],

            ['id' => 2935, 'template_id' => 80, 'key' => "sw-KE", 'value' => 'Andika kichocheo cha ubunifu kwa sahani ifuatayo:\n\n ##description## '],

            ['id' => 2936, 'template_id' => 80, 'key' => "sl-SI", 'value' => 'Napišite kreativen recept za naslednjo jed:\n\n ##description##' ],

            ['id' => 2937, 'template_id' => 80, 'key' => "th-TH", 'value' => 'เขียนสูตรสร้างสรรค์สำหรับอาหารจานต่อไปนี้:\n\n ##description## '],

            ['id' => 2938, 'template_id' => 80, 'key' => "uk-UA", 'value' => 'Напишіть творчий рецепт наступної страви:\n\n ##description## '],

            ['id' => 2939, 'template_id' => 80, 'key' => "lt-LT", 'value' => 'Parašykite kūrybinį šio patiekalo receptą:\n\n ##description## '],

            ['id' => 2940, 'template_id' => 80, 'key' => "bg-BG", 'value' => 'Напишете творческа рецепта за следното ястие:\n\n ##description## '],

            ['id' => 2941, 'template_id' => 81, 'key' => "en-US", 'value' => 'Write creative poetry  for the following:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 2942, 'template_id' => 81, 'key' => "ar-AE", 'value' => 'اكتب شعرًا إبداعيًا لما يلي:\n\n ##description## \n\n TTالوحيدة من صوت النتيجة يجب أن تكون::\n ##tone_language## \n\n'],

            ['id' => 2943, 'template_id' => 81, 'key' => "cmn-CN", 'value' => '为以下创作诗歌:\n\n ##description## \n\n 结果的声音必须是:\n ##tone_language## \n\n'],

            ['id' => 2944, 'template_id' => 81, 'key' => "hr-HR", 'value' => 'Napišite kreativnu poeziju za sljedeće:\n\n ##description## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n'],

            ['id' => 2945, 'template_id' => 81, 'key' => "cs-CZ", 'value' => 'Napište kreativní poezii pro následující:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n'],

            ['id' => 2946, 'template_id' => 81, 'key' => "da-DK", 'value' => 'Skriv kreativ poesi til følgende:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n'],

            ['id' => 2947, 'template_id' => 81, 'key' => "nl-NL", 'value' => 'Schrijf creatieve poëzie voor het volgende:\n\n ##description## \n\n Toon van de stem van het resultaat moet zijn:\n ##tone_language## \n\n'],

            ['id' => 2948, 'template_id' => 81, 'key' => "et-EE", 'value' => 'Kirjutage loomingulist luulet järgmiste jaoks:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 2949, 'template_id' => 81, 'key' => "fi-FI", 'value' => 'Kirjoita luovaa runoutta seuraavaa varten:\n\n ##description## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n'],

            ['id' => 2950, 'template_id' => 81, 'key' => "fr-FR", 'value' => 'Écrivez de la poésie créative pour les éléments suivants:\n\n ##description## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n'],

            ['id' => 2951, 'template_id' => 81, 'key' => "de-DE", 'value' => 'Schreiben Sie kreative Gedichte für Folgendes:\n\n ##description## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n'],

            ['id' => 2952, 'template_id' => 81, 'key' => "el-GR", 'value' => 'Γράψε δημιουργική ποίηση για τα παρακάτω:\n\n ##description## \n\n Η φωνή του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 2953, 'template_id' => 81, 'key' => "he-IL", 'value' => ' כתבו שירה יצירתית עבור הבאים:\n\n ##description## \n\n של הקול של התוצאה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 2954, 'template_id' => 81, 'key' => "hi-IN", 'value' => 'निम्नलिखित के लिए रचनात्मक कविताएँ लिखिए:\n\n ##description## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n'],

            ['id' => 2955, 'template_id' => 81, 'key' => "hu-HU", 'value' => 'Írj kreatív verset a következőkhöz!:\n\n ##description## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n'],

            ['id' => 2956, 'template_id' => 81, 'key' => "is-IS", 'value' => 'Skrifaðu skapandi ljóð fyrir eftirfarandi:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 2957, 'template_id' => 81, 'key' => "id-ID", 'value' => 'Tulis puisi kreatif untuk berikut ini:\n\n ##description## \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n'],

            ['id' => 2958, 'template_id' => 81, 'key' => "it-IT", 'value' => 'Scrivi poesie creative per quanto segue:\n\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n'],

            ['id' => 2959, 'template_id' => 81, 'key' => "ja-JP", 'value' => '以下のために創造的な詩を書いてください:\n\n ##description## \n\n 結果の声のトーンは:\n ##tone_language## \n\n'],

            ['id' => 2960, 'template_id' => 81, 'key' => "ko-KR", 'value' => '다음을 위해 창의적인 시를 쓰세요.:\n\n ##description## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다:\n ##tone_language## \n\n'],

            ['id' => 2961, 'template_id' => 81, 'key' => "ms-MY", 'value' => 'Tulis puisi kreatif untuk perkara berikut:\n\n ##description## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n'],

            ['id' => 2962, 'template_id' => 81, 'key' => "nb-NO", 'value' => 'Skriv kreativ poesi for følgende:\n\n ##description## \n\n Tone av stemmen til resultatet må være:\n ##tone_language## \n\n'],

            ['id' => 2963, 'template_id' => 81, 'key' => "pl-PL", 'value' => 'Napisz twórczą poezję dla następujących:\n\n ##description## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n'],

            ['id' => 2964, 'template_id' => 81, 'key' => "pt-PT", 'value' => 'Escrever poesia criativa para os seguintes temas:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 2965, 'template_id' => 81, 'key' => "ru-RU", 'value' => 'Пишите творческие стихи для следующих:\n\n ##description## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n'],

            ['id' => 2966, 'template_id' => 81, 'key' => "es-ES", 'value' => 'Escribe poesía creativa para los siguientes:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n'],

            ['id' => 2967, 'template_id' => 81, 'key' => "sv-SE", 'value' => 'Skriv kreativ poesi för följande:\n\n ##description## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n'],

            ['id' => 2968, 'template_id' => 81, 'key' => "tr-TR", 'value' => 'Aşağıdakiler için yaratıcı şiir yazın:\n\n ##description## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n'],

            ['id' => 2969, 'template_id' => 81, 'key' => "pt-BR", 'value' => 'Escreva poesia criativa para o seguinte:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 2970, 'template_id' => 81, 'key' => "ro-RO", 'value' => 'Scrie poezie creativă pentru următoarele:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 2971, 'template_id' => 81, 'key' => "vi-VN", 'value' => 'Viết thơ sáng tạo cho những điều sau đây:\n\n ##description## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n'],

            ['id' => 2972, 'template_id' => 81, 'key' => "sw-KE", 'value' => 'Andika mashairi ya ubunifu kwa yafuatayo:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 2973, 'template_id' => 81, 'key' => "sl-SI", 'value' => 'Napišite ustvarjalno poezijo za naslednje:\n\n ##description## \n\n  Ton glasu rezultata mora biti:\n ##tone_language## \n\n'],

            ['id' => 2974, 'template_id' => 81, 'key' => "th-TH", 'value' => 'เขียนบทกวีสร้างสรรค์ต่อไปนี้:\n\n ##description## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 2975, 'template_id' => 81, 'key' => "uk-UA", 'value' => 'Напишіть творчі вірші для наступного:\n\n ##description## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n'],

            ['id' => 2976, 'template_id' => 81, 'key' => "lt-LT", 'value' => 'Rašykite kūrybinę poeziją šiems dalykams:\n\n ##description## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n'],

            ['id' => 2977, 'template_id' => 81, 'key' => "bg-BG", 'value' => 'Напишете творческа поезия за следното:\n\n ##description## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 2978, 'template_id' => 82, 'key' => "en-US", 'value' => 'Write progress Report for the following :\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 2979, 'template_id' => 82, 'key' => "ar-AE", 'value' => 'اكتب تقرير التقدم لما يلي:\n\n ##description## \n\n TTالوحيدة من صوت النتيجة يجب أن تكون::\n ##tone_language## \n\n'],

            ['id' => 2980, 'template_id' => 82, 'key' => "cmn-CN", 'value' => '为以下项目撰写进度报告:\n\n ##description## \n\n 结果的声音必须是:\n ##tone_language## \n\n'],

            ['id' => 2981, 'template_id' => 82, 'key' => "hr-HR", 'value' => 'Napišite izvješće o napretku za sljedeće:\n\n ##description## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n'],

            ['id' => 2982, 'template_id' => 82, 'key' => "cs-CZ", 'value' => 'Napište zprávu o pokroku pro následující:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n'],

            ['id' => 2983, 'template_id' => 82, 'key' => "da-DK", 'value' => 'Skriv statusrapport for følgende:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n'],

            ['id' => 2984, 'template_id' => 82, 'key' => "nl-NL", 'value' => 'Voortgangsrapport schrijven voor het volgende:\n\n ##description## \n\n Toon van de stem van het resultaat moet zijn:\n ##tone_language## \n\n'],

            ['id' => 2985, 'template_id' => 82, 'key' => "et-EE", 'value' => 'Kirjutage edenemisaruanne järgmiste asjade jaoks:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 2986, 'template_id' => 82, 'key' => "fi-FI", 'value' => 'Kirjoita edistymisraportti seuraavista asioista:\n\n ##description## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n'],

            ['id' => 2987, 'template_id' => 82, 'key' => "fr-FR", 'value' => 'Rédiger un rapport d avancement pour les éléments suivants:\n\n ##description## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n'],

            ['id' => 2988, 'template_id' => 82, 'key' => "de-DE", 'value' => 'Schreiben Sie einen Fortschrittsbericht für Folgendes:\n\n ##description## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n'],

            ['id' => 2989, 'template_id' => 82, 'key' => "el-GR", 'value' => 'Γράψτε αναφορά προόδου για τα ακόλουθα:\n\n ##description## \n\n Η φωνή του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 2990, 'template_id' => 82, 'key' => "he-IL", 'value' => 'כתוב דוח התקדמות עבור הדברים הבאים:\n\n ##description## \n\n של הקול של התוצאה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 2991, 'template_id' => 82, 'key' => "hi-IN", 'value' => 'निम्नलिखित के लिए प्रगति रिपोर्ट लिखें:\n\n ##description## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n'],

            ['id' => 2992, 'template_id' => 82, 'key' => "hu-HU", 'value' => 'Írjon előrehaladási jelentést a következőkhöz:\n\n ##description## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n'],

            ['id' => 2993, 'template_id' => 82, 'key' => "is-IS", 'value' => 'Skrifaðu framvinduskýrslu fyrir eftirfarandi:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 2994, 'template_id' => 82, 'key' => "id-ID", 'value' => 'Tulis laporan kemajuan untuk hal-hal berikut:\n\n ##description## \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n'],

            ['id' => 2995, 'template_id' => 82, 'key' => "it-IT", 'value' => 'Scrivi un rapporto sui progressi per quanto segue:\n\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n'],

            ['id' => 2996, 'template_id' => 82, 'key' => "ja-JP", 'value' => '以下の進捗レポートを作成します:\n\n ##description## \n\n 結果の声のトーンは:\n ##tone_language## \n\n'],

            ['id' => 2997, 'template_id' => 82, 'key' => "ko-KR", 'value' => '다음에 대한 진행 보고서 작성:\n\n ##description## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다:\n ##tone_language## \n\n'],

            ['id' => 2998, 'template_id' => 82, 'key' => "ms-MY", 'value' => 'Tulis Laporan kemajuan untuk perkara berikut:\n\n ##description## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n'],

            ['id' => 2999, 'template_id' => 82, 'key' => "nb-NO", 'value' => 'Skriv fremdriftsrapport for følgende:\n\n ##description## \n\n Tone av stemmen til resultatet må være:\n ##tone_language## \n\n'],

            ['id' => 3000, 'template_id' => 82, 'key' => "pl-PL", 'value' => 'Napisz raport z postępów dla następujących:\n\n ##description## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n'],

            ['id' => 3001, 'template_id' => 82, 'key' => "pt-PT", 'value' => 'Redigir um relatório de progresso para o seguinte:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 3002, 'template_id' => 82, 'key' => "ru-RU", 'value' => 'Напишите отчет о проделанной работе для следующего:\n\n ##description## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n'],

            ['id' => 3003, 'template_id' => 82, 'key' => "es-ES", 'value' => 'Escriba el informe de progreso para lo siguiente:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n'],

            ['id' => 3004, 'template_id' => 82, 'key' => "sv-SE", 'value' => 'Skriv lägesrapport för följande:\n\n ##description## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n'],

            ['id' => 3005, 'template_id' => 82, 'key' => "tr-TR", 'value' => 'Aşağıdakiler için ilerleme raporu yaz:\n\n ##description## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n'],

            ['id' => 3006, 'template_id' => 82, 'key' => "pt-BR", 'value' => 'Escreva um relatório de progresso para o seguinte:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 3007, 'template_id' => 82, 'key' => "ro-RO", 'value' => 'Scrieți un raport de progres pentru următoarele:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 3008, 'template_id' => 82, 'key' => "vi-VN", 'value' => 'Viết báo cáo tiến độ cho phần sau:\n\n ##description## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n'],

            ['id' => 3009, 'template_id' => 82, 'key' => "sw-KE", 'value' => 'Andika Ripoti ya maendeleo kwa yafuatayo:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 3010, 'template_id' => 82, 'key' => "sl-SI", 'value' => 'Napišite poročilo o napredku za naslednje:\n\n ##description## \n\n  Ton glasu rezultata mora biti:\n ##tone_language## \n\n'],

            ['id' => 3011, 'template_id' => 82, 'key' => "th-TH", 'value' => 'เขียนรายงานความคืบหน้าต่อไปนี้ : \n\n ##description## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 3012, 'template_id' => 82, 'key' => "uk-UA", 'value' => 'Напишіть звіт про прогрес для наступного:\n\n ##description## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n'],

            ['id' => 3013, 'template_id' => 82, 'key' => "lt-LT", 'value' => 'Parašykite pažangos ataskaitą dėl šių dalykų:\n\n ##description## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n'],

            ['id' => 3014, 'template_id' => 82, 'key' => "bg-BG", 'value' => 'Напишете отчет за напредъка за следното:\n\n ##description## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 3015, 'template_id' => 83, 'key' => "en-US", 'value' => 'Write fictional story idea for the following:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 3016, 'template_id' => 83, 'key' => "ar-AE", 'value' => 'اكتب فكرة قصة خيالية لما يلي:\n\n ##description## \n\n TTالوحيدة من صوت النتيجة يجب أن تكون::\n ##tone_language## \n\n'],

            ['id' => 3017, 'template_id' => 83, 'key' => "cmn-CN", 'value' => '为以下内容写虚构的故事构想:\n\n ##description## \n\n 结果的声音必须是:\n ##tone_language## \n\n'],

            ['id' => 3018, 'template_id' => 83, 'key' => "hr-HR", 'value' => 'Napišite ideju izmišljene priče za sljedeće:\n\n ##description## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n'],

            ['id' => 3019, 'template_id' => 83, 'key' => "cs-CZ", 'value' => 'Napište myšlenku na fiktivní příběh pro následující:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n'],

            ['id' => 3020, 'template_id' => 83, 'key' => "da-DK", 'value' => 'Skriv en fiktiv historieidé til følgende:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n'],

            ['id' => 3021, 'template_id' => 83, 'key' => "nl-NL", 'value' => 'Schrijf een fictief verhaalidee voor het volgende:\n\n ##description## \n\n Toon van de stem van het resultaat moet zijn:\n ##tone_language## \n\n'],

            ['id' => 3022, 'template_id' => 83, 'key' => "et-EE", 'value' => 'Kirjutage väljamõeldud loo idee järgmise jaoks:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 3023, 'template_id' => 83, 'key' => "fi-FI", 'value' => 'Kirjoita kuvitteellinen tarinaidea seuraavaa varten:\n\n ##description## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n'],

            ['id' => 3024, 'template_id' => 83, 'key' => "fr-FR", 'value' => 'Écrivez une idée d histoire fictive pour ce qui suit:\n\n ##description## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n'],

            ['id' => 3025, 'template_id' => 83, 'key' => "de-DE", 'value' => 'Schreiben Sie eine fiktive Story-Idee für Folgendes:\n\n ##description## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n'],

            ['id' => 3026, 'template_id' => 83, 'key' => "el-GR", 'value' => 'Γράψτε μια ιδέα φανταστικής ιστορίας για τα παρακάτω:\n\n ##description## \n\n Η φωνή του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 3027, 'template_id' => 83, 'key' => "he-IL", 'value' => 'כתוב רעיון לסיפור בדיוני עבור הדברים הבאים :\n\n ##description## \n\n של הקול של התוצאה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 3028, 'template_id' => 83, 'key' => "hi-IN", 'value' => 'निम्नलिखित के लिए काल्पनिक कहानी विचार लिखें:\n\n ##description## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n'],

            ['id' => 3029, 'template_id' => 83, 'key' => "hu-HU", 'value' => 'Írj kitalált történetötletet a következőkhöz!:\n\n ##description## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n'],

            ['id' => 3030, 'template_id' => 83, 'key' => "is-IS", 'value' => 'Skrifaðu skáldaða söguhugmynd fyrir eftirfarandi:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 3031, 'template_id' => 83, 'key' => "id-ID", 'value' => 'Tuliskan ide cerita fiksi untuk berikut ini:\n\n ##description## \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n'],

            ['id' => 3032, 'template_id' => 83, 'key' => "it-IT", 'value' => 'Scrivi un idea per una storia immaginaria per quanto segue:\n\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n'],

            ['id' => 3033, 'template_id' => 83, 'key' => "ja-JP", 'value' => '以下の架空の物語のアイデアを書いてください:\n\n ##description## \n\n 結果の声のトーンは:\n ##tone_language## \n\n'],

            ['id' => 3034, 'template_id' => 83, 'key' => "ko-KR", 'value' => '다음에 대한 허구의 이야기 아이디어 쓰기:\n\n ##description## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다:\n ##tone_language## \n\n'],

            ['id' => 3035, 'template_id' => 83, 'key' => "ms-MY", 'value' => 'Tulis idea cerita fiksyen untuk yang berikut:\n\n ##description## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n'],

            ['id' => 3036, 'template_id' => 83, 'key' => "nb-NO", 'value' => 'Skriv en fiktiv historieidé for følgende:\n\n ##description## \n\n Tone av stemmen til resultatet må være:\n ##tone_language## \n\n'],

            ['id' => 3037, 'template_id' => 83, 'key' => "pl-PL", 'value' => 'Napisz pomysł na fabułę opowiadania pt:\n\n ##description## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n'],

            ['id' => 3038, 'template_id' => 83, 'key' => "pt-PT", 'value' => 'Escreve uma ideia de história de ficção para o seguinte:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 3039, 'template_id' => 83, 'key' => "ru-RU", 'value' => 'Напишите идею вымышленного рассказа для следующего:\n\n ##description## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n'],

            ['id' => 3040, 'template_id' => 83, 'key' => "es-ES", 'value' => 'Escribe una idea de historia ficticia para lo siguiente:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n'],

            ['id' => 3041, 'template_id' => 83, 'key' => "sv-SE", 'value' => 'Skriv en fiktiv berättelseidé för följande:\n\n ##description## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n'],

            ['id' => 3042, 'template_id' => 83, 'key' => "tr-TR", 'value' => 'Aşağıdakiler için kurgusal hikaye fikri yazın:\n\n ##description## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n'],

            ['id' => 3043, 'template_id' => 83, 'key' => "pt-BR", 'value' => 'Escreva uma ideia de história fictícia para o seguinte:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 3044, 'template_id' => 83, 'key' => "ro-RO", 'value' => 'Scrieți o idee de poveste fictivă pentru următoarele:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 3045, 'template_id' => 83, 'key' => "vi-VN", 'value' => 'Viết ý tưởng câu chuyện hư cấu cho những điều sau đây:\n\n ##description## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n'],

            ['id' => 3046, 'template_id' => 83, 'key' => "sw-KE", 'value' => 'Andika wazo la hadithi ya kubuni kwa zifuatazo:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 3047, 'template_id' => 83, 'key' => "sl-SI", 'value' => 'Napišite idejo izmišljene zgodbe za naslednje:\n\n ##description## \n\n  Ton glasu rezultata mora biti:\n ##tone_language## \n\n'],

            ['id' => 3048, 'template_id' => 83, 'key' => "th-TH", 'value' => 'เขียนแนวคิดเรื่องสมมติต่อไปนี้ : \n\n ##description## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 3049, 'template_id' => 83, 'key' => "uk-UA", 'value' => 'Напишіть ідею художньої історії для наступного:\n\n ##description## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n'],

            ['id' => 3050, 'template_id' => 83, 'key' => "lt-LT", 'value' => 'Parašykite išgalvotos istorijos idėją šiai temai:\n\n ##description## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n'],

            ['id' => 3051, 'template_id' => 83, 'key' => "bg-BG", 'value' => 'Напишете идея за измислена история за следното:\n\n ##description## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 3052, 'template_id' => 84, 'key' => "en-US", 'value' =>   "Write 10 catchy webinar title ideas for the following  :\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 3053, 'template_id' => 84, 'key' => "ar-AE", 'value' =>   "اكتب 10 أفكار جذابة لعنوان ندوة عبر الإنترنت لما يلي:\n\n ##description## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 3054, 'template_id' => 84, 'key' => "cmn-CN", 'value' =>   "为以下内容编写 10 个引人入胜的网络研讨会标题创意：\n\n ##description## \n\n 结果的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 3055, 'template_id' => 84, 'key' => "hr-HR", 'value' =>   "Napišite 10 zanimljivih ideja za naslov webinara za sljedeće:\n\n ##description## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 3056, 'template_id' => 84, 'key' => "cs-CZ", 'value' =>   "Napište 10 chytlavých nápadů na název webináře pro následující:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 3057, 'template_id' => 84, 'key' => "da-DK", 'value' =>   "Skriv 10 iørefaldende webinartitelideer til følgende:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 3058, 'template_id' => 84, 'key' => "nl-NL", 'value' =>   "Schrijf 10 pakkende titelideeën voor webinars voor het volgende:\n\n ##description## \n\n Tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 3059, 'template_id' => 84, 'key' => "et-EE", 'value' =>   "Kirjutage 10 meeldejäävat veebiseminari pealkirjaideed järgmiste asjade jaoks:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 3060, 'template_id' => 84, 'key' => "fi-FI", 'value' =>   "Kirjoita 10 tarttuvaa webinaarin otsikkoideaa seuraavista aiheista:\n\n ##description## \n\n Tuloksen äänensävyn tulee olla:\n ##tone_language## \n\n"],

            ['id' => 3061, 'template_id' => 84, 'key' => "fr-FR", 'value' =>   "Rédigez 10 idées de titres de webinaires accrocheurs pour les éléments suivants :\n\n ##description## \n\n Le ton de voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 3062, 'template_id' => 84, 'key' => "de-DE", 'value' =>   "Schreiben Sie 10 einprägsame Webinar-Titelideen für Folgendes:\n\n ##description## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 3063, 'template_id' => 84, 'key' => "el-GR", 'value' =>   "Γράψτε 10 ελκυστικές ιδέες τίτλου διαδικτυακού σεμιναρίου για τα ακόλουθα:\n\n ##description## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 3064, 'template_id' => 84, 'key' => "he-IL", 'value' =>   "כתוב 10 רעיונות לכותרות סמינרים מקוונים עבור הדברים הבאים:\n\n ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 3065, 'template_id' => 84, 'key' => "hi-IN", 'value' =>   "निम्नलिखित के लिए 10 आकर्षक वेबिनार शीर्षक विचार लिखें:\n\n ##description## \n\n परिणाम की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 3066, 'template_id' => 84, 'key' => "hu-HU", 'value' =>   "Írj 10 fülbemászó webinárium címötletet a következőkhöz:\n\n ##description## \n\n Az eredmény hangnemének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 3067, 'template_id' => 84, 'key' => "is-IS", 'value' =>   "Skrifaðu 10 grípandi titilhugmyndir fyrir vefnámskeið fyrir eftirfarandi:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 3068, 'template_id' => 84, 'key' => "id-ID", 'value' =>   "Tulis 10 ide judul webinar yang menarik sebagai berikut :\n\n ##description## \n\n Nada suara hasil harus:\n ##tone_language## \n\n"],

            ['id' => 3069, 'template_id' => 84, 'key' => "it-IT", 'value' =>   "Scrivi 10 accattivanti idee per titoli di webinar per quanto segue:\n\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 3070, 'template_id' => 84, 'key' => "ja-JP", 'value' =>   "以下について、キャッチーなウェビナー タイトルのアイデアを 10 個書いてください:\n\n ##description## \n\n 結果の口調は次のようになります:\n ##tone_language## \n\n"],

            ['id' => 3071, 'template_id' => 84, 'key' => "ko-KR", 'value' =>   "다음에 대한 10가지 흥미로운 웨비나 제목 아이디어를 작성하십시오:\n\n ##description## \n\n 결과의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 3072, 'template_id' => 84, 'key' => "ms-MY", 'value' =>   "Tulis 10 idea tajuk webinar yang menarik untuk perkara berikut:\n\n ##description## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 3073, 'template_id' => 84, 'key' => "nb-NO", 'value' =>   "Skriv 10 fengende webinartittelideer for følgende:\n\n ##description## \n\n Stemmetonen for resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 3074, 'template_id' => 84, 'key' => "pl-PL", 'value' =>   "Napisz 10 chwytliwych pomysłów na tytuły webinarów:\n\n ##description## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 3075, 'template_id' => 84, 'key' => "pt-PT", 'value' =>   "Escreva 10 ideias atraentes de títulos de webinar para o seguinte:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 3076, 'template_id' => 84, 'key' => "ru-RU", 'value' =>   "Напишите 10 броских идей названия вебинара для следующего:\n\n ##description## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 3077, 'template_id' => 84, 'key' => "es-ES", 'value' =>   "Escriba 10 ideas pegadizas para títulos de seminarios web para lo siguiente:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 3078, 'template_id' => 84, 'key' => "sv-SE", 'value' =>   "Skriv 10 fängslande webbinariumtitelidéer för följande:\n\n ##description## \n\n Tonen i resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 3079, 'template_id' => 84, 'key' => "tr-TR", 'value' =>   "Aşağıdakiler için akılda kalıcı 10 web semineri başlığı fikri yazın:\n\n ##description## \n\n Sonucun ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 3080, 'template_id' => 84, 'key' => "pt-BR", 'value' =>   "Escreva 10 ideias de títulos de webinar atraentes para os seguintes itens:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 3081, 'template_id' => 84, 'key' => "ro-RO", 'value' =>   "Scrieți 10 idei captivante pentru titluri de webinar pentru următoarele:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 3082, 'template_id' => 84, 'key' => "vi-VN", 'value' =>   "Viết 10 ý tưởng tiêu đề hội thảo trên web hấp dẫn cho những nội dung sau:\n\n ##description## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 3083, 'template_id' => 84, 'key' => "sw-KE", 'value' =>   "Andika mawazo 10 ya kuvutia ya mada ya wavuti kwa yafuatayo:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 3084, 'template_id' => 84, 'key' => "sl-SI", 'value' =>   "Napišite 10 idej za privlačne naslove spletnih seminarjev za naslednje:\n\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 3085, 'template_id' => 84, 'key' => "th-TH", 'value' =>   "เขียน 10 แนวคิดเกี่ยวกับหัวข้อการสัมมนาผ่านเว็บที่จับใจสำหรับสิ่งต่อไปนี้:\n\n ##description## \n\n โทนเสียงของผลลัพธ์ต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 3086, 'template_id' => 84, 'key' => "uk-UA", 'value' =>   "Напишіть 10 цікавих ідей назви вебінару для наступного:\n\n ##description## \n\n Тон голосу результату повинен бути таким:\n ##tone_language## \n\n"],

            ['id' => 3087, 'template_id' => 84, 'key' => "lt-LT", 'value' =>   "Parašykite 10 patrauklių internetinio seminaro pavadinimo idėjų, skirtų šiems dalykams:\n\n ##description## \n\n Rezultato balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 3088, 'template_id' => 84, 'key' => "bg-BG", 'value' =>   "Напишете 10 закачливи идеи за заглавия на уебинара за следното:\n\n ##description## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 3089, 'template_id' => 85, 'key' => "en-US", 'value' => "Write 10 interesting titles for snapchat ad  of the following product aimed at:\n\n ##audience## \n\n Product name:\n ##title## \n\n Product description:\n  ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n Title's length must be 30 characters\n\n"],

            ['id' => 3090, 'template_id' => 85, 'key' => "ar-AE", 'value' => "اكتب 10 عناوين مثيرة للاهتمام لإعلان سناب شات للمنتج التالي الذي يهدف إلى:\n\n ##audience## \n\n اسم المنتج:\n ##title## \n\n وصف المنتج:\n  ##description## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n يجب أن يكون طول العنوان 30 حرفًا\n\n"],

            ['id' => 3091, 'template_id' => 85, 'key' => "cmn-CN", 'value' => "为以下产品的 snapchat 广告写 10 个有趣的标题，目的是：\n\n ##audience## \n\n 产品名称：\n ##title## \n\n 产品描述：\n  ##description## \n\n 结果的语气必须是：\n ##tone_language## \n\n 标题长度必须为 30 个字符\n\n"],

            ['id' => 3092, 'template_id' => 85, 'key' => "hr-HR", 'value' => "Napišite 10 zanimljivih naslova za snapchat oglas sljedećeg proizvoda namijenjenog:\n\n ##audience## \n\n Ime proizvoda:\n ##title## \n\n Opis proizvoda:\n  ##description## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n Duljina naslova mora biti 30 znakova\n\n"],

            ['id' => 3093, 'template_id' => 85, 'key' => "cs-CZ", 'value' => "Napište 10 zajímavých titulků pro snapchat reklamu následujícího produktu zaměřeného na:\n\n ##audience## \n\n Jméno výrobku:\n ##title## \n\n Popis výrobku:\n  ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n Délka názvu musí být 30 znaků\n\n"],

            ['id' => 3094, 'template_id' => 85, 'key' => "da-DK", 'value' => "Skriv 10 interessante titler til snapchat-annoncer for følgende produkt rettet mod:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produkt beskrivelse:\n  ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n Titlens længde skal være på 30 tegn\n\n"],

            ['id' => 3095, 'template_id' => 85, 'key' => "nl-NL", 'value' => "Schrijf 10 interessante titels voor Snapchat-advertentie van het volgende product gericht op:\n\n ##audience## \n\n Productnaam:\n ##title## \n\n Product beschrijving:\n  ##description## \n\n Tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n De lengte van de titel moet 30 tekens zijn\n\n"],

            ['id' => 3096, 'template_id' => 85, 'key' => "et-EE", 'value' => "Kirjutage 10 huvitavat pealkirja järgmise toote snapchati reklaamile, mis on suunatud:\n\n ##audience## \n\n Tootenimi:\n ##title## \n\n Tootekirjeldus:\n  ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n Pealkirja pikkus peab olema 30 tähemärki\n\n"],

            ['id' => 3097, 'template_id' => 85, 'key' => "fi-FI", 'value' => "Kirjoita 10 mielenkiintoista otsikkoa seuraavan tuotteen snapchat-mainokseen, joka on tarkoitettu:\n\n ##audience## \n\n Tuotteen nimi:\n ##title## \n\n Tuotteen Kuvaus:\n  ##description## \n\n Tuloksen äänensävyn tulee olla:\n ##tone_language## \n\n Otsikon pituuden tulee olla 30 merkkiä\n\n"],

            ['id' => 3098, 'template_id' => 85, 'key' => "fr-FR", 'value' => "Écrivez 10 titres intéressants pour l'annonce Snapchat du produit suivant destiné à :\n\n ##audience## \n\n Nom du produit :\n ##title## \n\n Description du produit:\n  ##description## \n\n Le ton de voix du résultat doit être :\n ##tone_language## \n\n La longueur du titre doit être de 30 caractères\n\n"],

            ['id' => 3099, 'template_id' => 85, 'key' => "de-DE", 'value' => "Schreiben Sie 10 interessante Titel für die Snapchat-Anzeige des folgenden Produkts mit der Zielgruppe:\n\n ##audience## \n\n Produktname:\n ##title## \n\n Produktbeschreibung:\n  ##description## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n Die Länge des Titels muss 30 Zeichen betragen\n\n"],

            ['id' => 3100, 'template_id' => 85, 'key' => "el-GR", 'value' => "Γράψτε 10 ενδιαφέροντες τίτλους για τη διαφήμιση snapchat του παρακάτω προϊόντος με στόχο:\n\n ##audience## \n\n Ονομασία προϊόντος:\n ##title## \n\n Περιγραφή προϊόντος:\n  ##description## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n Το μήκος του τίτλου πρέπει να είναι 30 χαρακτήρες\n\n"],

            ['id' => 3101, 'template_id' => 85, 'key' => "he-IL", 'value' => "כתוב 10 כותרות מעניינות עבור מודעת Snapchat של המוצר הבא המיועדת ל:\n\n ##audience## \n\n שם מוצר:\n ##title## \n\n תיאור מוצר:\n  ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n אורך הכותרת חייב להיות 30 תווים\n\n"],

            ['id' => 3102, 'template_id' => 85, 'key' => "hi-IN", 'value' => "निम्नलिखित उत्पाद के स्नैपचैट विज्ञापन के लिए 10 दिलचस्प शीर्षक लिखें जिनका उद्देश्य है:\n\n ##audience## \n\n प्रोडक्ट का नाम:\n ##title## \n\n उत्पाद वर्णन:\n  ##description## \n\n परिणाम की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n शीर्षक की लंबाई 30 वर्ण होनी चाहिए\n\n"],

            ['id' => 3103, 'template_id' => 85, 'key' => "hu-HU", 'value' => "Írjon 10 érdekes címet a következő termék snapchat-hirdetéséhez, amelynek célja:\n\n ##audience## \n\n Termék név:\n ##title## \n\n Termékleírás:\n  ##description## \n\n Az eredmény hangnemének a következőnek kell lennie:\n ##tone_language## \n\n A címnek 30 karakterből kell állnia\n\n"],

            ['id' => 3104, 'template_id' => 85, 'key' => "is-IS", 'value' => "Skrifaðu 10 áhugaverða titla fyrir snapchat auglýsingu fyrir eftirfarandi vöru sem miðar að:\n\n ##audience## \n\n Vöru Nafn:\n ##title## \n\n Vörulýsing:\n  ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n Lengd titilsins verður að vera 30 stafir\n\n"],

            ['id' => 3105, 'template_id' => 85, 'key' => "id-ID", 'value' => "Tulis 10 judul menarik untuk iklan snapchat produk berikut yang ditujukan untuk:\n\n ##audience## \n\n Nama Produk:\n ##title## \n\n Deskripsi Produk:\n  ##description## \n\n Nada suara hasil harus:\n ##tone_language## \n\n Panjang judul harus 30 karakter\n\n"],

            ['id' => 3106, 'template_id' => 85, 'key' => "it-IT", 'value' => "Scrivi 10 titoli interessanti per l'annuncio snapchat del seguente prodotto mirato a:\n\n ##audience## \n\n Nome del prodotto:\n ##title## \n\n Descrizione del prodotto:\n  ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n La lunghezza del titolo deve essere di 30 caratteri\n\n"],

            ['id' => 3107, 'template_id' => 85, 'key' => "ja-JP", 'value' => "次の製品のスナップチャット広告の興味深いタイトルを 10 個書いてください:\n\n ##audience## \n\n 商品名：\n ##title## \n\n 製品説明：\n  ##description## \n\n 結果の口調は次のようになります:\n ##tone_language## \n\n タイトルの長さは 30 文字である必要があります\n\n"],

            ['id' => 3108, 'template_id' => 85, 'key' => "ko-KR", 'value' => "다음을 대상으로 하는 스냅챗 광고의 흥미로운 제목 10개를 작성하세요:\n\n ##audience## \n\n 상품명:\n ##title## \n\n 제품 설명:\n  ##description## \n\n 결과의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n 제목 길이는 30자여야 합니다\n\n"],

            ['id' => 3109, 'template_id' => 85, 'key' => "ms-MY", 'value' => "Tulis 10 tajuk menarik untuk iklan snapchat produk berikut bertujuan:\n\n ##audience## \n\n Nama Produk:\n ##title## \n\n Penerangan Produk:\n  ##description## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n Panjang tajuk mestilah 30 aksara\n\n"],

            ['id' => 3110, 'template_id' => 85, 'key' => "nb-NO", 'value' => "Skriv 10 interessante titler for snapchat-annonsen for følgende produkt rettet mot:\n\n ##audience## \n\n Produktnavn:\n ##title## \n\n Produktbeskrivelse:\n  ##description## \n\n Stemmetonen for resultatet må være:\n ##tone_language## \n\n Tittelens lengde må være på 30 tegn\n\n"],

            ['id' => 3111, 'template_id' => 85, 'key' => "pl-PL", 'value' => "Napisz 10 ciekawych tytułów reklamy snapchat następującego produktu skierowanej do:\n\n ##audience## \n\n Nazwa produktu:\n ##title## \n\n Opis produktu:\n  ##description## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n Długość tytułu musi wynosić 30 znaków\n\n"],

            ['id' => 3112, 'template_id' => 85, 'key' => "pt-PT", 'value' => "Escreva 10 títulos interessantes para o anúncio do Snapchat do seguinte produto destinado a:\n\n ##audience## \n\n Nome do Produto:\n ##title## \n\n Descrição do produto:\n  ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n O comprimento do título deve ser de 30 caracteres\n\n"],

            ['id' => 3113, 'template_id' => 85, 'key' => "ru-RU", 'value' => "Напишите 10 интересных заголовков для рекламы в Snapchat следующего продукта, нацеленного на:\n\n ##audience## \n\n Наименование товара:\n ##title## \n\n Описание продукта:\n  ##description## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n Длина заголовка должна быть 30 символов\n\n"],

            ['id' => 3114, 'template_id' => 85, 'key' => "es-ES", 'value' => "Escriba 10 títulos interesantes para el anuncio de Snapchat del siguiente producto dirigido a:\n\n ##audience## \n\n Nombre del producto:\n ##title## \n\n Descripción del Producto:\n  ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n La longitud del título debe ser de 30 caracteres\n\n"],

            ['id' => 3115, 'template_id' => 85, 'key' => "sv-SE", 'value' => "Skriv 10 intressanta titlar för snapchat-annons för följande produkt som syftar till:\n\n ##audience## \n\n Produktnamn:\n ##title## \n\n Produktbeskrivning:\n  ##description## \n\n Tonen i resultatet måste vara:\n ##tone_language## \n\n Titelns längd måste vara 30 tecken\n\n"],

            ['id' => 3116, 'template_id' => 85, 'key' => "tr-TR", 'value' => "Aşağıdaki ürünün Snapchat reklamı için 10 ilginç başlık yazın:\n\n ##audience## \n\n Ürün adı:\n ##title## \n\n Ürün Açıklaması:\n  ##description## \n\n Sonucun ses tonu şöyle olmalıdır:\n ##tone_language## \n\n Başlığın uzunluğu 30 karakter olmalıdır\n\n"],

            ['id' => 3117, 'template_id' => 85, 'key' => "pt-BR", 'value' => "Escreva 10 títulos interessantes para o anúncio do snapchat do seguinte produto destinado a:\n\n ##audience## \n\n Nome do produto:\n ##title## \n\n Descrição do produto:\n  ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n O comprimento do título deve ser de 30 caracteres\n\n"],

            ['id' => 3118, 'template_id' => 85, 'key' => "ro-RO", 'value' => "Scrieți 10 titluri interesante pentru reclame snapchat ale următorului produs destinat:\n\n ##audience## \n\n Numele produsului:\n ##title## \n\n Descriere produs:\n  ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n Lungimea titlului trebuie să fie de 30 de caractere\n\n"],

            ['id' => 3119, 'template_id' => 85, 'key' => "vi-VN", 'value' => "Viết 10 tiêu đề thú vị cho quảng cáo snapchat của sản phẩm sau nhằm mục đích:\n\n ##audience## \n\n Tên sản phẩm:\n ##title## \n\n Mô tả Sản phẩm:\n  ##description## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n Độ dài của tiêu đề phải là 30 ký tự\n\n"],

            ['id' => 3120, 'template_id' => 85, 'key' => "sw-KE", 'value' =>   "Andika mada 10 za kuvutia za tangazo la snapchat la bidhaa ifuatayo inayolenga:\n\n ##audience## \n\n Jina la bidhaa:\n ##title## \n\n Maelezo ya bidhaa:\n  ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n Urefu wa kichwa lazima uwe na vibambo 30\n\n"],

            ['id' => 3121, 'template_id' => 85, 'key' => "sl-SI", 'value' =>   "Napišite 10 zanimivih naslovov za snapchat oglas naslednjega izdelka, ki je namenjen:\n\n ##audience## \n\n Ime izdelka:\n ##title## \n\n Opis izdelka:\n  ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n Dolžina naslova mora biti 30 znakov\n\n"],

            ['id' => 3122, 'template_id' => 85, 'key' => "th-TH", 'value' =>   "เขียน 10 ชื่อที่น่าสนใจสำหรับโฆษณา snapchat ของผลิตภัณฑ์ต่อไปนี้ซึ่งมุ่งเป้าไปที่:\n\n ##audience## \n\n ชื่อผลิตภัณฑ์:\n ##title## \n\n รายละเอียดสินค้า:\n  ##description## \n\n โทนเสียงของผลลัพธ์ต้องเป็น:\n ##tone_language## \n\n ความยาวของชื่อเรื่องต้องมีความยาว 30 อักขระ\n\n"],

            ['id' => 3123, 'template_id' => 85, 'key' => "uk-UA", 'value' =>   "Напишіть 10 цікавих назв для реклами snapchat наступного продукту, спрямованого на:\n\n ##audience## \n\n Назва продукту:\n ##title## \n\n Опис продукту:\n  ##description## \n\n Тон голосу результату повинен бути таким:\n ##tone_language## \n\n Довжина заголовка має бути 30 символів\n\n"],

            ['id' => 3124, 'template_id' => 85, 'key' => "lt-LT", 'value' =>   "Parašykite 10 įdomių pavadinimų šio produkto „snapchat“ reklamai, skirtai:\n\n ##audience## \n\n Produkto pavadinimas:\n ##title## \n\n Prekės aprašymas:\n  ##description## \n\n Rezultato balso tonas turi būti:\n ##tone_language## \n\n Pavadinimo ilgis turi būti 30 simbolių\n\n"],

            ['id' => 3125, 'template_id' => 85, 'key' => "bg-BG", 'value' => "Напишете 10 интересни заглавия за snapchat реклама на следния продукт, насочен към::\n\n ##audience## \n\n Име на продукта:\n ##title## \n\n Описание на продукта:\n  ##description## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n Дължината на заглавието трябва да бъде 30 знака\n\n"],

            ['id' => 3126, 'template_id' => 86, 'key' => "en-US", 'value' => "Write attention grabbing Shopify marketplace product description for:\n\n ##title## \n\nUse following keywords in the product description:\n ##keywords## \n\n"],

            ['id' => 3127, 'template_id' => 86, 'key' => "ar-AE", 'value' =>   "اكتب وصفًا لجذب الانتباه Shopify Marketplace وصف المنتج لـ:\n\n ##title## \n\n استخدم الكلمات الرئيسية التالية في وصف المنتج:\n ##keywords## \n\n"],

            ['id' => 3128, 'template_id' => 86, 'key' => "cmn-CN", 'value' =>   "写下引人注目的 Shopify 市场产品描述：\n\n ##title## \n\n 在产品描述中使用以下关键字：\n ##keywords## \n\n"],

            ['id' => 3129, 'template_id' => 86, 'key' => "hr-HR", 'value' =>   "Napišite opis proizvoda Shopify marketplace koji privlači pažnju za:\n\n ##title## \n\n Koristite sljedeće ključne riječi u opisu proizvoda:\n ##keywords## \n\n"],

            ['id' => 3130, 'template_id' => 86, 'key' => "cs-CZ", 'value' =>   "Napište popis produktu na tržišti Shopify pro:\n\n ##title## \n\n V popisu produktu použijte následující klíčová slova:\n ##keywords## \n\n"],

            ['id' => 3131, 'template_id' => 86, 'key' => "da-DK", 'value' =>   "Skriv opmærksomhedsfangende Shopify markedsplads produktbeskrivelse for:\n\n ##title## \n\n Brug følgende nøgleord i produktbeskrivelsen:\n ##keywords## \n\n"],

            ['id' => 3132, 'template_id' => 86, 'key' => "nl-NL", 'value' =>   "Schrijf de aandacht trekkende Shopify marktplaats productbeschrijving voor:\n\n ##title## \n\n Gebruik de volgende trefwoorden in de productbeschrijving:\n ##keywords## \n\n"],

            ['id' => 3133, 'template_id' => 86, 'key' => "et-EE", 'value' =>   "Kirjutage tähelepanu äratav Shopify turuplatsi tootekirjeldus:\n\n ##title## \n\n Kasutage tootekirjelduses järgmisi märksõnu:\n ##keywords## \n\n"],

            ['id' => 3134, 'template_id' => 86, 'key' => "fi-FI", 'value' =>   "Kirjoita huomiota herättävä Shopify Marketplace -tuotteen kuvaus:\n\n ##title## \n\n Käytä tuotekuvauksessa seuraavia avainsanoja:\n ##keywords## \n\n"],

            ['id' => 3135, 'template_id' => 86, 'key' => "fr-FR", 'value' =>   "Rédigez une description de produit accrocheuse sur le marché Shopify pour :\n\n ##title## \n\n Utilisez les mots clés suivants dans la description du produit :\n ##keywords## \n\n"],

            ['id' => 3136, 'template_id' => 86, 'key' => "de-DE", 'value' =>   "Schreiben Sie eine aufmerksamkeitsstarke Produktbeschreibung für den Shopify-Marktplatz für:\n\n ##title## \n\n Verwenden Sie in der Produktbeschreibung folgende Schlüsselwörter:\n ##keywords## \n\n"],

            ['id' => 3137, 'template_id' => 86, 'key' => "el-GR", 'value' =>   "Γράψτε την περιγραφή προϊόντος της αγοράς Shopify για:\n\n ##title## \n\n Χρησιμοποιήστε τις ακόλουθες λέξεις-κλειδιά στην περιγραφή του προϊόντος:\n ##keywords## \n\n"],

            ['id' => 3138, 'template_id' => 86, 'key' => "he-IL", 'value' =>   "כתוב תיאור המוצר של Shopify Marketplace למשוך תשומת לב עבור:\n\n ##title## \n\n השתמש במילות המפתח הבאות בתיאור המוצר:\n ##keywords## \n\n"],

            ['id' => 3139, 'template_id' => 86, 'key' => "hi-IN", 'value' =>   "ध्यान आकर्षित करने वाले लिखें Shopify मार्केटप्लेस के लिए उत्पाद विवरण:\n\n ##title## \n\n उत्पाद विवरण में निम्नलिखित कीवर्ड का प्रयोग करें:\n ##keywords## \n\n"],

            ['id' => 3140, 'template_id' => 86, 'key' => "hu-HU", 'value' =>   "Írjon figyelemfelkeltő Shopify piactér termékleírást a következőhöz:\n\n ##title## \n\n Használja a következő kulcsszavakat a termékleírásban:\n ##keywords## \n\n"],

            ['id' => 3141, 'template_id' => 86, 'key' => "is-IS", 'value' =>   "Skrifaðu athyglisverða vörulýsingu á Shopify markaðstorginu fyrir:\n\n ##title## \n\n Notaðu eftirfarandi lykilorð í vörulýsingunni:\n ##keywords## \n\n"],

            ['id' => 3142, 'template_id' => 86, 'key' => "id-ID", 'value' =>   "Tulis deskripsi produk pasar Shopify yang menarik perhatian untuk:\n\n ##title## \n\n Gunakan kata kunci berikut dalam deskripsi produk:\n ##keywords## \n\n"],

            ['id' => 3143, 'template_id' => 86, 'key' => "it-IT", 'value' =>   "Scrivi una descrizione del prodotto del marketplace Shopify che attiri l'attenzione per:\n\n ##title## \n\n Usa le seguenti parole chiave nella descrizione del prodotto:\n ##keywords## \n\n"],

            ['id' => 3144, 'template_id' => 86, 'key' => "ja-JP", 'value' =>   "注目を集める Shopify マーケットプレイス製品の説明を次のように書きます:\n\n ##title## \n\n 製品説明には次のキーワードを使用します:\n ##keywords## \n\n"],

            ['id' => 3145, 'template_id' => 86, 'key' => "ko-KR", 'value' =>   "관심을 끄는 Shopify 마켓플레이스 제품 설명 작성:\n\n ##title## \n\n 제품 설명에 다음 키워드를 사용하십시오:\n ##keywords## \n\n"],

            ['id' => 3146, 'template_id' => 86, 'key' => "ms-MY", 'value' =>   "Tulis penerangan produk pasaran Shopify yang menarik perhatian untuk:\n\n ##title## \n\n Gunakan kata kunci berikut dalam penerangan produk:\n ##keywords## \n\n"],

            ['id' => 3147, 'template_id' => 86, 'key' => "nb-NO", 'value' =>   "Skriv oppmerksomhetsfangende Shopify markedsplass produktbeskrivelse for:\n\n ##title## \n\n Bruk følgende nøkkelord i produktbeskrivelsen:\n ##keywords## \n\n"],

            ['id' => 3148, 'template_id' => 86, 'key' => "pl-PL", 'value' =>   "Napisz przyciągający uwagę opis produktu Shopify marketplace dla:\n\n ##title## \n\n Użyj następujących słów kluczowych w opisie produktu:\n ##keywords## \n\n"],

            ['id' => 3149, 'template_id' => 86, 'key' => "pt-PT", 'value' =>   "Escreva uma descrição atraente do produto Shopify marketplace para:\n\n ##title## \n\n Use as seguintes palavras-chave na descrição do produto:\n ##keywords## \n\n"],

            ['id' => 3150, 'template_id' => 86, 'key' => "ru-RU", 'value' =>   "Напишите привлекающее внимание описание продукта торговой площадки Shopify для:\n\n ##title## \n\n В описании товара используйте следующие ключевые слова:\n ##keywords## \n\n"],

            ['id' => 3151, 'template_id' => 86, 'key' => "es-ES", 'value' =>   "Escriba una descripción del producto del mercado de Shopify que llame la atención para:\n\n ##title## \n\n Utilice las siguientes palabras clave en la descripción del producto:\n ##keywords## \n\n"],

            ['id' => 3152, 'template_id' => 86, 'key' => "sv-SE", 'value' =>   "Skriv en uppmärksammad produktbeskrivning för Shopify marknadsplats för:\n\n ##title## \n\nAnvänd följande nyckelord i produktbeskrivningen:\n ##keywords## \n\n"],

            ['id' => 3153, 'template_id' => 86, 'key' => "tr-TR", 'value' =>   "Aşağıdakiler için dikkat çekici Shopify ticaret sitesi ürün açıklamasını yazın:\n\n ##title## \n\n Ürün açıklamasında aşağıdaki anahtar kelimeleri kullanın:\n ##keywords## \n\n"],

            ['id' => 3154, 'template_id' => 86, 'key' => "pt-BR", 'value' =>   "Escreva uma descrição de produto do Shopify Marketplace que chame a atenção:\n\n ##title## \n\n Use as seguintes palavras-chave na descrição do produto:\n ##keywords## \n\n"],

            ['id' => 3155, 'template_id' => 86, 'key' => "ro-RO", 'value' =>   "Scrieți descrierea produsului Shopify marketplace pentru:\n\n ##title## \n\n Utilizați următoarele cuvinte cheie în descrierea produsului:\n ##keywords## \n\n"],

            ['id' => 3156, 'template_id' => 86, 'key' => "vi-VN", 'value' =>   "Viết mô tả sản phẩm thị trường Shopify thu hút sự chú ý cho:\n\n ##title## \n\n Sử dụng các từ khóa sau trong mô tả sản phẩm:\n ##keywords## \n\n"],

            ['id' => 3157, 'template_id' => 86, 'key' => "sw-KE", 'value' =>   "Andika umakini wa kuvutia maelezo ya bidhaa ya soko la Shopify kwa:\n\n ##title## \n\n Tumia maneno muhimu yafuatayo katika maelezo ya bidhaa:\n ##keywords## \n\n"],

            ['id' => 3158, 'template_id' => 86, 'key' => "sl-SI", 'value' =>   "Napišite opis izdelka Shopify marketplace, ki pritegne pozornost za:\n\n ##title## \n\n V opisu izdelka uporabite naslednje ključne besede:\n ##keywords## \n\n"],

            ['id' => 3159, 'template_id' => 86, 'key' => "th-TH", 'value' =>   "เขียนคำอธิบายผลิตภัณฑ์ตลาด Shopify ที่ดึงดูดความสนใจสำหรับ:\n\n ##title## \n\n ใช้คำหลักต่อไปนี้ในรายละเอียดสินค้า:\n ##keywords## \n\n"],

            ['id' => 3160, 'template_id' => 86, 'key' => "uk-UA", 'value' =>   "Напишіть опис продукту Shopify Marketplace, який приверне увагу:\n\n ##title## \n\n Використовуйте наступні ключові слова в описі товару:\n ##keywords## \n\n"],

            ['id' => 3161, 'template_id' => 86, 'key' => "lt-LT", 'value' =>   "Parašykite dėmesį patraukiantį Shopify prekyvietės produkto aprašymą:\n\n ##title## \n\n Produkto aprašyme naudokite šiuos raktinius žodžius:\n ##keywords## \n\n"],

            ['id' => 3162, 'template_id' => 86, 'key' => "bg-BG", 'value' =>   "Напишете грабващо вниманието описание на продукта на Shopify marketplace за:\n\n ##title## \n\n Използвайте следните ключови думи в описанието на продукта:\n ##keywords## \n\n"],

            ['id' => 3163, 'template_id' => 87, 'key' => "en-US", 'value' => "Write a personal bio  which  i can used at  ##description## for myself \n\n  Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 3164, 'template_id' => 87, 'key' => "ar-AE", 'value' => "اكتب سيرة ذاتية شخصية يمكنني استخدامها فيها ##description##  لنفسي \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 3165, 'template_id' => 87, 'key' => "cmn-CN", 'value' => "写一个我可以使用的个人简历 ##description## 为了我自己 \n\n 结果的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 3166, 'template_id' => 87, 'key' => "hr-HR", 'value' => "Napiši osobnu biografiju koju mogu koristiti ##description## Za sebe \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 3167, 'template_id' => 87, 'key' => "cs-CZ", 'value' => "Napište osobní životopis, který mohu použít ##description## pro mě \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 3168, 'template_id' => 87, 'key' => "da-DK", 'value' => "Skriv en personlig biografi, som jeg kan bruge på ##description## for mig selv \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 3169, 'template_id' => 87, 'key' => "nl-NL", 'value' => "Schrijf een persoonlijke bio die ik kan gebruiken ##description## voor mezelf \n\n Tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 3170, 'template_id' => 87, 'key' => "et-EE", 'value' =>   "Kirjutage isiklik biograafia, mida saan kasutada ##description## enda jaoks \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 3171, 'template_id' => 87, 'key' => "fi-FI", 'value' =>   "Kirjoita henkilökohtainen bio, jota voin käyttää ##description## itselleni \n\n Tuloksen äänensävyn tulee olla:\n ##tone_language## \n\n"],

            ['id' => 3172, 'template_id' => 87, 'key' => "fr-FR", 'value' =>   "Écrivez une biographie personnelle que je peux utiliser à ##description## pour moi-même \n\n Le ton de voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 3173, 'template_id' => 87, 'key' => "de-DE", 'value' =>   "Schreiben Sie eine persönliche Biografie, die ich verwenden kann ##description## für mich \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 3174, 'template_id' => 87, 'key' => "el-GR", 'value' =>   "Γράψτε ένα προσωπικό βιογραφικό στο οποίο μπορώ να χρησιμοποιήσω ##description## για τον εαυτό μου \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 3175, 'template_id' => 87, 'key' => "he-IL", 'value' =>   "כתוב ביו אישי שבו אני יכול להשתמש: ##description## בשבילי \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 3176, 'template_id' => 87, 'key' => "hi-IN", 'value' =>   "एक व्यक्तिगत परिचय लिखें जिसका मैं उपयोग कर सकता हूं ##description## अपने आप के लिए \n\n परिणाम की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 3177, 'template_id' => 87, 'key' => "hu-HU", 'value' =>   "Írj személyes életrajzot, amit fel tudok használni ##description## magamnak \n\n Az eredmény hangnemének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 3178, 'template_id' => 87, 'key' => "is-IS", 'value' =>   "Skrifaðu persónulega ævisögu sem ég get notað á ##description## fyrir mig \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 3179, 'template_id' => 87, 'key' => "id-ID", 'value' =>   "Tulis bio pribadi yang bisa saya gunakan ##description## untuk diriku \n\n Nada suara hasil harus:\n ##tone_language## \n\n"],

            ['id' => 3180, 'template_id' => 87, 'key' => "it-IT", 'value' =>   "Scrivi una biografia personale che posso usare su ##description## per me \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 3181, 'template_id' => 87, 'key' => "ja-JP", 'value' =>   "で使用できる個人的な経歴を書きます ##description## 自分のため \n\n 結果の口調は次のようになります:\n ##tone_language## \n\n"],

            ['id' => 3182, 'template_id' => 87, 'key' => "ko-KR", 'value' =>   "내가 사용할 수있는 개인 약력 쓰기: ##description## 나 자신을 위해 \n\n 결과의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 3183, 'template_id' => 87, 'key' => "ms-MY", 'value' =>   "Tulis bio peribadi yang boleh saya gunakan ##description## untuk diri saya sendiri \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 3184, 'template_id' => 87, 'key' => "nb-NO", 'value' =>   "Skriv en personlig biografi som jeg kan bruke på ##description## for meg selv \n\n Stemmetonen for resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 3185, 'template_id' => 87, 'key' => "pl-PL", 'value' =>   "Napisz osobistą biografię, z której będę mógł korzystać ##description## dla siebie \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 3186, 'template_id' => 87, 'key' => "pt-PT", 'value' =>   "Escreva uma biografia pessoal que eu possa usar em ##description## para mim \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 3187, 'template_id' => 87, 'key' => "ru-RU", 'value' =>   "Напишите личную биографию, которую я могу использовать в ##description## для меня \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 3188, 'template_id' => 87, 'key' => "es-ES", 'value' =>   "Escribe una biografía personal que pueda usar en ##description## para mí \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 3189, 'template_id' => 87, 'key' => "sv-SE", 'value' =>   "Skriv en personlig bio som jag kan använda på ##description## för mig själv \n\n Tonen i resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 3190, 'template_id' => 87, 'key' => "tr-TR", 'value' =>   "Kullanabileceğim kişisel bir biyografi yaz ##description## kendim için \n\n Sonucun ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 3191, 'template_id' => 87, 'key' => "pt-BR", 'value' =>   "Escreva uma biografia pessoal que eu possa usar em ##description## para mim mesmo \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 3192, 'template_id' => 87, 'key' => "ro-RO", 'value' =>   "Scrie o biografie personală pe care să o pot folosi ##description## pentru mine \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 3193, 'template_id' => 87, 'key' => "vi-VN", 'value' =>   "Viết tiểu sử cá nhân mà tôi có thể sử dụng tại ##description## cho bản thân mình \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 3194, 'template_id' => 87, 'key' => "sw-KE", 'value' =>   "Andika wasifu wa kibinafsi ambao ninaweza kutumia ##description## kwa ajili yangu mwenyewe \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 3195, 'template_id' => 87, 'key' => "sl-SI", 'value' =>   "Napišite osebni življenjepis, ki ga lahko uporabim ##description## zame \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 3196, 'template_id' => 87, 'key' => "th-TH", 'value' =>   "เขียนประวัติส่วนตัวที่ฉันสามารถใช้ได้ ##description## สำหรับตัวฉันเอง \n\n โทนเสียงของผลลัพธ์ต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 3197, 'template_id' => 87, 'key' => "uk-UA", 'value' =>   "Напишіть особисту біографію, яку я можу використати ##description## для мене \n\n Тон голосу результату повинен бути таким:\n ##tone_language## \n\n"],

            ['id' => 3198, 'template_id' => 87, 'key' => "lt-LT", 'value' =>   "Parašykite asmeninę biografiją, kurią galėčiau panaudoti ##description## sau pačiam \n\n Rezultato balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 3199, 'template_id' => 87, 'key' => "bg-BG", 'value' =>   "Напишете лична биография, която мога да използвам ##description##  за мен \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 3200, 'template_id' => 88, 'key' => "en-US", 'value' => "Grab attention with catchy captions for this Pinterest post:\n\n ##description## \n\nTone of voice of the captions must be:\n ##tone_language## \n\n"],

            ['id' => 3201, 'template_id' => 88, 'key' => "ar-AE", 'value' => "اجذب الانتباه من خلال التسميات التوضيحية الجذابة لمشاركة Pinterest هذه:\n\n ##description## \n\nيجب أن تكون نبرة صوت التعليقات:\n ##tone_language## \n\n"],

            ['id' => 3202, 'template_id' => 88, 'key' => "cmn-CN", 'value' => "用醒目的标题吸引注意力:\n\n ##description## \n\n字幕的语气必须是:\n ##tone_language## \n\n"],

            ['id' => 3203, 'template_id' => 88, 'key' => "hr-HR", 'value' => "Privucite pozornost privlačnim opisima za ovu objavu na Pinterestu:\n\n ##description## \n\nTon glasa titlova mora biti:\n ##tone_language## \n\n"],

            ['id' => 3204, 'template_id' => 88, 'key' => "cs-CZ", 'value' => "Upoutejte pozornost chytlavými titulky k tomuto příspěvku na Pinterestu:\n\n ##description## \n\nTón hlasu titulků musí být:\n ##tone_language## \n\n"],

            ['id' => 3205, 'template_id' => 88, 'key' => "da-DK", 'value' => "Fang opmærksomhed med fængende billedtekster til dette Pinterest-indlæg:\n\n ##description## \n\nTone of voice af billedteksterne skal være:\n ##tone_language## \n\n"],

            ['id' => 3206, 'template_id' => 88, 'key' => "nl-NL", 'value' => "Trek de aandacht met pakkende bijschriften voor dit Pinterest-bericht:\n\n ##description## \n\nDe tone-of-voice van de onderschriften moet zijn:\n ##tone_language## \n\n"],

            ['id' => 3207, 'template_id' => 88, 'key' => "et-EE", 'value' => "Pöörake tähelepanu selle Pinteresti postituse meeldejäävate pealkirjadega:\n\n ##description## \n\nPealkirjade hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 3208, 'template_id' => 88, 'key' => "fi-FI", 'value' => "Herätä huomiota tämän Pinterest-julkaisun tarttuvilla kuvateksteillä:\n\n ##description## \n\nTekstityksen äänensävyn on oltava:\n ##tone_language## \n\n"],

            ['id' => 3209, 'template_id' => 88, 'key' => "fr-FR", 'value' => "Attirez l'attention avec des légendes accrocheuses pour ce post Pinterest:\n\n ##description## \n\nLe ton de la voix des sous-titres doit être:\n ##tone_language## \n\n"],

            ['id' => 3210, 'template_id' => 88, 'key' => "de-DE", 'value' => "Machen Sie mit einprägsamen Bildunterschriften für diesen Pinterest-Beitrag auf sich aufmerksam:\n\n ##description## \n\nDer Tonfall der Untertitel muss sein:\n ##tone_language## \n\n"],

            ['id' => 3211, 'template_id' => 88, 'key' => "el-GR", 'value' => "Τραβήξτε την προσοχή με συναρπαστικές λεζάντες για αυτήν την ανάρτηση στο Pinterest:\n\n ##description## \n\nΟ τόνος της φωνής των λεζάντων πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 3212, 'template_id' => 88, 'key' => "he-IL", 'value' => "למשוך תשומת לב עם כיתובים קליטים לפוסט הזה בפינטרסט:\n\n ##description## \n\nטון הדיבור של הכתוביות חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 3213, 'template_id' => 88, 'key' => "hi-IN", 'value' => "इस Pinterest पोस्ट के लिए आकर्षक कैप्शन के साथ ध्यान आकर्षित करें:\n\n ##description## \n\nकैप्शन की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 3214, 'template_id' => 88, 'key' => "hu-HU", 'value' => "Vedd fel a figyelmet fülbemászó feliratokkal ehhez a Pinterest-bejegyzéshez:\n\n ##description## \n\nA feliratok hangszínének ilyennek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 3215, 'template_id' => 88, 'key' => "is-IS", 'value' => "Gríptu athygli með grípandi myndatexta fyrir þessa Pinterest færslu:\n\n ##description## \n\nRöddtónn skjátextanna verður að vera:\n ##tone_language## \n\n"],

            ['id' => 3216, 'template_id' => 88, 'key' => "id-ID", 'value' => "Tarik perhatian dengan teks yang menarik untuk postingan Pinterest ini:\n\n ##description## \n\nNada suara teks harus:\n ##tone_language## \n\n"],

            ['id' => 3217, 'template_id' => 88, 'key' => "it-IT", 'value' => "Attira l'attenzione con didascalie accattivanti per questo post di Pinterest:\n\n ##description## \n\nIl tono di voce delle didascalie deve essere:\n ##tone_language## \n\n"],

            ['id' => 3218, 'template_id' => 88, 'key' => "ja-JP", 'value' => "この Pinterest の投稿にキャッチーなキャプションを付けて注目を集めましょう:\n\n ##description## \n\nキャプションの声のトーンは次のとおりです。:\n ##tone_language## \n\n"],

            ['id' => 3219, 'template_id' => 88, 'key' => "ko-KR", 'value' => "이 Pinterest 게시물에 대한 눈길을 끄는 캡션으로 관심 끌기:\n\n ##description## \n\n자막의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 3220, 'template_id' => 88, 'key' => "ms-MY", 'value' => "Tarik perhatian dengan kapsyen menarik untuk siaran Pinterest ini:\n\n ##description## \n\nNada suara kapsyen mestilah:\n ##tone_language## \n\n"],

            ['id' => 3221, 'template_id' => 88, 'key' => "nb-NO", 'value' => "Fang oppmerksomhet med fengende bildetekster for dette Pinterest-innlegget:\n\n ##description## \n\nTonefallet til bildetekstene må være:\n ##tone_language## \n\n"],

            ['id' => 3222, 'template_id' => 88, 'key' => "pl-PL", 'value' => "Przyciągnij uwagę chwytliwymi napisami do tego posta na Pintereście:\n\n ##description## \n\nTon głosu napisów musi być:\n ##tone_language## \n\n"],

            ['id' => 3223, 'template_id' => 88, 'key' => "pt-PT", 'value' => "Chame a atenção com legendas cativantes para esta postagem do Pinterest:\n\n ##description## \n\nO tom de voz das legendas deve ser:\n ##tone_language## \n\n"],

            ['id' => 3224, 'template_id' => 88, 'key' => "ru-RU", 'value' => "Привлеките внимание броскими подписями к этому посту в Pinterest:\n\n ##description## \n\nТон голоса титров должен быть:\n ##tone_language## \n\n"],

            ['id' => 3225, 'template_id' => 88, 'key' => "es-ES", 'value' => "Llame la atención con leyendas pegadizas para esta publicación de Pinterest:\n\n ##description## \n\nEl tono de voz de los subtítulos debe ser:\n ##tone_language## \n\n"],

            ['id' => 3226, 'template_id' => 88, 'key' => "sv-SE", 'value' => "Fånga uppmärksamheten med catchy bildtexter för detta Pinterest-inlägg:\n\n ##description## \n\nTonen i rösten för bildtexterna måste vara:\n ##tone_language## \n\n"],

            ['id' => 3227, 'template_id' => 88, 'key' => "tr-TR", 'value' => "Bu Pinterest gönderisi için akılda kalıcı altyazılarla dikkat çekin:\n\n ##description## \n\nAltyazıların ses tonu:\n ##tone_language## \n\n"],

            ['id' => 3228, 'template_id' => 88, 'key' => "pt-BR", 'value' => "Grab attention with catchy captions for this Pinterest post:\n\n ##description## \n\nTone of voice of the captions must be:\n ##tone_language## \n\n"],

            ['id' => 3229, 'template_id' => 88, 'key' => "ro-RO", 'value' => "Atrageți atenția cu subtitrări captivante pentru această postare pe Pinterest:\n\n ##description## \n\nTonul vocii subtitrărilor trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 3230, 'template_id' => 88, 'key' => "vi-VN", 'value' => "Thu hút sự chú ý với chú thích hấp dẫn cho bài đăng trên Pinterest này:\n\n ##description## \n\nGiọng điệu của phụ đề phải được:\n ##tone_language## \n\n"],

            ['id' => 3231, 'template_id' => 88, 'key' => "sw-KE", 'value' => "Chukua tahadhari kwa manukuu ya kuvutia ya chapisho hili la Pinterest:\n\n ##description## \n\nToni ya sauti ya manukuu lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 3232, 'template_id' => 88, 'key' => "sl-SI", 'value' => "Pritegnite pozornost s privlačnimi napisi za to objavo na Pinterestu:\n\n ##description## \n\nTon glasu napisov mora biti:\n ##tone_language## \n\n"],

            ['id' => 3233, 'template_id' => 88, 'key' => "th-TH", 'value' => "ดึงดูดความสนใจด้วยคำบรรยายที่จับใจสำหรับโพสต์ Pinterest นี้:\n\n ##description## \n\nน้ำเสียงของคำบรรยายต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 3234, 'template_id' => 88, 'key' => "uk-UA", 'value' => "Привертайте увагу привабливими підписами до цієї публікації на Pinterest:\n\n ##description## \n\nТон голосу титрів повинен бути:\n ##tone_language## \n\n"],

            ['id' => 3235, 'template_id' => 88, 'key' => "lt-LT", 'value' => "Patraukite dėmesį patraukliais šio Pinterest įrašo antraštėmis:\n\n ##description## \n\nSubtitrų balso tonas turi būti toks:\n ##tone_language## \n\n"],

            ['id' => 3236, 'template_id' => 88, 'key' => "bg-BG", 'value' => "Грабнете вниманието със закачливи надписи за тази публикация в Pinterest:\n\n ##description## \n\nТонът на гласа на надписите трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 3237, 'template_id' => 89, 'key' => "en-US", 'value' => "Write 10 interesting titles for Pinterest post  of the following:\n  ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n Title's length must be 30 characters\n\n"],

            ['id' => 3238, 'template_id' => 89, 'key' => "ar-AE", 'value' => "اكتب 10 عناوين مثيرة للاهتمام لنشر Pinterest مما يلي:\n  ##description## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n يجب أن يكون طول العنوان 30 حرفًا\n\n"],

            ['id' => 3239, 'template_id' => 89, 'key' => "cmn-CN", 'value' => "为以下 Pinterest 帖子写 10 个有趣的标题:\n  ##description## \n\n 结果的语气必须是:\n ##tone_language## \n\n 标题长度必须为 30 个字符\n\n"],

            ['id' => 3240, 'template_id' => 89, 'key' => "hr-HR", 'value' => "Napišite 10 zanimivih naslovov za objavo na Pinterestu od naslednjih:\n  ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n Dolžina naslova mora biti 30 znakov\n\n"],

            ['id' => 3241, 'template_id' => 89, 'key' => "cs-CZ", 'value' => "Napište 10 zajímavých titulů pro následující příspěvek na Pinterestu:\n  ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n Délka názvu musí být 30 znaků\n\n"],

            ['id' => 3242, 'template_id' => 89, 'key' => "da-DK", 'value' => "Skriv 10 interessante titler til Pinterest-indlæg af følgende:\n  ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n Titlens længde skal være på 30 tegn\n\n"],

            ['id' => 3243, 'template_id' => 89, 'key' => "nl-NL", 'value' => "Schrijf 10 interessante titels voor Pinterest post van het volgende:\n  ##description## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n De lengte van de titel moet 30 tekens zijn\n\n"],

            ['id' => 3244, 'template_id' => 89, 'key' => "et-EE", 'value' => "Kirjutage järgmise Pinteresti postituse jaoks 10 huvitavat pealkirja:\n  ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n Pealkirja pikkus peab olema 30 tähemärki\n\n"],

            ['id' => 3245, 'template_id' => 89, 'key' => "fi-FI", 'value' => "Kirjoita 10 mielenkiintoista otsikkoa seuraavista Pinterest-julkaisuista:\n  ##description## \n\n Äänesävyn tuloksen on oltava:\n ##tone_language## \n\n Otsikon pituuden tulee olla 30 merkkiä\n\n"],

            ['id' => 3246, 'template_id' => 89, 'key' => "fr-FR", 'value' => "Écrivez 10 titres intéressants pour la publication Pinterest des éléments suivants:\n  ##description## \n\n Le ton de la voix du résultat doit être:\n ##tone_language## \n\n La longueur du titre doit être de 30 caractères\n\n"],

            ['id' => 3247, 'template_id' => 89, 'key' => "de-DE", 'value' => "Schreiben Sie 10 interessante Titel für den folgenden Pinterest-Beitrag:\n  ##description## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n Die Länge des Titels muss 30 Zeichen betragen\n\n"],

            ['id' => 3248, 'template_id' => 89, 'key' => "el-GR", 'value' => "Γράψε 10 ενδιαφέροντες τίτλους για την ανάρτηση στο Pinterest των παρακάτω:\n  ##description## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n Το μήκος του τίτλου πρέπει να είναι 30 χαρακτήρες\n\n"],

            ['id' => 3249, 'template_id' => 89, 'key' => "he-IL", 'value' => "כתוב 10 כותרות מעניינות לפוסט Pinterest של הבאים:\n  ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n אורך הכותרת חייב להיות 30 תווים\n\n"],

            ['id' => 3250, 'template_id' => 89, 'key' => "hi-IN", 'value' => "निम्नलिखित में से Pinterest पोस्ट के लिए 10 रोचक शीर्षक लिखें:\n  ##description## \n\n परिणाम का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n शीर्षक की लंबाई 30 वर्ण होनी चाहिए\n\n"],

            ['id' => 3251, 'template_id' => 89, 'key' => "hu-HU", 'value' => "Írj 10 érdekes címet a következő Pinterest-bejegyzéshez:\n  ##description## \n\n Az eredmény hangnemének kell lennie:\n ##tone_language## \n\n A címnek 30 karakterből kell állnia\n\n"],

            ['id' => 3252, 'template_id' => 89, 'key' => "is-IS", 'value' => "Skrifaðu 10 áhugaverða titla fyrir Pinterest færslu af eftirfarandi:\n  ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n Lengd titilsins verður að vera 30 stafir\n\n"],

            ['id' => 3253, 'template_id' => 89, 'key' => "id-ID", 'value' => "Tulis 10 judul menarik untuk postingan Pinterest berikut ini:\n  ##description## \n\n Nada suara hasilnya harus:\n ##tone_language## \n\n Panjang judul harus 30 karakter\n\n"],

            ['id' => 3254, 'template_id' => 89, 'key' => "it-IT", 'value' => "Scrivi 10 titoli interessanti per il post Pinterest di quanto segue:\n  ##description## \n\n Tono di voce del risultato deve essere:\n ##tone_language## \n\n La lunghezza del titolo deve essere di 30 caratteri\n\n"],

            ['id' => 3255, 'template_id' => 89, 'key' => "ja-JP", 'value' => "Pinterest の投稿に次の興味深いタイトルを 10 件書いてください:\n  ##description## \n\n 結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n タイトルの長さは 30 文字である必要があります\n\n"],

            ['id' => 3256, 'template_id' => 89, 'key' => "ko-KR", 'value' => "Pinterest 게시물에 다음과 같은 흥미로운 제목 10개를 작성하세요.:\n  ##description## \n\n 결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n 제목 길이는 30자여야 합니다.\n\n"],

            ['id' => 3257, 'template_id' => 89, 'key' => "ms-MY", 'value' => "Tulis 10 tajuk menarik untuk siaran Pinterest berikut:\n  ##description## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n Panjang tajuk mestilah 30 aksara\n\n"],

            ['id' => 3258, 'template_id' => 89, 'key' => "nb-NO", 'value' => "Skriv 10 interessante titler for Pinterest-innlegg av følgende:\n  ##description## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n Tittelens lengde må være på 30 tegn\n\n"],

            ['id' => 3259, 'template_id' => 89, 'key' => "pl-PL", 'value' => "Napisz 10 interesujących tytułów dla posta na Pintereście z poniższych:\n  ##description## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n Długość tytułu musi wynosić 30 znaków\n\n"],

            ['id' => 3260, 'template_id' => 89, 'key' => "pt-PT", 'value' => "Escreva 10 títulos interessantes para a postagem no Pinterest do seguinte:\n  ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n O comprimento do título deve ser de 30 caracteres\n\n"],

            ['id' => 3261, 'template_id' => 89, 'key' => "ru-RU", 'value' => "Напишите 10 интересных заголовков для публикации в Pinterest из следующих:\n  ##description## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n Длина заголовка должна быть 30 символов.\n\n"],

            ['id' => 3262, 'template_id' => 89, 'key' => "es-ES", 'value' => "Escriba 10 títulos interesantes para la publicación de Pinterest de lo siguiente:\n  ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n La longitud del título debe ser de 30 caracteres.\n\n"],

            ['id' => 3263, 'template_id' => 89, 'key' => "sv-SE", 'value' => "Skriv 10 intressanta titlar för Pinterest-inlägg av följande:\n  ##description## \n\n Tonen i rösten för resultatet måste vara:\n ##tone_language## \n\n Titelns längd måste vara 30 tecken\n\n"],

            ['id' => 3264, 'template_id' => 89, 'key' => "tr-TR", 'value' => "Aşağıdakilerden Pinterest gönderisi için 10 ilginç başlık yazın:\n  ##description## \n\n Sonucun ses tonu şöyle olmalıdır:\n ##tone_language## \n\n Başlığın uzunluğu 30 karakter olmalıdır\n\n"],

            ['id' => 3265, 'template_id' => 89, 'key' => "pt-BR", 'value' => "Escreva 10 títulos interessantes para a postagem no Pinterest do seguinte:\n  ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n O comprimento do título deve ser de 30 caracteres\n\n"],

            ['id' => 3266, 'template_id' => 89, 'key' => "ro-RO", 'value' => "Scrie 10 titluri interesante pentru postarea Pinterest din următoarele:\n  ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n Lungimea titlului trebuie să fie de 30 de caractere\n\n"],

            ['id' => 3267, 'template_id' => 89, 'key' => "vi-VN", 'value' => "Viết 10 tiêu đề thú vị cho bài đăng trên Pinterest sau đây:\n  ##description## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n Độ dài của tiêu đề phải là 30 ký tự\n\n"],

            ['id' => 3268, 'template_id' => 89, 'key' => "sw-KE", 'value' => "Andika majina 10 ya kuvutia kwa chapisho la Pinterest la yafuatayo:\n  ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n Urefu wa kichwa lazima uwe na vibambo 30\n\n"],

            ['id' => 3269, 'template_id' => 89, 'key' => "sl-SI", 'value' => "Napišite 10 zanimivih naslovov za objavo na Pinterestu od naslednjih:\n  ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n Dolžina naslova mora biti 30 znakov\n\n"],

            ['id' => 3270, 'template_id' => 89, 'key' => "th-TH", 'value' => "เขียน 10 ชื่อเรื่องที่น่าสนใจสำหรับโพสต์ Pinterest ต่อไปนี้:\n  ##description## \n\n น้ำเสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n ความยาวของชื่อเรื่องต้องมีความยาว 30 อักขระ\n\n"],

            ['id' => 3271, 'template_id' => 89, 'key' => "uk-UA", 'value' => "Напишіть 10 цікавих заголовків для публікації Pinterest з наступного:\n  ##description## \n\n Тон голосу результату повинен бути:\n ##tone_language## \n\n Довжина заголовка має бути 30 символів\n\n"],

            ['id' => 3272, 'template_id' => 89, 'key' => "lt-LT", 'value' => "Parašykite 10 įdomių pavadinimų toliau pateiktiems Pinterest įrašams:\n  ##description## \n\n Turi būti rezultato balso tonas:\n ##tone_language## \n\n Pavadinimo ilgis turi būti 30 simbolių\n\n"],

            ['id' => 3273, 'template_id' => 89, 'key' => "bg-BG", 'value' => "Напишете 10 интересни заглавия за публикация в Pinterest от следните:\n  ##description## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n Дължината на заглавието трябва да бъде 30 знака\n\n"],

            ['id' => 3274, 'template_id' => 90, 'key' => "en-US", 'value' => "Write creative Pinterest bio Using following keywords in the bio description:\n ##keywords##  \n\n  Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 3275, 'template_id' => 90, 'key' => "ar-AE", 'value' => "اكتب السيرة الذاتية الإبداعية Pinterest باستخدام الكلمات الرئيسية التالية في وصف السيرة الذاتية:\n ##keywords##  \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 3276, 'template_id' => 90, 'key' => "cmn-CN", 'value' => "写有创意的 Pinterest bio 在 bio 描述中使用以下关键字:\n ##keywords##  \n\n 结果的语气必须是:\n ##tone_language## \n\n"],

            ['id' => 3277, 'template_id' => 90, 'key' => "hr-HR", 'value' => "Napišite ustvarjalni življenjepis na Pinterestu. V opisu biografije uporabite naslednje ključne besede:\n ##keywords##  \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 3278, 'template_id' => 90, 'key' => "cs-CZ", 'value' => "Napište kreativní životopis na Pinterest Pomocí následujících klíčových slov v popisu životopisu:\n ##keywords##  \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 3279, 'template_id' => 90, 'key' => "da-DK", 'value' => "Skriv kreativ Pinterest bio Brug følgende nøgleord i biobeskrivelsen:\n ##keywords##  \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 3280, 'template_id' => 90, 'key' => "nl-NL", 'value' => "Schrijf creatieve Pinterest-bio Gebruik de volgende trefwoorden in de biobeschrijving:\n ##keywords##  \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 3281, 'template_id' => 90, 'key' => "et-EE", 'value' => "Kirjutage loominguline Pinteresti biograafia Kasutades biokirjelduses järgmisi märksõnu:\n ##keywords##  \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 3282, 'template_id' => 90, 'key' => "fi-FI", 'value' => "Kirjoita luova Pinterest bio käyttämällä seuraavia avainsanoja bion kuvauksessa:\n ##keywords##  \n\n Äänesävyn tuloksen on oltava:\n ##tone_language## \n\n"],

            ['id' => 3283, 'template_id' => 90, 'key' => "fr-FR", 'value' => "Rédigez une bio Pinterest créative en utilisant les mots-clés suivants dans la description de la bio:\n ##keywords##  \n\n Le ton de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 3284, 'template_id' => 90, 'key' => "de-DE", 'value' => "Schreiben Sie eine kreative Pinterest-Biografie und verwenden Sie die folgenden Schlüsselwörter in der Biografiebeschreibung:\n ##keywords##  \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 3285, 'template_id' => 90, 'key' => "el-GR", 'value' => "Γράψτε δημιουργικό βιογραφικό Pinterest Χρησιμοποιώντας τις ακόλουθες λέξεις-κλειδιά στην περιγραφή του βιογραφικού:\n ##keywords##  \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 3286, 'template_id' => 90, 'key' => "he-IL", 'value' => "כתוב ביוגרפיה יצירתית של Pinterest באמצעות מילות מפתח הבאות בתיאור הביוגרפיה:\n ##keywords##  \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 3287, 'template_id' => 90, 'key' => "hi-IN", 'value' => "बायो विवरण में निम्नलिखित कीवर्ड्स का उपयोग करके रचनात्मक Pinterest बायो लिखें:\n ##keywords##  \n\n परिणाम का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 3288, 'template_id' => 90, 'key' => "hu-HU", 'value' => "Írjon kreatív Pinterest életrajzot Az alábbi kulcsszavak használatával az életrajz leírásában:\n ##keywords##  \n\n Az eredmény hangnemének kell lennie:\n ##tone_language## \n\n"],

            ['id' => 3289, 'template_id' => 90, 'key' => "is-IS", 'value' => "Skrifaðu skapandi líffræði á Pinterest með því að nota eftirfarandi lykilorð í líflýsingunni:\n ##keywords##  \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 3290, 'template_id' => 90, 'key' => "id-ID", 'value' => "Tulis bio Pinterest yang kreatif Menggunakan kata kunci berikut dalam deskripsi bio:\n ##keywords##  \n\n Nada suara hasilnya harus:\n ##tone_language## \n\n"],

            ['id' => 3291, 'template_id' => 90, 'key' => "it-IT", 'value' => "Scrivi una biografia Pinterest creativa utilizzando le seguenti parole chiave nella descrizione della biografia:\n ##keywords##  \n\n Tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 3292, 'template_id' => 90, 'key' => "ja-JP", 'value' => "Pinterest のクリエイティブな自己紹介を作成します。自己紹介の説明に次のキーワードを使用します。:\n ##keywords##  \n\n 結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n"],

            ['id' => 3293, 'template_id' => 90, 'key' => "ko-KR", 'value' => "창의적인 Pinterest 약력 작성 약력 설명에 다음 키워드 사용:\n ##keywords##  \n\n 결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 3294, 'template_id' => 90, 'key' => "ms-MY", 'value' => "Tulis bio Pinterest kreatif Menggunakan kata kunci berikut dalam penerangan bio:\n ##keywords##  \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 3295, 'template_id' => 90, 'key' => "nb-NO", 'value' => "Skriv kreativ Pinterest-biografi ved å bruke følgende nøkkelord i biobeskrivelsen:\n ##keywords##  \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 3296, 'template_id' => 90, 'key' => "pl-PL", 'value' => "Napisz kreatywną biografię na Pintereście, używając następujących słów kluczowych w opisie biografii:\n ##keywords##  \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 3297, 'template_id' => 90, 'key' => "pt-PT", 'value' => "Escreva uma biografia criativa no Pinterest usando as seguintes palavras-chave na descrição da biografia:\n ##keywords##  \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 3298, 'template_id' => 90, 'key' => "ru-RU", 'value' => "Напишите креативную биографию Pinterest, используя следующие ключевые слова в описании биографии:\n ##keywords##  \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 3299, 'template_id' => 90, 'key' => "es-ES", 'value' => "Escriba una biografía creativa de Pinterest usando las siguientes palabras clave en la descripción de la biografía:\n ##keywords##  \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 3300, 'template_id' => 90, 'key' => "sv-SE", 'value' => "Skriv kreativ Pinterest-bio Använd följande nyckelord i biobeskrivningen:\n ##keywords##  \n\n Tonen i rösten för resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 3301, 'template_id' => 90, 'key' => "tr-TR", 'value' => "Biyo açıklamasında aşağıdaki anahtar kelimeleri kullanarak yaratıcı Pinterest biyografisi yazın:\n ##keywords##  \n\n Sonucun ses tonu şöyle olmalıdır::\n ##tone_language## \n\n"],

            ['id' => 3302, 'template_id' => 90, 'key' => "pt-BR", 'value' => "Escreva uma biografia criativa no Pinterest usando as seguintes palavras-chave na descrição da biografia:\n ##keywords##  \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 3303, 'template_id' => 90, 'key' => "ro-RO", 'value' => "Scrieți o biografie creativă pentru Pinterest Folosind următoarele cuvinte cheie în descrierea biografiei:\n ##keywords##  \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 3304, 'template_id' => 90, 'key' => "vi-VN", 'value' => "Viết tiểu sử Pinterest sáng tạo Sử dụng các từ khóa sau trong mô tả sinh học:\n ##keywords##  \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 3305, 'template_id' => 90, 'key' => "sw-KE", 'value' => "Andika wasifu wa ubunifu wa Pinterest Kwa kutumia maneno muhimu yafuatayo katika maelezo ya wasifu:\n ##keywords##  \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 3306, 'template_id' => 90, 'key' => "sl-SI", 'value' => "Napišite ustvarjalni življenjepis na Pinterestu. V opisu biografije uporabite naslednje ključne besede:\n ##keywords##  \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 3307, 'template_id' => 90, 'key' => "th-TH", 'value' => "เขียนชีวประวัติของ Pinterest อย่างสร้างสรรค์ โดยใช้คำหลักต่อไปนี้ในคำอธิบายชีวประวัติ:\n ##keywords##  \n\n น้ำเสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 3308, 'template_id' => 90, 'key' => "uk-UA", 'value' => "Напишіть креативну біографію Pinterest, використовуючи наступні ключові слова в описі біографії:\n ##keywords##  \n\n Тон голосу результату повинен бути:\n ##tone_language## \n\n"],

            ['id' => 3309, 'template_id' => 90, 'key' => "lt-LT", 'value' => "Parašykite kūrybingą Pinterest biografiją Naudodami šiuos raktinius žodžius biografijos aprašyme:\n ##keywords##  \n\n Turi būti rezultato balso tonas:\n ##tone_language## \n\n"],

            ['id' => 3310, 'template_id' => 90, 'key' => "bg-BG", 'value' => "Напишете творческа биография в Pinterest, като използвате следните ключови думи в описанието на биографията:\n ##keywords##  \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 3311, 'template_id' => 91, 'key' => "en-US", 'value' =>  "Write creative replay for the following  review :\n\n  ##description## \n\n Tone of voice of the result must be:\n  ##tone_language##  \n\n"],

            ['id' => 3312, 'template_id' => 91, 'key' => "ar-AE", 'value' =>  "اكتب إعادة إبداعية للمراجعة التالية :\n\n  ##description## \n\n يجب أن تكون نبرة صوت النتيجة:\n  ##tone_language##  \n\n"],

            ['id' => 3313, 'template_id' => 91, 'key' => "cmn-CN", 'value' =>  "为以下评论撰写创意重播 :\n\n  ##description## \n\n 结果的语气必须是:\n  ##tone_language##  \n\n"],

            ['id' => 3314, 'template_id' => 91, 'key' => "hr-HR", 'value' =>  "Napišite kreativno ponovitev za naslednji pregled :\n\n  ##description## \n\n Ton glasu rezultata mora biti:\n  ##tone_language##  \n\n"],

            ['id' => 3315, 'template_id' => 91, 'key' => "cs-CZ", 'value' =>  "Napište kreativní záznam pro následující recenzi :\n\n  ##description## \n\n Tón hlasu výsledku musí být:\n  ##tone_language##  \n\n"],

            ['id' => 3316, 'template_id' => 91, 'key' => "da-DK", 'value' =>  "Skriv kreativ gentagelse til den følgende anmeldelse :\n\n  ##description## \n\n Tone of voice af resultatet skal være:\n  ##tone_language##  \n\n"],

            ['id' => 3317, 'template_id' => 91, 'key' => "nl-NL", 'value' =>  "Schrijf een creatieve herhaling voor de volgende recensie :\n\n  ##description## \n\n De tone of voice van het resultaat moet zijn:\n  ##tone_language##  \n\n"],

            ['id' => 3318, 'template_id' => 91, 'key' => "et-EE", 'value' =>  "Kirjutage järgmise ülevaate jaoks loominguline kordus :\n\n  ##description## \n\n Tulemuse hääletoon peab olema:\n  ##tone_language##  \n\n"],

            ['id' => 3319, 'template_id' => 91, 'key' => "fi-FI", 'value' =>  "Kirjoita luova uusinta seuraavaa arvostelua varten :\n\n  ##description## \n\n Äänesävyn tuloksen on oltava:\n  ##tone_language##  \n\n"],

            ['id' => 3320, 'template_id' => 91, 'key' => "fr-FR", 'value' =>  "Rédiger une rediffusion créative pour l'examen suivant :\n\n  ##description## \n\n Le ton de la voix du résultat doit être:\n  ##tone_language##  \n\n"],

            ['id' => 3321, 'template_id' => 91, 'key' => "de-DE", 'value' =>  "Schreiben Sie eine kreative Wiederholung für die folgende Rezension :\n\n  ##description## \n\n Der Tonfall des Ergebnisses muss sein:\n  ##tone_language##  \n\n"],

            ['id' => 3322, 'template_id' => 91, 'key' => "el-GR", 'value' =>  "Γράψτε επανάληψη δημιουργικού για την ακόλουθη κριτική :\n\n  ##description## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n  ##tone_language##  \n\n"],

            ['id' => 3323, 'template_id' => 91, 'key' => "he-IL", 'value' =>  "כתוב שידור חוזר יצירתי עבור הסקירה הבאה :\n\n  ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n  ##tone_language##  \n\n"],

            ['id' => 3324, 'template_id' => 91, 'key' => "hi-IN", 'value' =>  "निम्नलिखित समीक्षा के लिए क्रिएटिव रिप्ले लिखें :\n\n  ##description## \n\n परिणाम का स्वर स्वर होना चाहिए:\n  ##tone_language##  \n\n"],

            ['id' => 3325, 'template_id' => 91, 'key' => "hu-HU", 'value' =>  "Írjon kreatív újrajátszást a következő áttekintéshez :\n\n  ##description## \n\n Az eredmény hangnemének kell lennie:\n  ##tone_language##  \n\n"],

            ['id' => 3326, 'template_id' => 91, 'key' => "is-IS", 'value' =>  "Skrifaðu skapandi endursýningu fyrir eftirfarandi umsögn :\n\n  ##description## \n\n Rödd útkomunnar verður að vera:\n  ##tone_language##  \n\n"],

            ['id' => 3327, 'template_id' => 91, 'key' => "id-ID", 'value' =>  "Tulis tayangan ulang kreatif untuk ulasan berikut :\n\n  ##description## \n\n Nada suara hasilnya harus:\n  ##tone_language##  \n\n"],

            ['id' => 3328, 'template_id' => 91, 'key' => "it-IT", 'value' =>  "Scrivi un replay creativo per la seguente recensione :\n\n  ##description## \n\n Tono di voce del risultato deve essere:\n  ##tone_language##  \n\n"],

            ['id' => 3329, 'template_id' => 91, 'key' => "ja-JP", 'value' =>  "次のレビュー用にクリエイティブ リプレイを作成します :\n\n  ##description## \n\n 結果の声のトーンは次のとおりです。:\n  ##tone_language##  \n\n"],

            ['id' => 3330, 'template_id' => 91, 'key' => "ko-KR", 'value' =>  "다음 리뷰를 위한 크리에이티브 리플레이 작성 :\n\n  ##description## \n\n 결과의 어조는 다음과 같아야 합니다.:\n  ##tone_language##  \n\n"],

            ['id' => 3331, 'template_id' => 91, 'key' => "ms-MY", 'value' =>  "Tulis ulang tayang kreatif untuk ulasan berikut :\n\n  ##description## \n\n Nada suara keputusan mestilah:\n  ##tone_language##  \n\n"],

            ['id' => 3332, 'template_id' => 91, 'key' => "nb-NO", 'value' =>  "Skriv kreativ reprise for følgende anmeldelse :\n\n  ##description## \n\n Tonen i stemmen til resultatet må være:\n  ##tone_language##  \n\n"],

            ['id' => 3333, 'template_id' => 91, 'key' => "pl-PL", 'value' =>  "Napisz twórczą powtórkę do następnej recenzji :\n\n  ##description## \n\n Ton głosu wyniku musi być:\n  ##tone_language##  \n\n"],

            ['id' => 3334, 'template_id' => 91, 'key' => "pt-PT", 'value' =>  "Escrever replay criativo para a seguinte revisão :\n\n  ##description## \n\n O tom de voz do resultado deve ser:\n  ##tone_language##  \n\n"],

            ['id' => 3335, 'template_id' => 91, 'key' => "ru-RU", 'value' =>  "Напишите творческий повтор для следующего обзора :\n\n  ##description## \n\n Тон озвучивания результата должен быть:\n  ##tone_language##  \n\n"],

            ['id' => 3336, 'template_id' => 91, 'key' => "es-ES", 'value' =>  "Escriba una repetición creativa para la siguiente reseña :\n\n  ##description## \n\n El tono de voz del resultado debe ser:\n  ##tone_language##  \n\n"],

            ['id' => 3337, 'template_id' => 91, 'key' => "sv-SE", 'value' =>  "Skriv kreativ repris för följande recension :\n\n  ##description## \n\n Tonen i rösten för resultatet måste vara:\n  ##tone_language##  \n\n"],

            ['id' => 3338, 'template_id' => 91, 'key' => "tr-TR", 'value' =>  "Bir sonraki inceleme için reklam öğesi tekrarını yazın :\n\n  ##description## \n\n Sonucun ses tonu şöyle olmalıdır::\n  ##tone_language##  \n\n"],

            ['id' => 3339, 'template_id' => 91, 'key' => "pt-BR", 'value' =>  "Escrever replay criativo para a seguinte revisão :\n\n  ##description## \n\n O tom de voz do resultado deve ser:\n  ##tone_language##  \n\n"],

            ['id' => 3340, 'template_id' => 91, 'key' => "ro-RO", 'value' =>  "Scrieți reluare creativă pentru următoarea recenzie :\n\n  ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n  ##tone_language##  \n\n"],

            ['id' => 3341, 'template_id' => 91, 'key' => "vi-VN", 'value' =>  "Viết phát lại sáng tạo cho đánh giá sau :\n\n  ##description## \n\n Giọng điệu của kết quả phải là:\n  ##tone_language##  \n\n"],

            ['id' => 3342, 'template_id' => 91, 'key' => "sw-KE", 'value' =>  "Andika ubunifu wa kurudia kwa ukaguzi ufuatao :\n\n  ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n  ##tone_language##  \n\n"],

            ['id' => 3343, 'template_id' => 91, 'key' => "sl-SI", 'value' =>  "Napišite kreativno ponovitev za naslednji pregled :\n\n  ##description## \n\n Ton glasu rezultata mora biti:\n  ##tone_language##  \n\n"],

            ['id' => 3344, 'template_id' => 91, 'key' => "th-TH", 'value' =>  "เขียนรีเพลย์เชิงสร้างสรรค์สำหรับบทวิจารณ์ต่อไปนี้ :\n\n  ##description## \n\n น้ำเสียงของผลลัพธ์จะต้องเป็น:\n  ##tone_language##  \n\n"],

            ['id' => 3345, 'template_id' => 91, 'key' => "uk-UA", 'value' =>  "Напишіть творчий повтор для наступного огляду :\n\n  ##description## \n\n Тон голосу результату повинен бути:\n  ##tone_language##  \n\n"],

            ['id' => 3346, 'template_id' => 91, 'key' => "lt-LT", 'value' =>  "Parašykite kūrybinį pakartojimą kitai peržiūrai :\n\n  ##description## \n\n Turi būti rezultato balso tonas:\n  ##tone_language##  \n\n"],

            ['id' => 3347, 'template_id' => 91, 'key' => "bg-BG", 'value' =>  "Напишете творческо повторение за следния преглед :\n\n  ##description## \n\n Тонът на гласа на резултата трябва да бъде:\n  ##tone_language##  \n\n"],

            ['id' => 3348, 'template_id' => 92, 'key' => "en-US", 'value' => 'Write 10 catchy Slogan for the following:\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 3349, 'template_id' => 92, 'key' => "ar-AE", 'value' => 'اكتب 10 شعارات جذابة لما يلي:\n\n ##description## \n\n TTالوحيدة من صوت النتيجة يجب أن تكون::\n ##tone_language## \n\n'],

            ['id' => 3350, 'template_id' => 92, 'key' => "cmn-CN", 'value' => '为以下内容写10个朗朗上口的Slogan:\n\n ##description## \n\n 结果的声音必须是:\n ##tone_language## \n\n'],

            ['id' => 3351, 'template_id' => 92, 'key' => "hr-HR", 'value' => 'Napišite 10 privlačnih slogana za sljedeće:\n\n ##description## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n'],

            ['id' => 3352, 'template_id' => 92, 'key' => "cs-CZ", 'value' => 'Napište 10 chytlavých sloganů pro následující:\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n'],

            ['id' => 3353, 'template_id' => 92, 'key' => "da-DK", 'value' => 'Skriv 10 fængende slogan til følgende:\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n'],

            ['id' => 3354, 'template_id' => 92, 'key' => "nl-NL", 'value' => 'Schrijf 10 pakkende Slogan voor het volgende:\n\n ##description## \n\n Toon van de stem van het resultaat moet zijn:\n ##tone_language## \n\n'],

            ['id' => 3355, 'template_id' => 92, 'key' => "et-EE", 'value' => 'Kirjutage 10 meeldejäävat loosungit järgmise jaoks:\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 3356, 'template_id' => 92, 'key' => "fi-FI", 'value' => 'Kirjoita 10 tarttuvaa iskulausetta seuraavalle:\n\n ##description## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n'],

            ['id' => 3357, 'template_id' => 92, 'key' => "fr-FR", 'value' => 'Rédigez 10 slogans accrocheurs pour les éléments suivants:\n\n ##description## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n'],

            ['id' => 3358, 'template_id' => 92, 'key' => "de-DE", 'value' => 'Schreiben Sie 10 einprägsame Slogans für Folgendes:\n\n ##description## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n'],

            ['id' => 3359, 'template_id' => 92, 'key' => "el-GR", 'value' => 'Γράψτε 10 πιασάρικα σύνθημα για τα παρακάτω:\n\n ##description## \n\n Η φωνή του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 3360, 'template_id' => 92, 'key' => "he-IL", 'value' => 'כתוב 10 סלוגן קליט עבור הדברים הבאים:\n\n ##description## \n\n של הקול של התוצאה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 3361, 'template_id' => 92, 'key' => "hi-IN", 'value' => 'निम्नलिखित के लिए 10 आकर्षक स्लोगन लिखिए:\n\n ##description## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n'],

            ['id' => 3362, 'template_id' => 92, 'key' => "hu-HU", 'value' => 'Írj 10 fülbemászó szlogent a következőkhöz:\n\n ##description## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n'],

            ['id' => 3363, 'template_id' => 92, 'key' => "is-IS", 'value' => 'Skrifaðu 10 grípandi slagorð fyrir eftirfarandi:\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 3364, 'template_id' => 92, 'key' => "id-ID", 'value' => 'Tuliskan 10 Slogan yang menarik berikut ini:\n\n ##description## \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n'],

            ['id' => 3365, 'template_id' => 92, 'key' => "it-IT", 'value' => 'Scrivi 10 slogan accattivanti per quanto segue:\n\n ##description## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n'],

            ['id' => 3366, 'template_id' => 92, 'key' => "ja-JP", 'value' => '以下のキャッチーなスローガンを 10 個書いてください:\n\n ##description## \n\n 結果の声のトーンは:\n ##tone_language## \n\n'],

            ['id' => 3367, 'template_id' => 92, 'key' => "ko-KR", 'value' => '다음과 같은 10가지 눈에 띄는 슬로건 작성:\n\n ##description## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다:\n ##tone_language## \n\n'],

            ['id' => 3368, 'template_id' => 92, 'key' => "ms-MY", 'value' => 'Tulis 10 Slogan yang menarik untuk yang berikut:\n\n ##description## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n'],

            ['id' => 3369, 'template_id' => 92, 'key' => "nb-NO", 'value' => 'Skriv 10 fengende slagord for følgende:\n\n ##description## \n\n Tone av stemmen til resultatet må være:\n ##tone_language## \n\n'],

            ['id' => 3370, 'template_id' => 92, 'key' => "pl-PL", 'value' => 'Napisz 10 chwytliwych sloganów dla następujących osób:\n\n ##description## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n'],

            ['id' => 3371, 'template_id' => 92, 'key' => "pt-PT", 'value' => 'Escreva 10 slogans apelativos para o seguinte:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 3372, 'template_id' => 92, 'key' => "ru-RU", 'value' => 'Напишите 10 броских слоганов для следующих:\n\n ##description## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n'],

            ['id' => 3373, 'template_id' => 92, 'key' => "es-ES", 'value' => 'Escriba 10 lemas pegadizos para lo siguiente:\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n'],

            ['id' => 3374, 'template_id' => 92, 'key' => "sv-SE", 'value' => 'Skriv 10 catchy slogan för följande:\n\n ##description## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n'],

            ['id' => 3375, 'template_id' => 92, 'key' => "tr-TR", 'value' => 'Aşağıdakiler için akılda kalıcı 10 Slogan yazın:\n\n ##description## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n'],

            ['id' => 3376, 'template_id' => 92, 'key' => "pt-BR", 'value' => 'Escreva 10 slogans atraentes para o seguinte:\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 3377, 'template_id' => 92, 'key' => "ro-RO", 'value' => 'Scrieți 10 sloganuri captivante pentru următoarele:\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 3378, 'template_id' => 92, 'key' => "vi-VN", 'value' => 'Viết 10 Slogan hấp dẫn cho những điều sau đây:\n\n ##description## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n'],

            ['id' => 3379, 'template_id' => 92, 'key' => "sw-KE", 'value' => 'Andika Kauli mbi 10 ya kuvutia kwa yafuatayo:\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 3380, 'template_id' => 92, 'key' => "sl-SI", 'value' => 'Napišite 10 privlačnih sloganov za naslednje:\n\n ##description## \n\n  Ton glasu rezultata mora biti:\n ##tone_language## \n\n'],

            ['id' => 3381, 'template_id' => 92, 'key' => "th-TH", 'value' => 'เขียน 10 สโลแกนติดหูต่อไปนี้: \n\n ##description## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 3382, 'template_id' => 92, 'key' => "uk-UA", 'value' => 'Напишіть 10 яскравих слоганів для наступного:\n\n ##description## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n'],

            ['id' => 3383, 'template_id' => 92, 'key' => "lt-LT", 'value' => 'Parašykite 10 intriguojančių šūkių:\n\n ##description## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n'],

            ['id' => 3384, 'template_id' => 92, 'key' => "bg-BG", 'value' => 'Напишете 10 закачливи лозунга за следното:\n\n ##description## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 3385, 'template_id' => 93, 'key' => "en-US", 'value' => 'Write creative TikTok bio Using following keywords in the bio description:\n ##keywords## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 3386, 'template_id' => 93, 'key' => "ar-AE", 'value' => 'اكتب السيرة الذاتية الإبداعية لـ TikTok باستخدام الكلمات الرئيسية التالية في وصف السيرة الذاتية:\n ##keywords## \n\n TTالوحيدة من صوت النتيجة يجب أن تكون::\n ##tone_language## \n\n'],

            ['id' => 3387, 'template_id' => 93, 'key' => "cmn-CN", 'value' => '撰写有创意的 TikTok bio 在 bio 描述中使用以下关键字:\n ##keywords## \n\n 结果的声音必须是:\n ##tone_language## \n\n'],

            ['id' => 3388, 'template_id' => 93, 'key' => "hr-HR", 'value' => 'Napišite kreativnu TikTok biografiju koristeći sljedeće ključne riječi u opisu biografije:\n ##keywords## \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n'],

            ['id' => 3389, 'template_id' => 93, 'key' => "cs-CZ", 'value' => 'Napište kreativní TikTok bio Pomocí následujících klíčových slov v popisu bio:\n ##keywords## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n'],

            ['id' => 3390, 'template_id' => 93, 'key' => "da-DK", 'value' => 'Skriv kreativ TikTok-bio ved at bruge følgende nøgleord i biobeskrivelsen:\n ##keywords## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n'],

            ['id' => 3391, 'template_id' => 93, 'key' => "nl-NL", 'value' => 'Schrijf creatieve TikTok-bio Gebruik de volgende trefwoorden in de biobeschrijving:\n ##keywords## \n\n Toon van de stem van het resultaat moet zijn:\n ##tone_language## \n\n'],

            ['id' => 3392, 'template_id' => 93, 'key' => "et-EE", 'value' => 'Kirjutage loominguline TikToki biograafia, kasutades biokirjelduses järgmisi märksõnu:\n ##keywords## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 3393, 'template_id' => 93, 'key' => "fi-FI", 'value' => 'Kirjoita luova TikTok bio käyttämällä seuraavia avainsanoja biokuvauksessa:\n ##keywords## \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n'],

            ['id' => 3394, 'template_id' => 93, 'key' => "fr-FR", 'value' => 'Rédigez une bio TikTok créative en utilisant les mots-clés suivants dans la description de la bio:\n ##keywords## \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n'],

            ['id' => 3395, 'template_id' => 93, 'key' => "de-DE", 'value' => 'Schreiben Sie eine kreative TikTok-Biografie und verwenden Sie dabei die folgenden Schlüsselwörter in der Biografiebeschreibung:\n ##keywords## \n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n'],

            ['id' => 3396, 'template_id' => 93, 'key' => "el-GR", 'value' => 'Γράψτε δημιουργικό TikTok bio Χρησιμοποιώντας τις ακόλουθες λέξεις-κλειδιά στην περιγραφή του βιογραφικού:\n ##keywords## \n\n Η φωνή του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 3397, 'template_id' => 93, 'key' => "he-IL", 'value' => 'כתוב ביוגרפיה יצירתית של TikTok באמצעות מילות מפתח הבאות בתיאור הביולוגי:\n ##keywords## \n\n של הקול של התוצאה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 3398, 'template_id' => 93, 'key' => "hi-IN", 'value' => 'बायो डिस्क्रिप्शन में निम्नलिखित कीवर्ड्स का उपयोग करके क्रिएटिव टिकटॉक बायो लिखें:\n ##keywords## \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n'],

            ['id' => 3399, 'template_id' => 93, 'key' => "hu-HU", 'value' => 'Írjon kreatív TikTok életrajzot Az alábbi kulcsszavak használatával az életrajz leírásában:\n ##keywords## \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n'],

            ['id' => 3400, 'template_id' => 93, 'key' => "is-IS", 'value' => 'Skrifaðu skapandi TikTok líf með því að nota eftirfarandi leitarorð í líflýsingunni:\n ##keywords## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 3401, 'template_id' => 93, 'key' => "id-ID", 'value' => 'Tulis bio TikTok yang kreatif Menggunakan kata kunci berikut dalam deskripsi bio:\n ##keywords## \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n'],

            ['id' => 3402, 'template_id' => 93, 'key' => "it-IT", 'value' => 'Scrivi una biografia TikTok creativa utilizzando le seguenti parole chiave nella descrizione della biografia:\n ##keywords## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n'],

            ['id' => 3403, 'template_id' => 93, 'key' => "ja-JP", 'value' => 'クリエイティブな TikTok プロフィールを作成します。プロフィールの説明に次のキーワードを使用します。:\n ##keywords## \n\n 結果の声のトーンは:\n ##tone_language## \n\n'],

            ['id' => 3404, 'template_id' => 93, 'key' => "ko-KR", 'value' => '약력 설명에 다음 키워드를 사용하여 창의적인 TikTok 약력 작성:\n ##keywords## \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다:\n ##tone_language## \n\n'],

            ['id' => 3405, 'template_id' => 93, 'key' => "ms-MY", 'value' => 'Tulis bio TikTok kreatif Menggunakan kata kunci berikut dalam huraian bio:\n ##keywords## \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n'],

            ['id' => 3406, 'template_id' => 93, 'key' => "nb-NO", 'value' => 'Skriv kreativ TikTok-bio ved å bruke følgende nøkkelord i biobeskrivelsen:\n ##keywords## \n\n Tone av stemmen til resultatet må være:\n ##tone_language## \n\n'],

            ['id' => 3407, 'template_id' => 93, 'key' => "pl-PL", 'value' => 'Napisz kreatywną biografię TikTok, używając następujących słów kluczowych w opisie biografii:\n ##keywords## \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n'],

            ['id' => 3408, 'template_id' => 93, 'key' => "pt-PT", 'value' => 'Escrever uma biografia criativa para o TikTok Utilizar as seguintes palavras-chave na descrição da biografia:\n ##keywords## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 3409, 'template_id' => 93, 'key' => "ru-RU", 'value' => 'Напишите креативную биографию TikTok, используя следующие ключевые слова в описании биографии:\n ##keywords## \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n'],

            ['id' => 3410, 'template_id' => 93, 'key' => "es-ES", 'value' => 'Escriba una biografía creativa de TikTok usando las siguientes palabras clave en la descripción de la biografía:\n ##keywords## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n'],

            ['id' => 3411, 'template_id' => 93, 'key' => "sv-SE", 'value' => 'Skriv kreativa TikTok-bio Använd följande nyckelord i biobeskrivningen:\n ##keywords## \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n'],

            ['id' => 3412, 'template_id' => 93, 'key' => "tr-TR", 'value' => 'Biyo açıklamasında aşağıdaki anahtar kelimeleri kullanarak yaratıcı TikTok biyografisi yazın:\n ##keywords## \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n'],

            ['id' => 3413, 'template_id' => 93, 'key' => "pt-BR", 'value' => 'Escreva uma biografia criativa no TikTok Usando as seguintes palavras-chave na descrição da biografia:\n ##keywords## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 3414, 'template_id' => 93, 'key' => "ro-RO", 'value' => 'Scrieți o biografie creativa TikTok Folosind următoarele cuvinte cheie în descrierea biografiei:\n ##keywords## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 3415, 'template_id' => 93, 'key' => "vi-VN", 'value' => 'Viết tiểu sử TikTok sáng tạo Sử dụng các từ khóa sau trong mô tả sinh học:\n ##keywords## \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n'],

            ['id' => 3416, 'template_id' => 93, 'key' => "sw-KE", 'value' => 'Andika wasifu wa ubunifu wa TikTok Kwa kutumia maneno muhimu yafuatayo kwenye maelezo ya wasifu:\n ##keywords## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 3417, 'template_id' => 93, 'key' => "sl-SI", 'value' => 'Napišite ustvarjalni TikTok življenjepis z uporabo naslednjih ključnih besed v opisu biografije:\n ##keywords## \n\n  Ton glasu rezultata mora biti:\n ##tone_language## \n\n'],

            ['id' => 3418, 'template_id' => 93, 'key' => "th-TH", 'value' => 'เขียนชีวประวัติของ TikTok อย่างสร้างสรรค์โดยใช้คำหลักต่อไปนี้ในคำอธิบายชีวประวัติ: \n ##keywords## \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 3419, 'template_id' => 93, 'key' => "uk-UA", 'value' => 'Напишіть творчу біографію TikTok, використовуючи наступні ключові слова в описі біографії:\n ##keywords## \n\n Тон голосу результату має бути:\n ##tone_language## \n\n'],

            ['id' => 3420, 'template_id' => 93, 'key' => "lt-LT", 'value' => 'Parašykite kūrybišką TikTok biografiją Naudodami šiuos raktinius žodžius biografijos aprašyme:\n ##keywords## \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n'],

            ['id' => 3421, 'template_id' => 93, 'key' => "bg-BG", 'value' => 'Напишете творческа биография на TikTok, като използвате следните ключови думи в описанието на биографията:\n ##keywords## \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 3422, 'template_id' => 94, 'key' => "en-US", 'value' => 'Write a  cover letter for ##description## email \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 3423, 'template_id' => 94, 'key' => "ar-AE", 'value' => 'اكتب خطاب تغطية ##description## بريد إلكتروني \n\n TTالوحيدة من صوت النتيجة يجب أن تكون::\n ##tone_language## \n\n'],

            ['id' => 3424, 'template_id' => 94, 'key' => "cmn-CN", 'value' => '写求职信 ##description## 电子邮件 \n\n 结果的声音必须是:\n ##tone_language## \n\n'],

            ['id' => 3425, 'template_id' => 94, 'key' => "hr-HR", 'value' => 'Napišite propratno pismo ##description## elektronička pošta \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n'],

            ['id' => 3426, 'template_id' => 94, 'key' => "cs-CZ", 'value' => 'Napište motivační dopis ##description## e-mailem \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n'],

            ['id' => 3427, 'template_id' => 94, 'key' => "da-DK", 'value' => 'Skriv et følgebrev ##description## e-mail \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n'],

            ['id' => 3428, 'template_id' => 94, 'key' => "nl-NL", 'value' => 'Schrijf een begeleidende brief ##description## e-mailen \n\n Toon van de stem van het resultaat moet zijn:\n ##tone_language## \n\n'],

            ['id' => 3429, 'template_id' => 94, 'key' => "et-EE", 'value' => 'Kirjutage kaaskiri ##description## meili \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 3430, 'template_id' => 94, 'key' => "fi-FI", 'value' => 'Kirjoita saatekirje ##description## sähköposti \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n'],

            ['id' => 3431, 'template_id' => 94, 'key' => "fr-FR", 'value' => 'Rédiger une lettre de motivation ##description##  e-Courrier électronique \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n'],

            ['id' => 3432, 'template_id' => 94, 'key' => "de-DE", 'value' => 'Schreiben Sie ein Anschreiben ##description##  Email\n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n'],

            ['id' => 3433, 'template_id' => 94, 'key' => "el-GR", 'value' => 'Γράψτε μια συνοδευτική επιστολή ##description## ΗΛΕΚΤΡΟΝΙΚΗ ΔΙΕΥΘΥΝΣΗ \n\n Η φωνή του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 3434, 'template_id' => 94, 'key' => "he-IL", 'value' => 'כתוב מכתב מקדים ##description## אימייל  \n\n של הקול של התוצאה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 3435, 'template_id' => 94, 'key' => "hi-IN", 'value' => 'एक कवर लेटर लिखें ##description##  ईमेल\n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n'],

            ['id' => 3436, 'template_id' => 94, 'key' => "hu-HU", 'value' => 'Írj kísérőlevelet ##description## email \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n'],

            ['id' => 3437, 'template_id' => 94, 'key' => "is-IS", 'value' => 'Skrifaðu kynningarbréf ##description## tölvupósti \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 3438, 'template_id' => 94, 'key' => "id-ID", 'value' => 'Tulis surat pengantar ##description## surel \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n'],

            ['id' => 3439, 'template_id' => 94, 'key' => "it-IT", 'value' => 'Scrivi una lettera di presentazione ##description## e-mail \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n'],

            ['id' => 3440, 'template_id' => 94, 'key' => "ja-JP", 'value' => 'カバーレターを書く ##description## Eメール \n\n 結果の声のトーンは:\n ##tone_language## \n\n'],

            ['id' => 3441, 'template_id' => 94, 'key' => "ko-KR", 'value' => '커버 레터 작성 ##description## 이메일 \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다:\n ##tone_language## \n\n'],

            ['id' => 3442, 'template_id' => 94, 'key' => "ms-MY", 'value' => 'Tulis surat iringan ##description## emel \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n'],

            ['id' => 3443, 'template_id' => 94, 'key' => "nb-NO", 'value' => 'Skriv et følgebrev ##description## e-post \n\n Tone av stemmen til resultatet må være:\n ##tone_language## \n\n'],

            ['id' => 3444, 'template_id' => 94, 'key' => "pl-PL", 'value' => 'Napisz list motywacyjny ##description## wiadomość e-mail  \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n'],

            ['id' => 3445, 'template_id' => 94, 'key' => "pt-PT", 'value' => 'Escrever uma carta de apresentação para ##description## e-mail \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 3446, 'template_id' => 94, 'key' => "ru-RU", 'value' => 'Написать сопроводительное письмо ##description## электронная почта \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n'],

            ['id' => 3447, 'template_id' => 94, 'key' => "es-ES", 'value' => 'Escribe una carta de presentación ##description## correo electrónico \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n'],

            ['id' => 3448, 'template_id' => 94, 'key' => "sv-SE", 'value' => 'Skriv ett följebrev ##description## e-post  \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n'],

            ['id' => 3449, 'template_id' => 94, 'key' => "tr-TR", 'value' => 'Bir ön yazı yaz ##description##  e-posta \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n'],

            ['id' => 3450, 'template_id' => 94, 'key' => "pt-BR", 'value' => 'Escreva uma carta de apresentação para ##description##  e-mail \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 3451, 'template_id' => 94, 'key' => "ro-RO", 'value' => 'Scrieți o scrisoare de intenție ##description## e-mail \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 3452, 'template_id' => 94, 'key' => "vi-VN", 'value' => 'Viết thư xin việc ##description## e-mail \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n'],

            ['id' => 3453, 'template_id' => 94, 'key' => "sw-KE", 'value' => 'Andika barua ya kazi ##description## barua pepe  \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 3454, 'template_id' => 94, 'key' => "sl-SI", 'value' => 'Napišite spremno pismo ##description## E-naslov  \n\n  Ton glasu rezultata mora biti:\n ##tone_language## \n\n'],

            ['id' => 3455, 'template_id' => 94, 'key' => "th-TH", 'value' => 'เขียนจดหมายปะหน้า ##description## อีเมล \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 3456, 'template_id' => 94, 'key' => "uk-UA", 'value' => 'Напишіть супровідний лист ##description## електронною поштою \n\n Тон голосу результату має бути:\n ##tone_language## \n\n'],

            ['id' => 3457, 'template_id' => 94, 'key' => "lt-LT", 'value' => 'Parašykite motyvacinį laišką ##description## paštu \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n'],

            ['id' => 3458, 'template_id' => 94, 'key' => "bg-BG", 'value' => 'Напишете мотивационно писмо ##description## електронна поща \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 3459, 'template_id' => 95, 'key' => "en-US", 'value' => 'Write a personal intro  which  i can used at ##description## for myself \n\n Tone of voice of the result must be:\n ##tone_language## \n\n'],

            ['id' => 3460, 'template_id' => 95, 'key' => "ar-AE", 'value' => 'اكتب مقدمة شخصية يمكنني استخدامها في  ##description## لنفسي \n\n TTالوحيدة من صوت النتيجة يجب أن تكون::\n ##tone_language## \n\n'],

            ['id' => 3461, 'template_id' => 95, 'key' => "cmn-CN", 'value' => '写一个我可以使用的个人介绍 ##description## 为了我自己 \n\n 结果的声音必须是:\n ##tone_language## \n\n'],

            ['id' => 3462, 'template_id' => 95, 'key' => "hr-HR", 'value' => 'Napišite osobni intro koji mogu koristiti ##description## Za sebe \n\n Ton glasa za rezultat mora biti:\n ##tone_language## \n\n'],

            ['id' => 3463, 'template_id' => 95, 'key' => "cs-CZ", 'value' => 'Napište osobní intro, které mohu použít ##description## pro mě \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n'],

            ['id' => 3464, 'template_id' => 95, 'key' => "da-DK", 'value' => 'Skriv en personlig intro, som jeg kan bruge på ##description## v \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n'],

            ['id' => 3465, 'template_id' => 95, 'key' => "nl-NL", 'value' => 'Schrijf een persoonlijke intro waar ik bij kan gebruiken ##description## voor mezelf \n\n Toon van de stem van het resultaat moet zijn:\n ##tone_language## \n\n'],

            ['id' => 3466, 'template_id' => 95, 'key' => "et-EE", 'value' => 'Kirjutage isiklik tutvustus, mida saan kasutada ##description## enda jaoks \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n'],

            ['id' => 3467, 'template_id' => 95, 'key' => "fi-FI", 'value' => 'Kirjoita henkilökohtainen esittely, jota voin käyttää ##description## itselleni \n\n Tuloksen äänen on oltava:\n ##tone_language## \n\n'],

            ['id' => 3468, 'template_id' => 95, 'key' => "fr-FR", 'value' => 'Écrivez une introduction personnelle que je peux utiliser à ##description##  pour moi-même \n\n Le Tone de la voix du résultat doit être:\n ##tone_language## \n\n'],

            ['id' => 3469, 'template_id' => 95, 'key' => "de-DE", 'value' => 'Schreiben Sie ein persönliches Intro, das ich verwenden kann ##description##  für mich\n\n Ton der Stimme des Ergebnisses muss:\n ##tone_language## \n\n'],

            ['id' => 3470, 'template_id' => 95, 'key' => "el-GR", 'value' => 'Γράψτε μια προσωπική εισαγωγή στην οποία μπορώ να χρησιμοποιήσω ##description## για τον εαυτό μου \n\n Η φωνή του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n'],

            ['id' => 3471, 'template_id' => 95, 'key' => "he-IL", 'value' => 'כתוב מבוא אישי שבו אני יכול להשתמש ##description## בשבילי  \n\n של הקול של התוצאה חייב להיות:\n ##tone_language## \n\n'],

            ['id' => 3472, 'template_id' => 95, 'key' => "hi-IN", 'value' => 'एक व्यक्तिगत परिचय लिखें जिसका मैं उपयोग कर सकता हूं ##description##  अपने आप के लिए \n\n परिणाम की आवाज का स्वर होना चाहिए ।:\n ##tone_language## \n\n'],

            ['id' => 3773, 'template_id' => 95, 'key' => "hu-HU", 'value' => 'Írj egy személyes bemutatkozást, amit felhasználhatok ##description## magamnak \n\n Az eredmény hangjának meg kell lennie:\n ##tone_language## \n\n'],

            ['id' => 3774, 'template_id' => 95, 'key' => "is-IS", 'value' => 'Skrifaðu persónulegt kynningu sem ég get notað á ##description## fyrir mig \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n'],

            ['id' => 3475, 'template_id' => 95, 'key' => "id-ID", 'value' => 'Tulis intro pribadi yang bisa saya gunakan ##description## untuk diriku \n\n Nada suara hasilnya harus dibuat.:\n ##tone_language## \n\n'],

            ['id' => 3476, 'template_id' => 95, 'key' => "it-IT", 'value' => "Scrivi unintroduzione personale che posso usare su ##description## per me \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 3477, 'template_id' => 95, 'key' => "ja-JP", 'value' => 'で使用できる個人的な紹介文を書きます ##description## 自分のため \n\n 結果の声のトーンは:\n ##tone_language## \n\n'],

            ['id' => 3778, 'template_id' => 95, 'key' => "ko-KR", 'value' => '내가 사용할 수있는 개인 소개 쓰기 ##description## 나 자신을 위해 \n\n 결과의 음성 중 하나는 반드시 다음과 같아야 합니다:\n ##tone_language## \n\n'],

            ['id' => 3479, 'template_id' => 95, 'key' => "ms-MY", 'value' => 'Tulis intro peribadi yang boleh saya gunakan ##description## untuk diri saya sendiri \n\n Nada suara hasilnya mesti.:\n ##tone_language## \n\n'],

            ['id' => 3780, 'template_id' => 95, 'key' => "nb-NO", 'value' => 'Skriv en personlig intro som jeg kan bruke på ##description## for meg selv \n\n Tone av stemmen til resultatet må være:\n ##tone_language## \n\n'],

            ['id' => 3481, 'template_id' => 95, 'key' => "pl-PL", 'value' => 'Napisz osobiste wprowadzenie, którego będę mógł użyć ##description## dla siebie  \n\n Ton głosu w wyniku musi być:\n ##tone_language## \n\n'],

            ['id' => 3482, 'template_id' => 95, 'key' => "pt-PT", 'value' => 'Escrever uma introdução pessoal que possa ser utilizada em ##description## para mim próprio \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 3483, 'template_id' => 95, 'key' => "ru-RU", 'value' => 'Напишите личное введение, которое я могу использовать на ##description## для меня \n\n Тон голоса результата должен быть:\n ##tone_language## \n\n'],

            ['id' => 3484, 'template_id' => 95, 'key' => "es-ES", 'value' => 'Escribe una introducción personal que pueda usar en ##description## para mí \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n'],

            ['id' => 3485, 'template_id' => 95, 'key' => "sv-SE", 'value' => 'Skriv ett personligt intro som jag kan använda vid ##description## för mig själv  \n\n Ton av resultatet måste vara:\n ##tone_language## \n\n'],

            ['id' => 3486, 'template_id' => 95, 'key' => "tr-TR", 'value' => 'Kullanabileceğim kişisel bir giriş yaz ##description##  kendim için \n\n Sonucun sesinin tonu olmalı:\n ##tone_language## \n\n'],

            ['id' => 3487, 'template_id' => 95, 'key' => "pt-BR", 'value' => 'Escreva uma introdução pessoal que eu possa usar em ##description## para mim mesmo \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n'],

            ['id' => 3488, 'template_id' => 95, 'key' => "ro-RO", 'value' => 'Scrie o introducere personală pe care să o pot folosi ##description## pentru mine \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n'],

            ['id' => 3489, 'template_id' => 95, 'key' => "vi-VN", 'value' => 'Viết phần giới thiệu cá nhân mà tôi có thể sử dụng tại ##description## cho bản thân mình \n\n Giọng nói của kết quả phải là:\n ##tone_language## \n\n'],

            ['id' => 3490, 'template_id' => 95, 'key' => "sw-KE", 'value' => 'Andika utangulizi wa kibinafsi ambao ninaweza kutumia ##description## kwa ajili yangu mwenyewe \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n'],

            ['id' => 3491, 'template_id' => 95, 'key' => "sl-SI", 'value' => 'Napišite osebni uvod, ki ga lahko uporabim pri ##description## zame  \n\n  Ton glasu rezultata mora biti:\n ##tone_language## \n\n'],

            ['id' => 3492, 'template_id' => 95, 'key' => "th-TH", 'value' => 'เขียนคำนำส่วนตัวที่ฉันสามารถใช้ได้ ##description## สำหรับตัวฉันเอง \n\n เสียงหนึ่งของผลที่ได้ต้องเป็น:\n ##tone_language## \n\n'],

            ['id' => 3493, 'template_id' => 95, 'key' => "uk-UA", 'value' => 'Напишіть особисте інтро, яке я можу використати ##description## v \n\n Тон голосу результату має бути:\n ##tone_language## \n\n'],

            ['id' => 3494, 'template_id' => 95, 'key' => "lt-LT", 'value' => 'Parašykite asmeninį įvadą, kurį galėčiau panaudoti ##description## sau pačiam \n\n Balso tonas rezultatas turi būti:\n ##tone_language## \n\n'],

            ['id' => 3495, 'template_id' => 95, 'key' => "bg-BG", 'value' => 'Напишете лично въведение, което мога да използвам ##description## за мен \n\n Тон на гласа на резултата трябва да бъде:\n ##tone_language## \n\n'],

            ['id' => 3496, 'template_id' => 96, 'key' => "en-US", 'value' =>  "Write 10 catchy motivation quote for the following  :\n\n ##description## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 3497, 'template_id' => 96, 'key' => "ar-AE", 'value' =>   "اكتب 10 اقتباسات دافعة جذابة لما يلي  :\n\n ##description## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 3498, 'template_id' => 96, 'key' => "cmn-CN", 'value' =>   "為下列項目撰寫 10 個朗朗文動機報價  :\n\n ##description## \n\n 必須要有聲音的聲音:\n ##tone_language## \n\n"],

            ['id' => 3499, 'template_id' => 96, 'key' => "hr-HR", 'value' =>   "Napišite 10 privlačnih motivacijskih citata za sljedeće  :\n\n ##description## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 3500, 'template_id' => 96, 'key' => "cs-CZ", 'value' =>   "Napište 10 chytlavých motivačních citátů pro následující  :\n\n ##description## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 3501, 'template_id' => 96, 'key' => "da-DK", 'value' =>   "Skriv 10 fængende motivationscitater til følgende  :\n\n ##description## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 3502, 'template_id' => 96, 'key' => "nl-NL", 'value' =>   "Schrijf 10 pakkende motivatiecitaten voor het volgende  :\n\n ##description## \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 3503, 'template_id' => 96, 'key' => "et-EE", 'value' =>   "Kirjutage 10 meeldejäävat motivatsioonitsitaati järgmise jaoks  :\n\n ##description## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 3504, 'template_id' => 96, 'key' => "fi-FI", 'value' =>   "Kirjoita 10 tarttuvaa motivaatiolainausta seuraavaan  :\n\n ##description## \n\n Äänesävyn tuloksen on oltava:\n ##tone_language## \n\n"],

            ['id' => 3505, 'template_id' => 96, 'key' => "fr-FR", 'value' =>   "Rédigez 10 citations de motivation accrocheuses pour les éléments suivants  :\n\n ##description## \n\n Le ton de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 3506, 'template_id' => 96, 'key' => "de-DE", 'value' =>   "Schreiben Sie 10 einprägsame Motivationszitate für Folgendes  :\n\n ##description## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 3507, 'template_id' => 96, 'key' => "el-GR", 'value' =>   "Γράψτε 10 συναρπαστικά κίνητρα για τα παρακάτω  :\n\n ##description## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 3508, 'template_id' => 96, 'key' => "he-IL", 'value' =>   "כתוב 10 ציטוטי מוטיבציה קליטים עבור הדברים הבאים  :\n\n ##description## \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 3509, 'template_id' => 96, 'key' => "hi-IN", 'value' =>   "निम्नलिखित के लिए 10 आकर्षक प्रेरक उद्धरण लिखिए  :\n\n ##description## \n\n परिणाम का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 3510, 'template_id' => 96, 'key' => "hu-HU", 'value' =>   "Írj 10 fülbemászó motivációs idézetet a következőkhöz!  :\n\n ##description## \n\n Az eredmény hangnemének kell lennie:\n ##tone_language## \n\n"],

            ['id' => 3511, 'template_id' => 96, 'key' => "is-IS", 'value' =>   "Skrifaðu 10 grípandi hvatningartilvitnanir fyrir eftirfarandi  :\n\n ##description## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 3512, 'template_id' => 96, 'key' => "id-ID", 'value' =>   "Tulis 10 kutipan motivasi yang menarik untuk yang berikut ini  :\n\n ##description## \n\n Nada suara hasilnya harus:\n ##tone_language## \n\n"],

            ['id' => 3513, 'template_id' => 96, 'key' => "it-IT", 'value' =>   "Scrivi 10 citazioni motivazionali accattivanti per quanto segue  :\n\n ##description## \n\n Tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 3514, 'template_id' => 96, 'key' => "ja-JP", 'value' =>   "以下のキャッチーな動機付けの引用を 10 個書いてください  :\n\n ##description## \n\n 結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n"],

            ['id' => 3515, 'template_id' => 96, 'key' => "ko-KR", 'value' =>   "다음에 대해 기억하기 쉬운 동기 부여 인용문 10개를 작성하십시오.  :\n\n ##description## \n\n 결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 3516, 'template_id' => 96, 'key' => "ms-MY", 'value' =>   "Tulis 10 petikan motivasi yang menarik untuk yang berikut  :\n\n ##description## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 3517, 'template_id' => 96, 'key' => "nb-NO", 'value' =>   "Skriv 10 fengende motivasjonssitat for følgende  :\n\n ##description## \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 3518, 'template_id' => 96, 'key' => "pl-PL", 'value' =>   "Napisz 10 chwytliwych cytatów motywacyjnych dla następujących osób  :\n\n ##description## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 3519, 'template_id' => 96, 'key' => "pt-PT", 'value' =>   "Escreva 10 citações cativantes de motivação para o seguinte  :\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 3520, 'template_id' => 96, 'key' => "ru-RU", 'value' => "Напишите 10 броских мотивационных цитат для следующего  :\n\n ##description## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 3521, 'template_id' => 96, 'key' => "es-ES", 'value' =>   "Escriba 10 citas de motivación pegadizas para lo siguiente  :\n\n ##description## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 3522, 'template_id' => 96, 'key' => "sv-SE", 'value' =>   "Skriv 10 catchy motivationscitat för följande  :\n\n ##description## \n\n Tonen i rösten för resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 3523, 'template_id' => 96, 'key' => "tr-TR", 'value' =>   "Aşağıdakiler için 10 akılda kalıcı motivasyon teklifi yazın  :\n\n ##description## \n\n Sonucun ses tonu şöyle olmalıdır::\n ##tone_language## \n\n"],

            ['id' => 3524, 'template_id' => 96, 'key' => "pt-BR", 'value' =>   "Escreva 10 citações cativantes de motivação para o seguinte  :\n\n ##description## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 3525, 'template_id' => 96, 'key' => "ro-RO", 'value' =>   "Scrieți 10 citate de motivație captivante pentru următoarele  :\n\n ##description## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 3526, 'template_id' => 96, 'key' => "vi-VN", 'value' =>   "Viết 10 trích dẫn động lực hấp dẫn cho những điều sau đây  :\n\n ##description## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 3527, 'template_id' => 96, 'key' => "sw-KE", 'value' =>   "Andika nukuu 10 za motisha kwa zifuatazo  :\n\n ##description## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 3528, 'template_id' => 96, 'key' => "sl-SI", 'value' =>   "Napišite 10 privlačnih motivacijskih citatov za naslednje  :\n\n ##description## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 3529, 'template_id' => 96, 'key' => "th-TH", 'value' =>   "เขียน 10 คำพูดสร้างแรงบันดาลใจลวงต่อไปนี้  :\n\n ##description## \n\n โทนเสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 3530, 'template_id' => 96, 'key' => "uk-UA", 'value' =>   "Напишіть 10 привабливих мотиваційних цитат для наступного  :\n\n ##description## \n\n Тон голосу результату повинен бути:\n ##tone_language## \n\n"],

            ['id' => 3531, 'template_id' => 96, 'key' => "lt-LT", 'value' =>   "Parašykite 10 patrauklių motyvacijos citatų toliau nurodytam klausimui  :\n\n ##description## \n\n Turi būti rezultato balso tonas:\n ##tone_language## \n\n"],

            ['id' => 3532, 'template_id' => 96, 'key' => "bg-BG", 'value' =>   "Напишете 10 закачливи мотивационни цитата за следното  :\n\n ##description## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 3533, 'template_id' => 97, 'key' => "en-US", 'value' =>  "Write a descriptive  motivation speech for the following  :\n\n ##description##  \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 3534, 'template_id' => 97, 'key' => "ar-AE", 'value' =>   "اكتب خطاب دافع وصفي لما يلي  :\n\n ##description##  \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 3535, 'template_id' => 97, 'key' => "cmn-CN", 'value' =>   "為下列項目撰寫敘述性動機語音  :\n\n ##description##  \n\n 必須要有聲音的聲音:\n ##tone_language## \n\n"],

            ['id' => 3536, 'template_id' => 97, 'key' => "hr-HR", 'value' =>   "Napišite opisni motivacijski govor za sljedeće  :\n\n ##description##  \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 3537, 'template_id' => 97, 'key' => "cs-CZ", 'value' =>   "Napište popisný motivační projev pro následující  :\n\n ##description##  \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 3538, 'template_id' => 97, 'key' => "da-DK", 'value' =>   "Skriv en beskrivende motivationstale til følgende  :\n\n ##description##  \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 3539, 'template_id' => 97, 'key' => "nl-NL", 'value' =>   "Schrijf een beschrijvende motivatietoespraak voor het volgende  :\n\n ##description##  \n\n De tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 3540, 'template_id' => 97, 'key' => "et-EE", 'value' =>   "Kirjutage kirjeldav motivatsioonikõne järgneva jaoks  :\n\n ##description##  \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 3541, 'template_id' => 97, 'key' => "fi-FI", 'value' =>   "Kirjoita kuvaava motivaatiopuhe seuraavaa varten  :\n\n ##description##  \n\n Äänesävyn tuloksen on oltava:\n ##tone_language## \n\n"],

            ['id' => 3542, 'template_id' => 97, 'key' => "fr-FR", 'value' =>   "Rédigez un discours de motivation descriptif pour les éléments suivants  :\n\n ##description##  \n\n Le ton de la voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 3543, 'template_id' => 97, 'key' => "de-DE", 'value' =>   "Schreiben Sie eine beschreibende Motivationsrede für Folgendes  :\n\n ##description##  \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 3544, 'template_id' => 97, 'key' => "el-GR", 'value' =>   "Γράψτε μια περιγραφική ομιλία κινήτρων για τα παρακάτω  :\n\n ##description##  \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 3545, 'template_id' => 97, 'key' => "he-IL", 'value' =>   "כתוב נאום מוטיבציה תיאורי עבור הדברים הבאים  :\n\n ##description##  \n\n טון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 3546, 'template_id' => 97, 'key' => "hi-IN", 'value' =>   "निम्नलिखित के लिए वर्णनात्मक प्रेरक भाषण लिखिए  :\n\n ##description##  \n\n परिणाम का स्वर स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 3547, 'template_id' => 97, 'key' => "hu-HU", 'value' =>   "Írjon leíró motivációs beszédet a következőkhöz!  :\n\n ##description##  \n\n Az eredmény hangnemének kell lennie:\n ##tone_language## \n\n"],

            ['id' => 3548, 'template_id' => 97, 'key' => "is-IS", 'value' =>   "Skrifaðu lýsandi hvatningarræðu fyrir eftirfarandi  :\n\n ##description##  \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 3549, 'template_id' => 97, 'key' => "id-ID", 'value' =>   "Tulis pidato motivasi deskriptif untuk yang berikut ini  :\n\n ##description##  \n\n Nada suara hasilnya harus:\n ##tone_language## \n\n"],

            ['id' => 3550, 'template_id' => 97, 'key' => "it-IT", 'value' =>   "Scrivi un discorso motivazionale descrittivo per quanto segue  :\n\n ##description##  \n\n Tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 3551, 'template_id' => 97, 'key' => "ja-JP", 'value' =>   "次のような動機を説明するスピーチを書いてください。  :\n\n ##description##  \n\n 結果の声のトーンは次のとおりです。:\n ##tone_language## \n\n"],

            ['id' => 3552, 'template_id' => 97, 'key' => "ko-KR", 'value' =>   "다음에 대한 설명적인 동기 연설을 작성하십시오.  :\n\n ##description##  \n\n 결과의 어조는 다음과 같아야 합니다.:\n ##tone_language## \n\n"],

            ['id' => 3553, 'template_id' => 97, 'key' => "ms-MY", 'value' =>   "Tulis ucapan motivasi deskriptif untuk perkara berikut  :\n\n ##description##  \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 3554, 'template_id' => 97, 'key' => "nb-NO", 'value' =>   "Skriv en beskrivende motivasjonstale for følgende  :\n\n ##description##  \n\n Tonen i stemmen til resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 3555, 'template_id' => 97, 'key' => "pl-PL", 'value' =>   "Napisz opisowe przemówienie motywacyjne dla poniższych osób  :\n\n ##description##  \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 3556, 'template_id' => 97, 'key' => "pt-PT", 'value' =>   "Escreva um discurso de motivação descritivo para o seguinte  :\n\n ##description##  \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 3557, 'template_id' => 97, 'key' => "ru-RU", 'value' =>   "Напишите описательную мотивационную речь для следующего  :\n\n ##description##  \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 3558, 'template_id' => 97, 'key' => "es-ES", 'value' =>   "Escriba un discurso de motivación descriptivo para los siguientes  :\n\n ##description##  \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 3559, 'template_id' => 97, 'key' => "sv-SE", 'value' =>   "Skriv ett beskrivande motivationstal för följande  :\n\n ##description##  \n\n Tonen i rösten för resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 3560, 'template_id' => 97, 'key' => "tr-TR", 'value' =>   "Aşağıdakiler için açıklayıcı bir motivasyon konuşması yazın  :\n\n ##description##  \n\n Sonucun ses tonu şöyle olmalıdır::\n ##tone_language## \n\n"],

            ['id' => 3561, 'template_id' => 97, 'key' => "pt-BR", 'value' =>   "Escreva um discurso de motivação descritivo para o seguinte  :\n\n ##description##  \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 3562, 'template_id' => 97, 'key' => "ro-RO", 'value' =>   "Scrieți un discurs descriptiv de motivare pentru următoarele  :\n\n ##description##  \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 3563, 'template_id' => 97, 'key' => "vi-VN", 'value' =>   "Viết một bài phát biểu động cơ mô tả cho những điều sau đây  :\n\n ##description##  \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 3564, 'template_id' => 97, 'key' => "sw-KE", 'value' =>   "Andika hotuba ya maelezo ya motisha kwa yafuatayo  :\n\n ##description##  \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 3565, 'template_id' => 97, 'key' => "sl-SI", 'value' =>   "Napišite opisni motivacijski govor za naslednje  :\n\n ##description##  \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 3566, 'template_id' => 97, 'key' => "th-TH", 'value' =>   "เขียนบรรยายแรงจูงใจต่อไปนี้  :\n\n ##description##  \n\n โทนเสียงของผลลัพธ์จะต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 3567, 'template_id' => 97, 'key' => "uk-UA", 'value' =>   "Напишіть описову мотиваційну промову для наступного  :\n\n ##description##  \n\n Тон голосу результату повинен бути:\n ##tone_language## \n\n"],

            ['id' => 3568, 'template_id' => 97, 'key' => "lt-LT", 'value' =>   "Parašykite aprašomąją motyvacinę kalbą dėl šių dalykų  :\n\n ##description##  \n\n Turi būti rezultato balso tonas:\n ##tone_language## \n\n"],

            ['id' => 3569, 'template_id' => 97, 'key' => "bg-BG", 'value' =>   "Напишете описателна мотивационна реч за следното  :\n\n ##description##  \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 3570, 'template_id' => 98, 'key' => "en-US", 'value' =>  "Write 10 catchy good wishes for the following occasion  : ##occasion## for my : ##relation## \n\n Tone of voice of the result must be:\n ##tone_language## \n\n"],

            ['id' => 3571, 'template_id' => 98, 'key' => "ar-AE", 'value' =>   "اكتب 10 تمنيات طيبة وجذابة للمناسبة التالية : ##occasion## لاجلي : ##relation## \n\n يجب أن تكون نبرة صوت النتيجة:\n ##tone_language## \n\n"],

            ['id' => 3572, 'template_id' => 98, 'key' => "cmn-CN", 'value' =>   "为以下场合写下 10 个朗朗上口的美好祝福  : ##occasion## 为了我的 : ##relation## \n\n 结果的语气必须是：\n ##tone_language## \n\n"],

            ['id' => 3573, 'template_id' => 98, 'key' => "hr-HR", 'value' =>   "Napišite 10 lijepih lijepih želja za sljedeću priliku  : ##occasion## za moj : ##relation## \n\n Ton glasa rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 3574, 'template_id' => 98, 'key' => "cs-CZ", 'value' =>   "Napište 10 chytlavých přání všeho dobrého pro následující příležitost  : ##occasion## pro mě : ##relation## \n\n Tón hlasu výsledku musí být:\n ##tone_language## \n\n"],

            ['id' => 3575, 'template_id' => 98, 'key' => "da-DK", 'value' =>   "Skriv 10 fængende gode ønsker til den følgende lejlighed  : ##occasion## for min : ##relation## \n\n Tone of voice af resultatet skal være:\n ##tone_language## \n\n"],

            ['id' => 3576, 'template_id' => 98, 'key' => "nl-NL", 'value' =>   "Schrijf 10 pakkende goede wensen voor de volgende gelegenheid  : ##occasion## voor mijn : ##relation## \n\n Tone of voice van het resultaat moet zijn:\n ##tone_language## \n\n"],

            ['id' => 3577, 'template_id' => 98, 'key' => "et-EE", 'value' =>   "Kirjutage järgmiseks puhuks 10 meeldejäävat head soovi  : ##occasion## minu : ##relation## \n\n Tulemuse hääletoon peab olema:\n ##tone_language## \n\n"],

            ['id' => 3578, 'template_id' => 98, 'key' => "fi-FI", 'value' =>   "Kirjoita 10 tarttuvaa onnentoivotusta seuraavaan tilaisuuteen  : ##occasion## minun puolestani : ##relation## \n\n Tuloksen äänensävyn tulee olla:\n ##tone_language## \n\n"],

            ['id' => 3579, 'template_id' => 98, 'key' => "fr-FR", 'value' =>   "Écrivez 10 bons vœux accrocheurs pour l'occasion suivante  : ##occasion## pour mon : ##relation## \n\n Le ton de voix du résultat doit être:\n ##tone_language## \n\n"],

            ['id' => 3580, 'template_id' => 98, 'key' => "de-DE", 'value' =>   "Schreiben Sie 10 einprägsame Glückwünsche für den folgenden Anlass  : ##occasion## für mein : ##relation## \n\n Der Tonfall des Ergebnisses muss sein:\n ##tone_language## \n\n"],

            ['id' => 3581, 'template_id' => 98, 'key' => "el-GR", 'value' =>   "Γράψε 10 πιασάρικες ευχές για την επόμενη περίσταση  : ##occasion## για τη δικιά μου : ##relation## \n\n Ο τόνος της φωνής του αποτελέσματος πρέπει να είναι:\n ##tone_language## \n\n"],

            ['id' => 3582, 'template_id' => 98, 'key' => "he-IL", 'value' =>   "כתבו 10 איחולים קליטים לאירוע הבא  : ##occasion## עבור שלי : ##relation## \n\nטון הדיבור של התוצאה חייב להיות:\n ##tone_language## \n\n"],

            ['id' => 3583, 'template_id' => 98, 'key' => "hi-IN", 'value' =>   "निम्नलिखित अवसर के लिए 10 आकर्षक शुभकामनाएं लिखें  : ##occasion## मेरे लिए : ##relation## \n\n परिणाम की आवाज़ का स्वर होना चाहिए:\n ##tone_language## \n\n"],

            ['id' => 3584, 'template_id' => 98, 'key' => "hu-HU", 'value' =>   "Írj 10 fülbemászó jókívánságot a következő alkalomra  : ##occasion## az én : ##relation## \n\n Az eredmény hangnemének a következőnek kell lennie:\n ##tone_language## \n\n"],

            ['id' => 3585, 'template_id' => 98, 'key' => "is-IS", 'value' =>   "Skrifaðu 10 grípandi góðar óskir fyrir næsta tilefni  : ##occasion## fyrir mig : ##relation## \n\n Rödd útkomunnar verður að vera:\n ##tone_language## \n\n"],

            ['id' => 3586, 'template_id' => 98, 'key' => "id-ID", 'value' =>   "Tulis 10 harapan baik yang menarik untuk kesempatan berikut  : ##occasion## untuk ku : ##relation## \n\n Nada suara hasil harus:\n ##tone_language## \n\n"],

            ['id' => 3587, 'template_id' => 98, 'key' => "it-IT", 'value' =>   "Scrivi 10 accattivanti auguri per la prossima occasione  : ##occasion## per me : ##relation## \n\n Il tono di voce del risultato deve essere:\n ##tone_language## \n\n"],

            ['id' => 3588, 'template_id' => 98, 'key' => "ja-JP", 'value' =>   "次の機会に向けて、キャッチーな良い願いを 10 個書いてください  : ##occasion## 私のために : ##relation## \n\n 結果の口調は次のようになります:\n ##tone_language## \n\n"],

            ['id' => 3589, 'template_id' => 98, 'key' => "ko-KR", 'value' =>   "다음 행사에 대한 10가지 좋은 소원을 적어보세요  : ##occasion## 나를 위해 : ##relation## \n\n 결과의 어조는 다음과 같아야 합니다:\n ##tone_language## \n\n"],

            ['id' => 3590, 'template_id' => 98, 'key' => "ms-MY", 'value' =>   "Tulis 10 ucapan selamat yang menarik untuk majlis berikut  : ##occasion## untuk saya : ##relation## \n\n Nada suara keputusan mestilah:\n ##tone_language## \n\n"],

            ['id' => 3591, 'template_id' => 98, 'key' => "nb-NO", 'value' =>   "Skriv 10 fengende lykkeønskninger for neste anledning  : ##occasion## for min : ##relation## \n\n Stemmetonen for resultatet må være:\n ##tone_language## \n\n"],

            ['id' => 3592, 'template_id' => 98, 'key' => "pl-PL", 'value' =>   "Napisz 10 chwytliwych życzeń na następną okazję  : ##occasion## dla mnie : ##relation## \n\n Ton głosu wyniku musi być:\n ##tone_language## \n\n"],

            ['id' => 3593, 'template_id' => 98, 'key' => "pt-PT", 'value' =>   "Escreva 10 bons desejos cativantes para a seguinte ocasião  : ##occasion## para o meu : ##relation## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 3594, 'template_id' => 98, 'key' => "ru-RU", 'value' =>   "Напишите 10 запоминающихся добрых пожеланий на следующий случай  : ##occasion## для меня : ##relation## \n\n Тон озвучивания результата должен быть:\n ##tone_language## \n\n"],

            ['id' => 3595, 'template_id' => 98, 'key' => "es-ES", 'value' =>   "Escribe 10 buenos deseos pegadizos para la próxima ocasión  : ##occasion## para mi : ##relation## \n\n El tono de voz del resultado debe ser:\n ##tone_language## \n\n"],

            ['id' => 3596, 'template_id' => 98, 'key' => "sv-SE", 'value' =>   "Skriv 10 catchy lyckönskningar för följande tillfälle  : ##occasion## för min : ##relation## \n\n Tonen i resultatet måste vara:\n ##tone_language## \n\n"],

            ['id' => 3597, 'template_id' => 98, 'key' => "tr-TR", 'value' =>   "Bir sonraki durum için akılda kalıcı 10 iyi dilek yazın  : ##occasion## benim için : ##relation## \n\n Sonucun ses tonu şöyle olmalıdır:\n ##tone_language## \n\n"],

            ['id' => 3598, 'template_id' => 98, 'key' => "pt-BR", 'value' =>   "Escreva 10 bons votos cativantes para a seguinte ocasião  : ##occasion## para mim : ##relation## \n\n O tom de voz do resultado deve ser:\n ##tone_language## \n\n"],

            ['id' => 3599, 'template_id' => 98, 'key' => "ro-RO", 'value' =>   "Scrie 10 urări de bine atrăgătoare pentru următoarea ocazie  : ##occasion## pentru a mea : ##relation## \n\n Tonul vocii rezultatului trebuie să fie:\n ##tone_language## \n\n"],

            ['id' => 3600, 'template_id' => 98, 'key' => "vi-VN", 'value' =>   "Viết 10 lời chúc hấp dẫn cho dịp sau  : ##occasion## cho tôi : ##relation## \n\n Giọng điệu của kết quả phải là:\n ##tone_language## \n\n"],

            ['id' => 3601, 'template_id' => 98, 'key' => "sw-KE", 'value' =>   "Andika matashi 10 mazuri kwa hafla ifuatayo  : ##occasion## kwa wangu : ##relation## \n\n Toni ya sauti ya matokeo lazima iwe:\n ##tone_language## \n\n"],

            ['id' => 3602, 'template_id' => 98, 'key' => "sl-SI", 'value' =>   "Napišite 10 privlačnih dobrih želja za naslednjo priložnost  : ##occasion## za moj : ##relation## \n\n Ton glasu rezultata mora biti:\n ##tone_language## \n\n"],

            ['id' => 3603, 'template_id' => 98, 'key' => "th-TH", 'value' =>   "เขียนความปรารถนาดีลวง 10 ประการต่อไปนี้  : ##occasion## สำหรับฉัน : ##relation## \n\n โทนเสียงของผลลัพธ์ต้องเป็น:\n ##tone_language## \n\n"],

            ['id' => 3604, 'template_id' => 98, 'key' => "uk-UA", 'value' =>   "Напишіть 10 яскравих побажань на наступну подію  : ##occasion## для мого : ##relation## \n\n Тон голосу результату повинен бути таким:\n ##tone_language## \n\n"],

            ['id' => 3605, 'template_id' => 98, 'key' => "lt-LT", 'value' =>   "Parašykite 10 patrauklių linkėjimų kitai progai  : ##occasion## už mano : ##relation## \n\n Rezultato balso tonas turi būti:\n ##tone_language## \n\n"],

            ['id' => 3606, 'template_id' => 98, 'key' => "bg-BG", 'value' =>   "Напишете 10 запомнящи се добри пожелания за следващия повод  : ##occasion## за моя : ##relation## \n\n Тонът на гласа на резултата трябва да бъде:\n ##tone_language## \n\n"],

            ['id' => 3607, 'template_id' => 99, 'key' => "en-US", 'value' =>  "Write creative & convincing bid for the following project :\n\n ##description##"],

            ['id' => 3608, 'template_id' => 99, 'key' => "ar-AE", 'value' =>   "اكتب محاولة إبداعية ومقنعة للمشروع التالي\n\n ##description##"],

            ['id' => 3609, 'template_id' => 99, 'key' => "cmn-CN", 'value' =>   "为以下项目写出有创意和有说服力的标书：\n\n ##description##"],

            ['id' => 3610, 'template_id' => 99, 'key' => "hr-HR", 'value' =>   "Napišite kreativnu i uvjerljivu ponudu za sljedeći projekt:\n\n ##description##"],

            ['id' => 3611, 'template_id' => 99, 'key' => "cs-CZ", 'value' =>   "Napište kreativní a přesvědčivou nabídku pro následující projekt:\n\n ##description##"],

            ['id' => 3612, 'template_id' => 99, 'key' => "da-DK", 'value' =>   "Skriv et kreativt og overbevisende bud på følgende projekt:\n\n ##description##"],

            ['id' => 3613, 'template_id' => 99, 'key' => "nl-NL", 'value' =>   "Schrijven van een creatief & overtuigend bod voor het volgende project:\n\n ##description##"],

            ['id' => 3614, 'template_id' => 99, 'key' => "et-EE", 'value' =>   "Kirjutage loov ja veenev pakkumine järgmise projekti jaoks:\n\n ##description##"],

            ['id' => 3615, 'template_id' => 99, 'key' => "fi-FI", 'value' =>   "Kirjoita luova ja vakuuttava tarjous seuraavaan projektiin:\n\n ##description##"],

            ['id' => 3616, 'template_id' => 99, 'key' => "fr-FR", 'value' =>   "Rédiger une offre créative et convaincante pour le projet suivant :\n\n ##description##"],

            ['id' => 3617, 'template_id' => 99, 'key' => "de-DE", 'value' =>   "Schreiben Sie ein kreatives und überzeugendes Angebot für das folgende Projekt:\n\n ##description##"],

            ['id' => 3618, 'template_id' => 99, 'key' => "el-GR", 'value' =>   "Γράψτε δημιουργική και πειστική προσφορά για το παρακάτω έργο:\n\n ##description##"],

            ['id' => 3619, 'template_id' => 99, 'key' => "he-IL", 'value' =>   "כתוב הצעה יצירתית ומשכנעת עבור הפרויקט הבא:\n\n ##description##"],

            ['id' => 3620, 'template_id' => 99, 'key' => "hi-IN", 'value' =>   "निम्नलिखित परियोजना के लिए रचनात्मक और विश्वसनीय बोली लिखें:\n\n ##description##"],

            ['id' => 3621, 'template_id' => 99, 'key' => "hu-HU", 'value' =>   "Írjon kreatív és meggyőző ajánlatot a következő projektre:\n\n ##description##"],

            ['id' => 3622, 'template_id' => 99, 'key' => "is-IS", 'value' =>   "Skrifaðu skapandi og sannfærandi tilboð í eftirfarandi verkefni:\n\n ##description##"],

            ['id' => 3623, 'template_id' => 99, 'key' => "id-ID", 'value' =>   "Tulis tawaran yang kreatif & meyakinkan untuk proyek berikut :\n\n ##description##"],

            ['id' => 3624, 'template_id' => 99, 'key' => "it-IT", 'value' =>   "Scrivi un'offerta creativa e convincente per il seguente progetto:\n\n ##description##"],

            ['id' => 3625, 'template_id' => 99, 'key' => "ja-JP", 'value' =>   "次のプロジェクトに対してクリエイティブで説得力のある入札書を作成します:\n\n ##description##"],

            ['id' => 3626, 'template_id' => 99, 'key' => "ko-KR", 'value' =>   "다음 프로젝트에 대한 창의적이고 설득력 있는 입찰가 작성:\n\n ##description##"],

            ['id' => 3627, 'template_id' => 99, 'key' => "ms-MY", 'value' =>   "Tulis tawaran yang kreatif & meyakinkan untuk projek berikut:\n\n ##description##"],

            ['id' => 3628, 'template_id' => 99, 'key' => "nb-NO", 'value' =>   "Skriv et kreativt og overbevisende bud på følgende prosjekt:\n\n ##description##"],

            ['id' => 3629, 'template_id' => 99, 'key' => "pl-PL", 'value' =>   "Napisz kreatywną i przekonującą ofertę na następujący projekt:\n\n ##description##"],

            ['id' => 3630, 'template_id' => 99, 'key' => "pt-PT", 'value' =>   "Escreva um lance criativo e convincente para o seguinte projeto:\n\n ##description##"],

            ['id' => 3631, 'template_id' => 99, 'key' => "ru-RU", 'value' =>   "Напишите креативную и убедительную заявку на следующий проект:\n\n ##description##"],

            ['id' => 3632, 'template_id' => 99, 'key' => "es-ES", 'value' =>   "Escriba una oferta creativa y convincente para el siguiente proyecto:\n\n ##description##"],

            ['id' => 3633, 'template_id' => 99, 'key' => "sv-SE", 'value' =>   "Skriv ett kreativt och övertygande bud på följande projekt:\n\n ##description##"],

            ['id' => 3634, 'template_id' => 99, 'key' => "tr-TR", 'value' =>   "Aşağıdaki proje için yaratıcı ve ikna edici teklif yazın:\n\n ##description##"],

            ['id' => 3635, 'template_id' => 99, 'key' => "pt-BR", 'value' =>   "Escreva uma proposta criativa e convincente para o seguinte projeto:\n\n ##description##"],

            ['id' => 3636, 'template_id' => 99, 'key' => "ro-RO", 'value' =>   "Scrieți o ofertă creativă și convingătoare pentru următorul proiect:\n\n ##description##"],

            ['id' => 3637, 'template_id' => 99, 'key' => "vi-VN", 'value' =>   "Viết giá thầu sáng tạo & thuyết phục cho dự án sau:\n\n ##description##"],

            ['id' => 3638, 'template_id' => 99, 'key' => "sw-KE", 'value' =>   "Andika zabuni ya ubunifu na ya kushawishi kwa mradi ufuatao:\n\n ##description##"],

            ['id' => 3639, 'template_id' => 99, 'key' => "sl-SI", 'value' =>   "Napišite kreativno in prepričljivo ponudbo za naslednji projekt:\n\n ##description##"],

            ['id' => 3640, 'template_id' => 99, 'key' => "th-TH", 'value' =>   "เขียนการเสนอราคาที่สร้างสรรค์และน่าเชื่อถือสำหรับโครงการต่อไปนี้:\n\n ##description##"],

            ['id' => 3641, 'template_id' => 99, 'key' => "uk-UA", 'value' =>   "Напишіть творчу та переконливу заявку на наступний проект:\n\n ##description##"],

            ['id' => 3642, 'template_id' => 99, 'key' => "lt-LT", 'value' =>   "Parašykite kūrybišką ir įtikinamą pasiūlymą kitam projektui:\n\n ##description##"],

            ['id' => 3643, 'template_id' => 99, 'key' => "bg-BG", 'value' =>   "Напишете креативна и убедителна оферта за следния проект:\n\n ##description##"],

            ['id' => 3644, 'template_id' => 19, 'key' => "he-IL", 'value' => "כתוב מודעה יצירתית עבור המוצר הבא שיוצג בפייסבוק שמטרתה:\n\n ##audience## \n\n שם המוצר:\n ##title## \n\n תיאור המוצר:\n ##description## \n\n גוון הקול של המודעה חייב להיות:\n ##tone_language## \n\n"],

        ];
        foreach ($prompts as $prompt) {
            AiTemplatePrompt::updateOrCreate(['id' => $prompt['id']], $prompt);
        }
        // $this->call("OthersTableSeeder");
    }
}
