<?php

namespace App\GraphQL\Types;

use App\Card;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CardType
{
    public function image(Card $card, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        $url = $card->getFirstMediaUrl('main');

        return $url;
    }
}
