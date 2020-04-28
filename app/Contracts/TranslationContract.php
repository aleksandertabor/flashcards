<?php

namespace App\Contracts;

interface TranslationContract
{
    public function translate(string $toTranslate, array $languages) : string;

    public function languages() : array;
}
