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
        $languageSource = $this->command->choice('How to fill your languages table? Seeder is default. GoogleTranslationApi requires valid GOOGLE_API_CREDENTIALS in environment variables.', ['Seeder', 'GoogleTranslationApi'], 0);

        if ($languageSource === 'Seeder') {
            $languages = [
                [
                    'locale' => 'en',
                    'name' => 'English',
                ],
                [
                    'locale' => 'pl',
                    'name' => 'Polish',
                ],
            ];
        } else {
            $languages = TranslationFacade::languages();
        }

        $this->command->table(['locale', 'name'], $languages);

        foreach ($languages as $lang) {
            Language::create([
                'locale' => $lang['locale'],
                'name' => $lang['name'],
            ]);
        }
    }
}
