<?php

namespace App\GraphQL\Mutations;

use App\Card;
use Exception;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Validator;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\UnreachableUrl;

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
            'image_file' => ['image', 'nullable'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $card = Card::findOrFail($args['id']);

        $card->update($args);

        if ($args['image_file']) {
            try {
                $card->addMedia($args['image_file'])->toMediaCollection('main');
            } catch (Exception $e) {
                $error = ValidationException::withMessages([
                        'image' => ['Try upload other image.'],
                     ]);
                throw $error;
            }
        } elseif ($args['image']) {
            try {
                $url = '';
                $media = $card->getFirstMedia('main');
                if ($media) {
                    $url = $media->getFullUrl();
                }
                if ($args['image'] !== $url) {
                    $card->addMediaFromUrl($args['image'])->toMediaCollection('main');
                }
            } catch (Exception $e) {
                if ($e instanceof FileIsTooBig) {
                    $error = ValidationException::withMessages([
                        'image' => [preg_replace("/\`[^)]+\`/", '', $e->getMessage())],
                     ]);
                    throw $error;
                }
                if ($e instanceof UnreachableUrl) {
                    $error = ValidationException::withMessages([
                        'image' => [preg_replace("/\`[^)]+\`/", '', $e->getMessage())],
                     ]);
                    throw $error;
                }
                $error = ValidationException::withMessages([
                        'image' => [$e->getMessage()],
                     ]);
                throw $error;
            }
        } else {
            $media = $card->getFirstMedia('main');
            if ($media) {
                $media->delete();
            }
        }

        return $card;
    }
}
