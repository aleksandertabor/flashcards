<?php

namespace App\Providers;

use App\Api\GoogleTranslationApi;
use App\Api\TwinwordApi;
use App\Api\WikipediaApi;
use Google\Cloud\Translate\V2\TranslateClient;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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

        if (file_exists(base_path(env('GOOGLE_API_CREDENTIALS')))) {
            $this->app->singleton(GoogleTranslationApi::class, function () {
                return new GoogleTranslationApi(
                    new TranslateClient([
                        'keyFilePath' => base_path(env('GOOGLE_API_CREDENTIALS')),
                    ])
                );
            });
        }

        if (env('TWINWORD_API_KEY') && env('TWINWORD_API_ENDPOINT')) {
            $this->app->singleton(TwinwordApi::class, function () {
                return new TwinwordApi(
                    new Client([
                        'base_uri' => env('TWINWORD_API_ENDPOINT'),
                        'headers' => [
                            'X-RapidAPI-Key' => env('TWINWORD_API_KEY'),
                        ],
                    ])
                );
            });
        }

        if (env('WIKIPEDIA_API_ENDPOINT')) {
            $this->app->singleton(WikipediaApi::class, function () {
                return new WikipediaApi(
                    new Client([
                        'base_uri' => env('WIKIPEDIA_API_ENDPOINT'),
                    ])
                );
            });
        }

        $this->app->bind(
            'App\Contracts\TranslationContract',
            GoogleTranslationApi::class
        );

        $this->app->bind(
            'App\Contracts\ImageContract',
            WikipediaApi::class
        );

        $this->app->bind(
            'App\Contracts\ExampleContract',
            TwinwordApi::class
        );
    }
}
