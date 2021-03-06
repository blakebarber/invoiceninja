<?php

use App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run()
    {
        Eloquent::unguard();

        $languages = [
            ['name' => 'English', 'locale' => 'en'],
            ['name' => 'Italian', 'locale' => 'it'],
            ['name' => 'German', 'locale' => 'de'],
            ['name' => 'French', 'locale' => 'fr'],
            ['name' => 'Brazilian Portuguese', 'locale' => 'pt_BR'],
            ['name' => 'Dutch', 'locale' => 'nl'],
            ['name' => 'Spanish', 'locale' => 'es'],
            ['name' => 'Norwegian', 'locale' => 'nb_NO'],
            ['name' => 'Danish', 'locale' => 'da'],
            ['name' => 'Japanese', 'locale' => 'ja'],
            ['name' => 'Swedish', 'locale' => 'sv'],
            ['name' => 'Spanish - Spain', 'locale' => 'es_ES'],
            ['name' => 'French - Canada', 'locale' => 'fr_CA'],
            ['name' => 'Lithuanian', 'locale' => 'lt'],  
            ['name' => 'Polish', 'locale' => 'pl'],
            ['name' => 'Czech', 'locale' => 'cs'],
        ];

        foreach ($languages as $language) {
            $record = Language::whereLocale($language['locale'])->first();
            if ($record) {
                $record->name = $language['name'];
                $record->save();
            } else {
                Language::create($language);
            }
        }

        Eloquent::reguard();
    }
}
