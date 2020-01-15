<?php

namespace App\Contracts;

interface ImageContract
{
    public function random(string $imageToFind, string $sourceLanguage = null) : string;
}
