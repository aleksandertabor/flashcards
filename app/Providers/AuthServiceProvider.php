<?php

namespace App\Providers;

use App\Providers\UsernameEmailAuthServiceProvider;
use App\User;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
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

        Passport::routes(function ($router) {
            $router->forAccessTokens();
        });

        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addDays(30));

    }
}
