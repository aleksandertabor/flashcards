<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ImageFacade extends Facade
{
    /**
     * A Facade to get images.
     * @method static string random(string $imageToFind, string $sourceLanguage = null)
     *
     * @see \App\Contracts\ImageContract
     */
    public static function getFacadeAccessor()
    {
        return "App\Contracts\ImageContract";
    }
}
