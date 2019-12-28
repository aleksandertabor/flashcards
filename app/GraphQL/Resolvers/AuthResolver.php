<?php
namespace App\GraphQL\Resolvers;

use App\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AuthResolver
{
    /**
     * @param $rootValue
     * @param array $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null $context
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     * @return array
     * @throws \Exception
     */
    public function login($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        // $login = $args['username'] ?? $args['email'];
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

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('FlashcardsUserToken')->accessToken;
            Cookie::queue('_token', $token, 1800, '/', $context->request->getHost(), false, true);
            Cookie::queue(Cookie::make('name', 'value', 15));
            return true;
        }

        throw new AuthenticationException('Authentication Failed');
    }

    public function register($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

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
        $token = $user->createToken('FlashcardsUserToken')->accessToken;
        Cookie::queue('_token', $token, 1800, '/', $context->request->getHost(), false, true);

        return true;
    }

    public function logout($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        Auth::guard('api')->user()->token()->revoke();
        Cookie::forget('_token');

        return [
            'status' => 'LOGOUT',
            'message' => 'Logout.',
        ];

    }
}
