<?php

namespace App\Contracts;

interface ExampleContract
{
    public function example(string $wordToFind, string $sourceLanguage, string $targetLanguage) : array;
}
