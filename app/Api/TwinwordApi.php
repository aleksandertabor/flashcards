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

        // if source lang is not english

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

        if (! is_null($targetLanguage) && $targetLanguage !== 'en') {
            $exampleTargetLanguage = TranslationFacade::translate($exampleEnglish, [
                'source' => 'en',
                'target' => $targetLanguage,
            ])['text'];
        } else {
            $exampleTargetLanguage = $exampleEnglish;
        }
        if ($wasTranslated) {
            $exampleSourceLanguage = TranslationFacade::translate($exampleEnglish, [
                'source' => 'en',
                'target' => $sourceLanguage,
            ])['text'];

            return [$exampleSourceLanguage, $exampleTargetLanguage];
        } else {
            $exampleSourceLanguage = $exampleEnglish;

            return [$exampleSourceLanguage, $exampleTargetLanguage];
        }
    }
}
