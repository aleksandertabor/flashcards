<?php
namespace App\GraphQL\Resolvers;

use App\Exceptions\InvalidCredentialsException;
use App\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
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

        if (Arr::has($credentials, 'email')) {
            $credentials['username'] = $credentials['email'];
        }

        $credentials['grant_type'] = 'password';
        $credentials['client_id'] = env("PASSPORT_CLIENT_ID");
        $credentials['client_secret'] = env("PASSPORT_CLIENT_SECRET");

        $request = Request::create('api/token', 'POST', $credentials, [], [], [
            'HTTP_Accept' => 'application/json',
        ]);
        $response = app()->handle($request);
        $decodedResponse = json_decode($response->getContent(), true);

        if ($response->getStatusCode() === 200) {
            Cookie::queue('_refresh_token', $decodedResponse['refresh_token'], 1800, '/', $context->request->getHost(), false, true);
            return $decodedResponse;
        }

        throw new InvalidCredentialsException(
            'Check your password or connection.',
            'Wrong password or server problems.'
        );

        // throw new CredentialsException(
        //     'Check your password or connection.',
        //     'Wrong password or server problems.'
        // );

// if (Auth::attempt($credentials)) {
        // }

        // $user = Auth::user();
        // $token = $user->createToken('FlashcardsUserToken')->accessToken;
        // $refreshToken = $user->createRefreshToken('FlashcardsUserToken')->accessToken;
        // Cookie::queue('_token', $token, 1800, '/', $context->request->getHost(), false, true);
        // Cookie::queue('_refreshToken', $refreshToken, 1800, '/', $context->request->getHost(), false, true);
        // return true;
    }

    public function register($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        $token = $user->createToken('FlashcardsUserToken')->accessToken;
        Cookie::queue('_token', $token, 1800, '/', $context->request->getHost(), false, true);

        return true;
    }

    public function refresh_token($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $credentials['grant_type'] = 'refresh_token';
        $credentials['client_id'] = env("PASSPORT_CLIENT_ID");
        $credentials['client_secret'] = env("PASSPORT_CLIENT_SECRET");
        $credentials['scope'] = '';
        $credentials['refresh_token'] = Cookie::get('_refresh_token');

        $request = Request::create('api/token', 'POST', $credentials, [], [], [
            'HTTP_Accept' => 'application/json',
        ]);
        $response = app()->handle($request);
        $decodedResponse = json_decode($response->getContent(), true);

        if ($response->getStatusCode() === 200) {
            Cookie::queue('_refresh_token', $decodedResponse['refresh_token'], 1800, '/', $context->request->getHost(), false, true);
            return $decodedResponse;
        }

        throw new AuthenticationException($decodedResponse['message']);

    }

    public function logout($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        if (!Auth::guard('api')->check()) {
            throw new AuthenticationException("Not Authenticated");
        }

        Auth::guard('api')->user()->token()->revoke();
        Cookie::queue(Cookie::forget('_refresh_token'));

        return [
            'status' => 'LOGOUT',
            'message' => 'Logout.',
        ];

    }
}
