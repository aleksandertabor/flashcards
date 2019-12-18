<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserShowResource;
use App\User;

class UserController extends Controller
{
    public function show(User $user)
    {
        return new UserShowResource($user->loadCount('cards'));
    }
}
