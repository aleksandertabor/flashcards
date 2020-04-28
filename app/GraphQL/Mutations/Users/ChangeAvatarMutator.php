<?php

namespace App\GraphQL\Mutations\Users;

use App\GraphQL\UploadMedia;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Validator;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ChangeAvatarMutator
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $validator = Validator::make($args, [
            'id' => ['required', 'exists:users,id'],
            'image' => ['string', 'nullable'],
            'image_file' => ['image', 'nullable', 'sometimes', 'max:2048', 'mimes:jpeg,webp,png'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user = User::findOrFail($args['id']);

        if ($imageFile = $args['image_file'] ?? null) {
            UploadMedia::uploadImageFromFile($imageFile, 'image', $user, 'main');
        } elseif ($imageCamera = $args['image'] ?? null) {
            UploadMedia::uploadImageFromBase64($imageCamera, 'image', $user, 'main');
        } elseif (! $imageCamera) {
            $user->clearMediaCollection('main');
        }

        $user->save();

        return $user;
    }
}
