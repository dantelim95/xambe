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

    Route::get('/init', 'Api\ContentController@getInit')->name('init.api');

    // private routes
    Route::middleware('auth:api')->group(function () {
        Route::get('/logout', 'Api\AuthController@logout')->name('logout');

        Route::get('/settings', 'Api\ContentController@getSettings')->name('settings.api');

        Route::get('/category', 'Api\ContentController@getCategory')->name('category.api');

        Route::get('/profile', 'Api\ProfileController@get')->name('profile.api');
        Route::post('/profile', 'Api\ProfileController@update')->name('updateprofile.api');

        Route::get('/user', 'Api\ContentController@getUser')->name('user.api');

        Route::get('/merchant', 'Api\ContentController@getMerchant')->name('merchant.api');
        Route::post('/merchant', 'Api\ContentController@saveMerchant')->name('merchant.api');

        Route::get('/address', 'Api\AddressController@getAll')->name('address.getAll.api');
        Route::get('/address/{id}', 'Api\AddressController@get')->name('address.get.api');
        Route::post('/address', 'Api\AddressController@create')->name('address.create.api');
        Route::put('/address', 'Api\AddressController@update')->name('address.update.api');
        Route::delete('/address', 'Api\AddressController@delete')->name('address.delete.api');

        Route::get('/adsitem', 'Api\AdsItemController@getAll')->name('adsitem.getAll.api');
        Route::get('/adsitem', 'Api\AdsItemController@getAll')->name('adsitem.getAll.api');
        Route::get('/adsitem/{id}', 'Api\AdsItemController@get')->name('adsitem.get.api');
        Route::post('/adsitem', 'Api\AdsItemController@create')->name('adsitem.create.api');
        Route::put('/adsitem', 'Api\AdsItemController@update')->name('adsitem.update.api');
        Route::delete('/adsitem', 'Api\AdsItemController@delete')->name('adsitem.delete.api');
        // Route::get('/user', function (Request $request) {
        //    return $request->user();
        // });
    });

});

