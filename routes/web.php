<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/','Controller@redirectLogin');
Route::group(['namespace' => 'Admin'],function(){
    Route::post('login','UserController@login');
});

Route::get('/faq', 'Admin\FAQsController@getBladeFAQ')->name('index');

Route::get('/dataxmltwilio', 'Admin\DeliveriesController@dataXMLTwi')->name('dataxmltwi');

Route::group(['middleware'=>'auth', 'prefix'=>'admin', 'as'=>'admin.'], function () {

    Route::group(['namespace' => 'Admin', 'prefix'=>'delivery', 'as'=>'delivery.'], function() {

        Route::get('/', 'DeliveriesController@index')->name('list');
        Route::post('delete/mp3/{key}', 'DeliveriesController@deleteMP3')->name('delete-mp3');

        Route::group(['prefix'=>'message-send', 'as' => 'send.'],function(){
            Route::get('create', 'DeliveriesController@create')->name('create');
            Route::post('store', 'DeliveriesController@store')->name('store');
            Route::delete('destroy', 'DeliveriesController@delete')->name('delete');
            Route::get('confirm', 'DeliveriesController@showConfirm');
            Route::get('edit/{id}', 'DeliveriesController@edit')->name('edit');
            Route::post('update/{id}', 'DeliveriesController@update')->name('update');
            Route::post('confirm/{id}', 'DeliveriesController@confirm')->name('confirm');
            Route::get('detail/{id}', 'DeliveriesController@showDetail')->name('detail');
        });
        Route::group(['prefix' => 'message-answer', 'as' => 'answer.'],function(){
            Route::get('create', 'AnswerController@create')->name('add');
            Route::post('store', 'AnswerController@store')->name('store');
            Route::delete('destroy', 'AnswerController@destroy')->name('delete');
            Route::get('edit/{id}', 'AnswerController@edit')->name('edit');
            Route::post('update/{id}', 'AnswerController@update')->name('update');
            Route::post('confirm/{id}', 'AnswerController@confirm')->name('confirm');
        });

    });

    Route::group(['namespace' => 'Admin', 'prefix'=>'call', 'as'=>'call.'], function() {
        Route::get('/delivery', 'DeliveriesController@deliveryCall')->name('delivery');

        Route::post('/delivery/{delivery}', 'DeliveriesController@deliveryCallPhone')->name('callphone');
        Route::post('/delivery', 'DeliveriesController@deliveryCallList')->name('deliverycalllist');

        Route::get('/startCall050/{delivery}', 'DeliveriesController@startCall050')->name('startCall050');
    });

    Route::group(['namespace' => 'Admin'], function() {
        Route::resource('faq', 'FAQsController');
        Route::resource('question', 'QuestionController');
        Route::get('/change-display-order','FAQsController@changeDisplayOrder')->name('changeDisplayOrder');
    });
    Route::resource('notification','Admin\NotificationController');
    Route::resource('appVersion','Admin\AppVersionController');
    Route::group(['namespace' => 'Admin', 'prefix'=>'notification', 'as'=>'notification.'],function(){
        Route::get('/{title?}','NotificationController@index')->name('index');
        Route::get('/confirm/{id}','NotificationController@confirm')->name('confirm');
        Route::get('/detail/{id}','NotificationController@showDetail')->name('detail');
    });
    Route::get('test','Admin\NotificationController@test');
});
