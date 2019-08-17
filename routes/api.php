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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::group(['middleware' => ['json.response']], function () {

    // public routes
    Route::post('/login', 'Api\AuthController@login')->name('login.api');
    Route::post('/register', 'Api\AuthController@register')->name('register.api');

    // private routes
    Route::middleware('auth:api')->group(function () {
        Route::get('/logout', 'Api\AuthController@logout')->name('logout');
        Route::get('/category', 'Api\ContentController@getCategory')->name('category.api');
        Route::get('/profile', 'Api\ContentController@getProfile')->name('profile.api');
        Route::post('/profile', 'Api\ContentController@saveProfile')->name('profile.api');
        Route::get('/user', 'Api\ContentController@getUser')->name('user.api');
        // Route::get('/user', function (Request $request) {
        //    return $request->user();
        // });
    });

});

