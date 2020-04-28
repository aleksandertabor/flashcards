<?php

namespace App\GraphQL\Mutations\Users;

use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class EditProfileMutator
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
            'username' => ['sometimes', 'required', 'string', 'min:3', 'alpha_num', Rule::unique('users', 'username')->ignore($args['id'])],
            'email' => ['sometimes', 'required', 'string', 'email', Rule::unique('users', 'email')->ignore($args['id'])],
            'password' => ['nullable', 'min:6', 'confirmed'],
            'password_confirmation' => ['same:password'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user = User::findOrFail($args['id']);

        $args = array_filter($args, function ($value) {
            return ! is_null($value) && $value !== '';
        });

        $user->update($args);

        return $user;
    }
}
