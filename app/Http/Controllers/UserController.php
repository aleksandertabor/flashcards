<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserEditResource;
use App\Http\Resources\UserShowResource;
use App\User;
use Barryvdh\Debugbar\Facade as Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(User $user, Request $request)
    {

        Debugbar::info('test');

        return new UserShowResource($user->loadCount('cards'));
    }

    public function edit(User $user)
    {

        return new UserEditResource($user);
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()->id !== $user->user_id) {
            return response()->json(['error' => 'You can only edit your own account.'], 403);
        }

// validation

        $user->update();

        return new UserShowResource($user->loadCount('cards'));
    }

    public function destroy(Request $request, User $user)
    {
        if (Auth::user()->id !== $user->user_id) {
            return response()->json(['error' => 'You can delete only your own account.'], 403);
        }

        $user->delete();

        return response()->json(['success' => 'Deleted'], 403);
    }

}
