<?php

namespace App\GraphQL\Enums;

use App\Deck;
use GraphQL\Type\Definition\EnumType;

final class VisibilityEnum
{
    public static function get()
    {
        $visibilities = collect(Deck::visibilities());

        $values = $visibilities->flatMap(function ($options) {
            return [
            strtoupper(array_key_first($options)) => [
                'value' => array_key_first($options),
                'description' => $options[array_key_first($options)],
            ],
        ];
        })->toArray();

        $visibilityEnum = new EnumType([
            'name' => 'Visibility',
            'description' => 'Deck visibility options',
            'values' => $values,
        ]);

        return $visibilityEnum;
    }
}
