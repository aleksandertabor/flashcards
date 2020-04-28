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

        // If source language is not English, then translate word to English

        if (! is_null($sourceLanguage) && $sourceLanguage !== 'en') {
            $wordToFind = TranslationFacade::translate($wordToFind, [
                $sourceLanguage,
                'en',
            ]) ?? [];

            if (! $wordToFind) {
                return [];
            }

            $wasTranslated = true;
        }

        // Find example for English word
        try {
            $output = $this->client->request('GET', "example/?entry={$wordToFind}")->getBody()->getContents();
        } catch (\Throwable $e) {
            return [];
        }

        $results = json_decode($output);

        // Get random example
        if (isset($results->example)) {
            $exampleEnglish = collect($results->example)->random();
        } else {
            return [];
        }

        // If target language is not English, then translate target example to target language

        if (! is_null($targetLanguage) && $targetLanguage !== 'en') {
            $exampleTargetLanguage = TranslationFacade::translate($exampleEnglish, [
                'en',
                $targetLanguage,
            ]) ?? '';
        } else {
            $exampleTargetLanguage = $exampleEnglish;
        }

        // If word was not English, then translate source example to source language

        if ($wasTranslated) {
            $exampleSourceLanguage = TranslationFacade::translate($exampleEnglish, [
                'en',
                $sourceLanguage,
            ]) ?? '';

            return [$exampleSourceLanguage, $exampleTargetLanguage];
        } else {
            $exampleSourceLanguage = $exampleEnglish;

            return [$exampleSourceLanguage, $exampleTargetLanguage];
        }
    }
}
