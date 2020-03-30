<?php

namespace App\Api;

use App\Contracts\ImageContract;
use App\Facades\TranslationFacade;
use GuzzleHttp\Client;

class WikipediaApi implements ImageContract
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function random(string $imageToFind, string $sourceLanguage = null) : string
    {
        if (! is_null($sourceLanguage) && $sourceLanguage !== 'en') {
            $imageToFind = TranslationFacade::translate($imageToFind, [
                'source' => $sourceLanguage,
                'target' => 'en',
            ])['text'];
        }

        $output = $this->client->request('GET', "media-list/{$imageToFind}")->getBody()->getContents();

        $decodedOutput = json_decode($output);

        if ($decodedOutput->items) {
            $results = collect($decodedOutput->items)->filter(function ($item) {
                return $item->type === 'image' && $item->showInGallery === true;
            });

            $url = $results->random()->srcset[0]->src;

            if (! preg_match('~^(?:f|ht)tps?://~i', $url)) {
                $url = 'https:'.$url;
            }

            return $url;
        }

        return '';
    }
}
