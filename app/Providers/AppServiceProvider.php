<?php

namespace App\Providers;

use App\Api\GoogleTranslationApi;
use App\Api\WikipediaApi;
use Google\Cloud\Translate\V2\TranslateClient;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Passport::withoutCookieSerialization();

        $this->app->singleton(GoogleTranslationApi::class, function ($app) {
            return new GoogleTranslationApi(
                new TranslateClient([
                    'keyFilePath' => base_path(env('GOOGLE_API_CREDENTIALS')),
                ])
            );
        });

        $this->app->singleton(WikipediaApi::class, function ($app) {
            return new WikipediaApi(
                new Client([
                    'base_uri' => env('WIKIPEDIA_API_ENDPOINT'),
                ])
            );
        });

        $this->app->bind(
            'App\Contracts\TranslationContract',
            GoogleTranslationApi::class
        );

        $this->app->bind(
            'App\Contracts\ImageContract',
            WikipediaApi::class
        );

        // JsonResource::withoutWrapping();
    }
}
