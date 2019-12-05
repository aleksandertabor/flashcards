<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        } else {
            $credentials = $request->validate([
                'email' => 'required|string',
                'password' => 'required|string',
            ]);
        }

        if (Auth::attempt($credentials)) {
            return response()->json(['access_token' => Auth::user()->createToken('FlashcardsUserToken')->accessToken,
            ]);
        }

        return response()->json(['errors' => ['login' => [0 => 'Unauthorized']]], 401);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $success['token'] = $user->createToken('FlashcardsUserToken')->accessToken;

        return response()->json(['access_token' => $user->createToken('MyApp')->accessToken]);
    }

    public function logout(Request $request)
    {
        // auth()->user()->tokens->each(function ($token, $key) {
        //     $token->delete();
        // });
        // auth()->user()->token()->revoke();

        // $value = $request->bearerToken();

        // $id = (new Parser())->parse($value)->getHeader('jti');
        // dd($id);
        // $token = $request->user()->tokens->find($id);
        // $token->revoke();
        // dd($token);

        // dd($request->user('api'));

        $request->user('api')->token()->revoke();

//         auth()->user()->tokens->each(function ($token, $key) {
        //     $token->delete();
        // });

        return response()->json('Logged out', 200);

    }
}
