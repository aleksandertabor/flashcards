<?php

namespace App\GraphQL\Mutations;

use App\Deck;
use Exception;
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
    public function updateDeck($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) : Deck
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

        $deck = Deck::withoutGlobalScopes()->findOrFail($args['id']);

        $deck->update($args);

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
                $url = '';
                $media = $deck->getFirstMedia('main');
                if ($media) {
                    $url = $media->getFullUrl();
                }
                if ($args['image'] !== $url) {
                    $deck->addMediaFromUrl($args['image'])->toMediaCollection('main');
                }
            } catch (Exception $e) {
                $error = ValidationException::withMessages([
                        'image' => ['Try add other image URL.'],
                     ]);
                throw $error;
            }
        }

        $args['cards'] = array_filter($args['cards'], function ($card) {
            return count($card) !== 0;
        });

        $this->updateDeckCards($deck, $args);

        $deck->cards()->whereIn('id', $args['cardsForDelete'])->delete();

        return $deck;
    }

    public function updateDeckCards(Deck $root, array $args) : void
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
            $card = $root->cards()->updateOrCreate(['id' => $card['id'] ?? null],
        $card);
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
                    $url = '';
                    $media = $card->getFirstMedia('main');
                    if ($media) {
                        $url = $media->getFullUrl();
                    }
                    if ($cardImages['url'] !== $url) {
                        $card->addMediaFromUrl($cardImages['url'])->toMediaCollection('main');
                    }
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
