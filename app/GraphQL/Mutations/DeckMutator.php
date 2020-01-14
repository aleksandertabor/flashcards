<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class DeckMutator
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared b ,etween all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function createDeck($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) : \App\Deck
    {
        // $validator = Validator::make($args, [
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'password' => ['required', 'string', 'min:6', 'confirmed'],
        //     'password_confirmation' => ['required', 'string', 'min:6'],
        // ]);
        // if ($validator->fails()) {
        //     throw new ValidationException($validator);
        // }
        $user = Auth::guard('api')->user();
        $deck = $user->decks()->create($args);
        if (preg_match('/data:image/', $args['image'])) {
            $deck->addMediaFromBase64($args['image'])->toMediaCollection('main');
        } else {
            $deck->addMediaFromUrl($args['image'])->toMediaCollection('main');
        }
        $this->createDeckCards($deck, $args);

        return $deck;
    }

    public function createDeckCards(\App\Deck $root, array $args) : void
    {
        foreach ($args['cards'] as $card) {
            $image = $card['image'];
            $card = $root->cards()->create($card);
            if (preg_match('/data:image/', $args['image'])) {
                $card->addMediaFromBase64($image)->toMediaCollection('main');
            } else {
                $card->addMediaFromUrl($image)->toMediaCollection('main');
            }
        }
    }
}
