<?php

use App\Facades\TranslationFacade;
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
        $languages = TranslationFacade::languages();

        foreach ($languages as $lang) {
            Language::create([
                'locale' => $lang['locale'],
                'name' => $lang['name'],
            ]);
        }
    }
}
