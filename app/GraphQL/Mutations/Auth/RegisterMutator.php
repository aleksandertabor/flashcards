<?php

namespace App\GraphQL\Mutations\Auth;

use App\Exceptions\InvalidCredentialsException;
use App\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RegisterMutator
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:6', 'same:password'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Create username from email
        $username = Str::limit(Str::before($args['email'], '@'), 50, '');

        $usernameSize = mb_strlen($username);

        // If username is too short cause of short email, set to 3 characters
        if ($usernameSize < 3) {
            $username .= Str::random(3);
        }

        // Generate an unique username
        $username = SlugService::createSlug(User::class, 'username', $username);

        $input['email'] = $args['email'];
        $input['password'] = bcrypt($args['password']);
        $input['username'] = $username;

        $newUser = User::create($input);

        if (Auth::loginUsingId($newUser->id)) {
            Log::channel('app')->info("{$newUser->username} registered.");

            return $newUser;
        }

        throw new InvalidCredentialsException(
            'Check your connection.',
            'Wrong credentials or server problems.'
        );
    }
}
