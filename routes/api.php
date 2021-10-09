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
Route::post('/callvip', 'Admin\DeliveriesController@callvip')->name('callvip');
Route::post('/callarchive', 'Admin\DeliveriesController@callarchive')->name('callarchive');
Route::get('/call050/{delivery}', 'Admin\DeliveriesController@call050')->name('call050');
Route::group(['namespace'  => 'Api\V1'], function () {

    Route::get('/setVersion', 'AppVersionController@setVersion');
    Route::get('/getVersion', 'AppVersionController@getVersion');

    Route::post('push-test', 'NotificationsController@testPush');
    Route::get('listDelivery','DeliveryController@getFullList');
    Route::post('getXMLVerifyCall','UsersController@xmlVerifyCall')->name('getXMLVerifyCall');

    Route::post('/statusCallback', 'CallsController@statuscallback')->name('statuscallback');

    Route::post('postDevice', 'UsersController@postDevice');
    Route::post('postSendCode', 'UsersController@verifyByCall');
    Route::post('postVerifyCode', 'UsersController@postVerifyCode');
    Route::post('getTokenTwilio', 'CallsController@getTokenTwilio');
    Route::get('getTokenTwilio', 'CallsController@getTokenTwilio');
    Route::group(['prefix' => 'notify'],function(){
        Route::get('list', 'NotificationsController@listNotify');
    });
    Route::group(['prefix' => 'call'],function(){
        Route::post('place', 'CallsController@placeCall');
    });
});
Route::group([ 'middleware' => 'auth.api', 'namespace'  => 'Api\V1' ], function () {
    Route::post('verifyPhoneCall','UsersController@verifyByCall');
    Route::post('sendQuestion', 'MailController@sendQuestion');
    Route::group(['prefix' => 'call'],function(){
        //get list call (history)
        Route::post('history', 'CallsController@callHistory')->name('call.history');
        //get url download dua vao status cua call newset
        Route::get('urlDownload','CallsController@getURL');
        Route::get('updateStatus','CallsController@updateStatus');
        Route::post('updateStatusNewestCall','CallsController@updateStatusNewestCall');
    });
    Route::post('listDelivery','DeliveryController@getList');
});