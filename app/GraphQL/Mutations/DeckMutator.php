<?php

namespace App\GraphQL\Mutations;

use App\Deck;
use Exception;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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

        // dump($args);

        $validator = Validator::make($args, [
            'title' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:320'],
            'lang_source_id' => ['required', 'integer', 'exists:languages,id'],
            'lang_target_id' => ['required', 'integer', 'exists:languages,id'],
            'image' => ['string', 'nullable'],
            'image_file' => ['image', 'nullable'],
            'visibility' => ['required', 'string', Rule::in(Deck::visibilityNames())],
            'cards' => ['array', 'max:50'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user = Auth::guard('api')->user();

        $deck = $user->decks()->create($args);

        if ($args['image_file']) {
            try {
                $deck->addMedia($args['image_file'])->toMediaCollection('main');
            } catch (Exception $e) {
                $error = ValidationException::withMessages([
                        'image' => ['Try upload other image.'],
                     ]);
                throw $error;
            }
        } elseif ($args['image']) {
            try {
                $deck->addMediaFromUrl($args['image'])->toMediaCollection('main');
            } catch (Exception $e) {
                $error = ValidationException::withMessages([
                        'image' => ['Try add other image URL.'],
                     ]);
                throw $error;
            }
        }

        // dump($args['cards']);

        $args['cards'] = array_filter($args['cards'], function ($card) {
            return count($card) !== 0;
        });

        // dump($args['cards']);

        $this->createDeckCards($deck, $args);

        // if ($args['visibility'] === key(Deck::UNLISTED_VISIBILITY)) {
        //     $deck->slug = Crypt::encryptString($args['title']);
        //     // dump($deck->slug);
        //     $deck->save();
        // }

        return $deck;
    }

    public function createDeckCards(Deck $root, array $args) : void
    {
        foreach ($args['cards'] as $card) {
            $validator = Validator::make($card, [
                'question' => ['string', 'max:255'],
                'answer' => ['string', 'max:255'],
                'example_question' => ['string', 'max:255'],
                'example_answer' => ['string', 'max:255'],
                'image' => ['string', 'nullable'],
                'image_file' => ['image', 'nullable'],
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $cardImages = [
                'file' => $card['image_file'] ?? null,
                'url' => $card['image'] ?? null,
            ];
            $card = $root->cards()->create($card);
            if ($cardImages['file']) {
                try {
                    $card->addMedia($cardImages['file'])->toMediaCollection('main');
                } catch (Exception $e) {
                    $error = ValidationException::withMessages([
                            'image' => ['Try upload other image.'],
                         ]);
                    throw $error;
                }
            } elseif ($cardImages['url']) {
                try {
                    $card->addMediaFromUrl($cardImages['url'])->toMediaCollection('main');
                } catch (Exception $e) {
                    $error = ValidationException::withMessages([
                            'image' => ['Try add other image URL.'],
                         ]);
                    throw $error;
                }
            }
        }
    }
}
