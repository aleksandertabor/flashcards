<?php

namespace App\Api;

use App\Facades\TranslationFacade;
use GuzzleHttp\Client;

class TwinwordApi
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function example(string $wordToFind, string $sourceLanguage, string $targetLanguage) : array
    {
        $wasTranslated = false;

        if (! is_null($sourceLanguage) && $sourceLanguage !== 'en') {
            $wordToFind = TranslationFacade::translate($wordToFind, [
                'source' => $sourceLanguage,
                'target' => 'en',
            ])['text'];
            $wasTranslated = true;
        }

        // find example for word
        $output = $this->client->request('GET', "example/?entry={$wordToFind}")->getBody()->getContents();

        $results = json_decode($output);

        if (isset($results->example)) {
            $exampleEnglish = collect($results->example)->random();
        } else {
            return [];
        }

        $exampleTargetLanguage = TranslationFacade::translate($exampleEnglish, [
            'source' => 'en',
            'target' => $targetLanguage,
        ])['text'];

        if ($wasTranslated) {
            $exampleSourceLanguage = TranslationFacade::translate($exampleEnglish, [
                'source' => 'en',
                'target' => $sourceLanguage,
            ])['text'];

            return [$exampleSourceLanguage, $exampleTargetLanguage];
        } else {
            return [$exampleSourceLanguage, $exampleTargetLanguage];
        }
    }
}
