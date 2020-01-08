<?php

use App\Api\GoogleTranslationApi;
use App\Language;
use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $translator = new GoogleTranslationApi();

        $languages = $translator->languages();

        foreach ($languages as $lang) {
            Language::create([
                'locale' => $lang['locale'],
                'name' => $lang['name'],
            ]);
        }
    }
}
