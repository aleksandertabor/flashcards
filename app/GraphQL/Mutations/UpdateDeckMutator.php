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
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) : Deck
    {
        $validator = Validator::make($args, [
            'title' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:320'],
            'lang_source_id' => ['required', 'integer', 'exists:languages,id'],
            'lang_target_id' => ['required', 'integer', 'exists:languages,id'],
            'image' => ['string', 'nullable'],
            'image_file' => ['image', 'nullable'],
            'visibility' => ['required', 'string', Rule::in(Deck::visibilityNames())],
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
        } else {
            $media = $deck->getFirstMedia('main');
            if ($media) {
                $media->delete();
            }
        }

        return $deck;
    }
}
