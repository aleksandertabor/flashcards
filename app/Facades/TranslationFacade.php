<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TranslationFacade extends Facade
{
    /**
     * A Facade to translation.
     * @method static string translate(string $toTranslate, array $languages)
     * @method static array languages()
     *
     * @see \App\Contracts\TranslationContract
     */
    public static function getFacadeAccessor()
    {
        return "App\Contracts\TranslationContract";
    }
}
