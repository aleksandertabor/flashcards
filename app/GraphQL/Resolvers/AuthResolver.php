<?php
namespace App\GraphQL\Resolvers;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\RefreshTokenException;
use App\User;
use Carbon\Carbon;
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
        // Login part
        $credentials = [
            'username' => $input['username'],
            'password' => $args['password'],
        ];
        $credentials['grant_type'] = 'password';
        $credentials['client_id'] = env("PASSPORT_CLIENT_ID");
        $credentials['client_secret'] = env("PASSPORT_CLIENT_SECRET");
        $request = Request::create('api/token', 'POST', $credentials, [], [], [
            'HTTP_Accept' => 'application/json',
        ]);
        $response = app()->handle($request);
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
            $dt = Carbon::now();
            $decodedResponse['expires_in'] = $dt->seconds($decodedResponse['expires_in'])->toDateTimeString();
            Cookie::queue('_refresh_token', $decodedResponse['refresh_token'], $dt->diffInMinutes($dt->copy()->addDays(30)), '/', $context->request->getHost(), false, true);
            return $decodedResponse;
        }
        throw new RefreshTokenException('So you are guest.', $decodedResponse['message']);
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
