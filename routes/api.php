<?php

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

use App\Http\Controllers\FcmTokenController;
use App\Http\Controllers\SpaceUsageController;

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('uploads/{id}', '\Common\Files\Controllers\FileEntriesController@show');

    // SHARING
    Route::post('entries/add-users', 'SharesController@addUsers');
    Route::delete('entries/remove-user/{userId}', 'SharesController@removeUser');

    // SHAREABLE LINK
    Route::get('entries/{id}/shareable-link', 'ShareableLinksController@show');
    Route::post('entries/{id}/shareable-link', 'ShareableLinksController@store');
    Route::put('shareable-links/{id}', 'ShareableLinksController@update');
    Route::delete('shareable-links/{id}', 'ShareableLinksController@destroy');

    // ENTRIES
    Route::get('entries', 'DriveEntriesController@index');
    Route::post('entries', '\Common\Files\Controllers\FileEntriesController@store');
    Route::post('entries/move', 'MoveFileEntriesController@move');
    Route::post('entries/copy', 'CopyEntriesController@copy');
    Route::post('entries/restore', '\Common\Files\Controllers\RestoreDeletedEntriesController@restore');
    Route::put('entries/{id}', '\Common\Files\Controllers\FileEntriesController@update');
    Route::delete('entries', '\Common\Files\Controllers\FileEntriesController@destroy');

    // FOLDERS
    Route::post('folders', 'FoldersController@store');

    // STARRING
    Route::post('entries/star', 'StarredEntriesController@add');
    Route::post('entries/unstar', 'StarredEntriesController@remove');

    // LOCALIZATIONS
    Route::get('localizations/{name}', '\Common\Localizations\LocalizationsController@show');

    //SPACE USAGE
    Route::get('user/space-usage', [SpaceUsageController::class, 'index']);

    // FCM TOKENS
    Route::post('fcm-token', [FcmTokenController::class, 'store']);
});

// AUTH
Route::post('auth/access-token', '\Common\Auth\Controllers\GetAccessTokenController@login');
Route::get('auth/social/{provider}/callback', '\Common\Auth\Controllers\SocialAuthController@loginCallback');
Route::post('auth/password/email', '\Common\Auth\Controllers\SendPasswordResetEmailController@sendResetLinkEmail');
Route::post('auth/register', '\Common\Auth\Controllers\RegisterController@register');

// REMOTE CONFIG
Route::get('remote-config/mobile', 'RemoteConfigController@mobile');


