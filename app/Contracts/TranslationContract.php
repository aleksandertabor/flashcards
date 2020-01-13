<?php

namespace App\Contracts;

interface TranslationContract
{
    public function translate(string $toTranslate, array $languages) : array;

    public function detect(string $toDetect) : array;

    public function languages() : array;
}
