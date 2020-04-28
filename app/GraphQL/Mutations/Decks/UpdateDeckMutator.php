<?php

namespace App\GraphQL\Mutations\Decks;

use App\Deck;
use App\GraphQL\UploadMedia;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateDeckMutator
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
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) : Deck
    {
        $validator = Validator::make($args, [
            'id' => ['required', 'exists:decks,id'],
            'title' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:320'],
            'lang_source_id' => ['required', 'integer', 'exists:languages,id'],
            'lang_target_id' => ['required', 'integer', 'exists:languages,id'],
            'image' => ['string', 'nullable'],
            'image_file' => ['image', 'nullable', 'max:2048', 'mimes:jpeg,webp,png'],
            'visibility' => ['required', 'string', Rule::in(Deck::visibilityNames())],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $deck = Deck::withoutGlobalScopes()->findOrFail($args['id']);

        $deck->fill($args);

        if ($args['image_file']) {
            UploadMedia::uploadImageFromFile($args['image_file'], 'image_file', $deck, 'main');
        } elseif ($args['image']) {
            UploadMedia::uploadImageFromUrl($args['image'], 'image', $deck, 'main');
        } else {
            $deck->clearMediaCollection('main');
        }

        $deck->save();

        return $deck;
    }
}
