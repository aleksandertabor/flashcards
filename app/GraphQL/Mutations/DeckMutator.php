<?php

namespace App\GraphQL\Mutations;

use App\Deck;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Exception;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Exceptions\ValidationException;
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
    public function createDeck($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) : Deck
    {
        $validator = Validator::make($args, [
            'title' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:320'],
            'lang_source_id' => ['required', 'integer', 'exists:languages,id'],
            'lang_target_id' => ['required', 'integer', 'exists:languages,id'],
            'image' => ['string', 'nullable'],
            'visibility' => ['required', 'string', Rule::in(Deck::visibilityNames())],
            'cards' => ['array'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user = Auth::guard('api')->user();

        // $args['slug'] = SlugService::createSlug(Deck::class, 'slug', $args['title']);

        $deck = $user->decks()->create($args);

        if ($args['image']) {
            if (preg_match('/data:image/', $args['image'])) {
                try {
                    $deck->addMediaFromBase64($args['image'])->toMediaCollection('main');
                } catch (Exception $e) {
                    $error = ValidationException::withMessages([
                        'image' => ['Try upload other image.'],
                     ]);
                    throw $error;
                }
            } else {
                try {
                    $deck->addMediaFromUrl($args['image'])->toMediaCollection('main');
                } catch (Exception $e) {
                    $error = ValidationException::withMessages([
                        'image' => ['Try add other image URL.'],
                     ]);
                    throw $error;
                }
            }
        }

        $this->createDeckCards($deck, $args);

        return $deck;
    }

    public function createDeckCards(Deck $root, array $args) : void
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
