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
    Route::post('/forgotpassword', 'Api\AuthController@forgotPassword')->name('forgotpassword.api');

    // private routes
    Route::middleware('auth:api')->group(function () {
        Route::get('/logout', 'Api\AuthController@logout')->name('logout');
        Route::get('/category', 'Api\ContentController@getCategory')->name('category.api');
        Route::get('/profile', 'Api\ProfileController@get')->name('profile.api');
        Route::post('/profile', 'Api\ProfileController@update')->name('profile.api');
        Route::get('/user', 'Api\ContentController@getUser')->name('user.api');
        Route::get('/merchant', 'Api\ContentController@getMerchant')->name('merchant.api');
        Route::post('/merchant', 'Api\ContentController@saveMerchant')->name('merchant.api');
        // Route::get('/user', function (Request $request) {
        //    return $request->user();
        // });
    });

});

