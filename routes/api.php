<?php

use App\FlashcardDeck;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('decks', function (Request $request) {
    return FlashcardDeck::all();
});

Route::get('decks/{deckId}', function (Request $request, $id) {
    return FlashcardDeck::findOrFail($id);
});
