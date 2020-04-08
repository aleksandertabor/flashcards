<?php

namespace App\GraphQL\Mutations\Auth;

use App\Exceptions\InvalidCredentialsException;
use App\Traits\PassportTokenTrait;
use App\User;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RegisterMutator
{
    use PassportTokenTrait;

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
            'password_confirmation' => ['required', 'string', 'min:6'],
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        $input['email'] = $args['email'];
        $input['password'] = bcrypt($args['password']);
        $username = Str::limit(Str::before($args['email'], '@'), 50, '');
        $usernameSize = mb_strlen($username);
        if ($usernameSize < 3) {
            $username .= Str::random(3);
        }
        $username = SlugService::createSlug(User::class, 'username', $username);
        $input['username'] = $username;
        $user = User::create($input);

        $credentials = [
            'username' => $input['username'],
            'password' => $args['password'],
        ];

        $response = $this->issueToken($credentials, 'password');

        $decodedResponse = json_decode($response->getContent(), true);

        if ($response->getStatusCode() === 200) {
            $dt = Carbon::now();
            $decodedResponse['expires_in'] = $dt->seconds($decodedResponse['expires_in'])->toDateTimeString();
            Cookie::queue('_refresh_token', $decodedResponse['refresh_token'], $dt->diffInMinutes($dt->copy()->addDays(30)), '/', $context->request->getHost(), false, true);
            $decodedResponse['user'] = $user;

            return $decodedResponse;
        }
        throw new InvalidCredentialsException(
            'Check your connection.',
            'Wrong credentials or server problems.'
        );
    }
}
