<?php

namespace App\Providers;

use App\User;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\User' => 'App\Policies\UserPolicy',
        'App\Deck' => 'App\Policies\DeckPolicy',
        'App\Card' => 'App\Policies\CardPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('username_email', function ($app) {
            return new UsernameEmailAuthServiceProvider($app->make(HasherContract::class), User::class);
        });
    }
}
