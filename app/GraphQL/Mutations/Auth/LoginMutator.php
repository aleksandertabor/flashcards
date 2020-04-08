<?php

namespace App\GraphQL\Mutations\Auth;

use App\Exceptions\InvalidCredentialsException;
use App\Traits\PassportTokenTrait;
use App\User;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class LoginMutator
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
        $login = $args['login'];
        $loginType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $loginType => $login,
            'password' => $args['password'],
        ];
        $validator = Validator::make($credentials, [
            'username' => ['required_without:email', 'string', 'nullable', 'exists:users'],
            'email' => ['required_without:username', 'string', 'nullable', 'exists:users'],
            'password' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        if (Arr::has($credentials, 'email')) {
            $credentials['username'] = $credentials['email'];
        }

        $response = $this->issueToken($credentials, 'password');

        $decodedResponse = json_decode($response->getContent(), true);

        if ($response->getStatusCode() === 200) {
            $dt = Carbon::now();
            $decodedResponse['expires_in'] = $dt->seconds($decodedResponse['expires_in'])->toDateTimeString();
            Cookie::queue('_refresh_token', $decodedResponse['refresh_token'], $dt->diffInMinutes($dt->copy()->addDays(30)), '/', $context->request->getHost(), false, true);
            $user = User::where($loginType, $credentials['username'])->firstOrFail();
            $decodedResponse['user'] = $user;

            return $decodedResponse;
        }
        throw new InvalidCredentialsException(
            'Check your password or connection.',
            'Wrong password or server problems.'
        );
    }
}
