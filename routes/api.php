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

// Notifications
Route::post('push', 'PushNotificationController@store');
Route::get('notifications', 'PushNotificationController@push');
Route::post('unsubscribe', 'PushNotificationController@destroy');

// Print decks to PDF
Route::post('deck/pdf', function (Request $request) {
    $pdf = PDF::loadView('decks.pdf', ['deck' => $request->deck]);

    return $pdf->download('decks.pdf');
});

// Fallback for /api/*
Route::fallback(function () {
    return response()->json([
        'message' => 'Error 404 - not found.',
    ], 404);
})->name('api.fallback');
