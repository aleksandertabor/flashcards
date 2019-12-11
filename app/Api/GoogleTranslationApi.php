<?php
namespace App\Api;

use Google\Cloud\Translate\V2\TranslateClient;

class GoogleTranslationApi
{

    protected $client;

    public function __construct()
    {
        $this->client = new TranslateClient([
            'keyFilePath' => base_path(env('GOOGLE_API_CREDENTIALS')),
        ]);
    }

    public function translate(string $toTranslate, array $languages): array
    {

        $result = $this->client->translate($toTranslate, [
            'source' => $languages['source'],
            'target' => $languages['target'],
        ]);

        return $result ? $result : [];
    }

    public function detect(string $toDetect): array
    {
        $result = $this->client->detectLanguage($toDetect);

        return $result ? $result : [];
    }

}
