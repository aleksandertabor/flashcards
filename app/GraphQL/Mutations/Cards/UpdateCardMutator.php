<?php

namespace App\GraphQL\Mutations\Cards;

use App\Card;
use App\GraphQL\UploadMedia;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Validator;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateCardMutator
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
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) : Card
    {
        $validator = Validator::make($args, [
            'deck_id' => ['required', 'integer', 'exists:decks,id'],
            'question' => ['string', 'max:255', 'nullable'],
            'answer' => ['string', 'max:255', 'nullable'],
            'example_question' => ['string', 'max:255', 'nullable'],
            'example_answer' => ['string', 'max:255', 'nullable'],
            'image' => ['string', 'nullable'],
            'image_file' => ['image', 'nullable', 'max:2048', 'mimes:jpeg,webp,png'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $card = Card::findOrFail($args['id']);

        $card->fill($args);

        if ($args['image_file']) {
            UploadMedia::uploadImageFromFile($args['image_file'], 'image_file', $card, 'main');
        } elseif ($args['image']) {
            UploadMedia::uploadImageFromUrl($args['image'], 'image', $card, 'main');
        } else {
            $card->clearMediaCollection('main');
        }

        $card->save();

        return $card;
    }
}
