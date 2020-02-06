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

Route::post('deck/pdf', function (Request $request) {
    $pdf = PDF::loadView('decks.pdf', ['deck' => $request->deck]);

    return $pdf->download('decks.pdf');
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Error 404 - not found.',
    ], 404);
})->name('api.fallback');
