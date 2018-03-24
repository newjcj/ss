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

Route::get('/', function () {
//    print_r(33);exit;
    return redirect('/admin/login');
//	return view("welcome");
});
Route::get('/jcj', 'TestController@jcj');
Route::get('/test', 'TestController@test');

Route::get('register/{qr}', 'HomeController@register')->name('register');;
Route::post('register/{qr}', 'HomeController@doRegister')->name('register');
Route::post('verify-code', 'HomeController@smsSend')->name('verify-code');

Route::namespace('Frontend')->group(function (){
    Route::get('goods/{id}', 'GoodsController@show');
});

