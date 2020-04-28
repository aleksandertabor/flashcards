<?php

namespace App\Api;

use App\Contracts\TranslationContract;
use Google\Cloud\Translate\V2\TranslateClient;
use Illuminate\Support\Facades\Log;
use Locale;

class GoogleTranslationApi implements TranslationContract
{
    private $client;

    public function __construct(TranslateClient $client)
    {
        $this->client = $client;
    }

    public function translate(string $toTranslate, array $languages) : string
    {
        try {
            $result = $this->client->translate($toTranslate, [
                'source' => $languages[0],
                'target' => $languages[1],
            ]);
        } catch (\Throwable $e) {
            return '';
        }

        Log::channel('app')->info("Calling to Google Translation API - {$toTranslate}.");

        $result = html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8');

        return $result ? $result : '';
    }

    public function languages() : array
    {
        $result = collect($this->client->languages())->map(function ($lang) {
            return [
            'locale' => $lang,
            'name' => Locale::getDisplayLanguage($lang, 'en'),
        ];
        })->toArray();

        return $result ? $result : [];
    }
}
