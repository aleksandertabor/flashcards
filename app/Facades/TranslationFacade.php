<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TranslationFacade extends Facade
{
    /**
     * A Facade to translation.
     * @method static array translate(string $toTranslate, array $languages)
     * @method static array detect(string $toDetect)
     * @method static array languages()
     */
    public static function getFacadeAccessor()
    {
        return "App\Contracts\TranslationContract";
    }
}
