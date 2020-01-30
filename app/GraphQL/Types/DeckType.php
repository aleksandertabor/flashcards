<?php

namespace App\GraphQL\Types;

use App\Deck;
use App\GraphQL\WithCountLoader;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class DeckType
{
    /**
     * Resolve comment count.
     *
     * @param Deck           $deck
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $info
     *
     * @return \GraphQL\Deferred
     */
    public function cardsCount(Deck $deck, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        $dataloader = BatchLoader::instance(
            WithCountLoader::class,
            $info->path,
            [
                'builder' => \App\Deck::query(),
                'relation' => 'cards',
            ]
        );

        return $dataloader->load(
            $deck->getKey(),
            ['parent' => $deck]
        );
    }

    public function image(Deck $deck, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        $url = '';

        $media = $deck->getFirstMedia('main');

        if ($media) {
            $url = $media->getFullUrl();
        }

        return $url;
    }
}
