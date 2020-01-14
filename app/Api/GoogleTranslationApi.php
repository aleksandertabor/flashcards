<?php

namespace App\Api;

use App\Contracts\TranslationContract;
use Google\Cloud\Translate\V2\TranslateClient;
use Locale;

class GoogleTranslationApi implements TranslationContract
{
    private $client;

    public function __construct(TranslateClient $client)
    {
        $this->client = $client;
    }

    public function translate(string $toTranslate, array $languages) : array
    {
        $result = $this->client->translate($toTranslate, [
            'source' => $languages['source'],
            'target' => $languages['target'],
        ]);

        $result['text'] = html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8');

        return $result ? $result : [];
    }

    public function detect(string $toDetect) : array
    {
        $result = $this->client->detectLanguage($toDetect);

        return $result ? $result : [];
    }

    public function languages() : array
    {
        $result = collect($this->client->languages())->map(fn ($lang) => [
            'locale' => $lang,
            'name' => Locale::getDisplayLanguage($lang, 'en'),
        ])->toArray();

        return $result ? $result : [];
    }
}
