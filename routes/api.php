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

Route::prefix('user')->namespace('User')->group(function () {
    Route::post('login', 'IndexController@login');
    Route::post('register', 'IndexController@register');
    Route::post('sms', 'IndexController@smsSend');
    Route::post('balance', 'IndexController@balance');
    Route::post('team', 'IndexController@team');
    Route::any('team-detail', 'IndexController@teamDetail');
    Route::post('info', 'IndexController@info');
    Route::post('forget', 'IndexController@forget');
    Route::post('transfer', 'IndexController@transfer');
    Route::post('account', 'IndexController@account');
    Route::post('withdrawal', 'IndexController@withdrawal');
    Route::post('qr', 'IndexController@qr');
    Route::post('avatar-upload', 'IndexController@avatarUpload');
    Route::post('info-update', 'IndexController@updateInfo');
    Route::post('change-password', 'IndexController@changePassword');
    Route::post('account-flow-detail', 'IndexController@accountFlowDetail');
    Route::post('idcard-upload', 'IndexController@idcardUpload');
    Route::post('idcard-check', 'IndexController@idcardCheck');
    Route::any('recharge', 'IndexController@recharge');
    Route::get('chars', 'IndexController@chars');


    Route::prefix('message')->group(function () {
        Route::post('/', 'MessageController@index');
        Route::post('update', 'MessageController@update');
    });

    Route::prefix('feedback')->group(function () {
        Route::post('/', 'FeedbackController@index');
        Route::post('create', 'FeedbackController@create');
    });

    // 用户收货地址
    Route::prefix('address')->group(function () {
        Route::post('/', 'AddressController@index');
        Route::post('add', 'AddressController@add');
        Route::post('set', 'AddressController@set');
        Route::post('info', 'AddressController@info');
        Route::post('delete', 'AddressController@delete');
        Route::post('update', 'AddressController@update');
    });

    Route::prefix('bank')->group(function () {
        Route::post('/', 'BankCardController@index');
        Route::post('add', 'BankCardController@add');
        Route::post('info', 'BankCardController@info');
        Route::post('delete', 'BankCardController@delete');
        Route::post('update', 'BankCardController@update');
    });
});

Route::prefix('phone')->namespace('Phone')->group(function () {
    Route::post('/', 'IndexController@index');
    Route::post('callback', 'IndexController@callback')->name('phone.callback');
});

Route::prefix('goods')->namespace('Goods')->group(function () {
   Route::post('/', 'IndexController@index');
   Route::post('info', 'IndexController@info');
   Route::get('categoryOne', 'IndexController@categoryOne');
   Route::get('categoryTwo', 'IndexController@categoryTwo');
    Route::get('recommended', 'IndexController@recommended');
    Route::get('attributeGet', 'IndexController@attributeGet');
});

Route::prefix('notice')->namespace('Notice')->group(function () {
    Route::post('status', 'IndexController@status');
    Route::post('content', 'IndexController@content');
});

Route::prefix('order')->namespace('Order')->group(function () {
    Route::post('/', 'IndexController@index');
    Route::post('payment', 'IndexController@payment');
    Route::post('ali-notify', 'IndexController@aliNotify');
    Route::post('wechat-notify', 'IndexController@wechatNotify');
    Route::post('ali-return', 'IndexController@aliReturn');
    Route::any('list', 'IndexController@list');
    Route::post('express', 'IndexController@express');
});

Route::prefix('test')->namespace('Test')->group(function () {
    Route::get('/', 'IndexController@index');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
