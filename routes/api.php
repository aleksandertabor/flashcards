<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['middleware' => ['auth:api']], function () {
    Route::apiResource('users', 'UserController')->only(['edit', 'update', 'destroy']);
});

Route::apiResource('users', 'UserController')->only(['show']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['middleware' => ['auth:api']], function () {
// });

Route::apiResource('decks', 'Api\DeckController')->only(['index', 'show']);

Route::get('search', 'SearchDeckController@index');

Route::post('login', 'AuthController@login');

Route::post('register', 'AuthController@register');
Route::post('logout', 'AuthController@logout');

Route::post('translate', 'TranslationController');
Route::post('detect', 'DetectionController');
Route::post('wikipedia', 'WikipediaController');
Route::post('twinword', 'TwinwordController');
Route::post('languages', 'LanguagesController');

Route::post('push', 'PushController@store');
Route::get('notifications', 'PushController@push');
Route::post('unsubscribe', 'PushController@destroy');

// Auth::routes();

Route::fallback(function () {
    return response()->json([
        'message' => 'Error 404 - not found.',
    ], 404);
})->name('api.fallback');
