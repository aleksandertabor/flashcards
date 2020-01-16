<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ExampleFacade extends Facade
{
    /**
     * A Facade to get images.
     * @method static array example(string $wordToFind, string $sourceLanguage, string $targetLanguage)
     */
    public static function getFacadeAccessor()
    {
        return "App\Contracts\ExampleContract";
    }
}
