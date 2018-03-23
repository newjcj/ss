<?php

Route::namespace('Auth')->group(function () {
    Route::get('login', 'LoginController@showLoginForm')->name('admin.show-login-form');
    Route::post('login', 'LoginController@login')->name('admin.login');
    Route::get('logout', 'LoginController@logout')->name('admin.logout');
    Route::post('logout', 'LoginController@logout')->name('admin.logout');
});

// 数据面板
Route::get('dashboard',  'DashboardController@index')->name('admin.dashboard');

Route::group(['middleware' => ['auth:admin']], function () {

    // 用户
    Route::prefix('user')->group(function () {
        //用户管理路由
        Route::get('/',  'UserController@index')->name('admin.user.index');;
        Route::get('idcard',  'UserController@idcardCheck')->name('admin.user.idcard');;
        Route::post('iddocheck',  'UserController@iddocheck')->name('admin.user.iddocheck');;
        Route::post('list',  'UserController@userList')->name('admin.user.list');
        Route::post('change-role',  'UserController@changeRole')->name('admin.user.change-role');
        Route::post('change-asset',  'UserController@changeAsset')->name('admin.user.change-asset');
    });

    // 商品
    Route::prefix('goods')->group(function () {
        Route::get('list',  'GoodsController@index')->name('admin.goods.index');
        Route::post('list',  'GoodsController@goodsList')->name('admin.goods.index');
        Route::get('create',  'GoodsController@create')->name('admin.goods.create');
        Route::post('create',  'GoodsController@store')->name('admin.goods.create');
        Route::get('edit/{id?}',  'GoodsController@edit')->name('admin.goods.edit');
        Route::post('delete',  'GoodsController@delete')->name('admin.goods.delete');
        Route::post('update',  'GoodsController@update')->name('admin.goods.update');
        Route::post('upload',  'GoodsController@uploadImg')->name('admin.goods.upload');
        Route::post('grounding',  'GoodsController@grounding')->name('admin.goods.grounding');
        Route::post('sort',  'GoodsController@sort')->name('admin.goods.sort');
    });
    // 分类
    Route::prefix('category')->group(function () {
        Route::get('list','CategoryController@list');
        Route::get('detail','CategoryController@detail');
        Route::post('postdetail','CategoryController@postdetail');
        Route::get('add','CategoryController@add');
        Route::post('postadd','CategoryController@postadd');
        Route::post('delete','CategoryController@postdelete');
        Route::get('img','CategoryController@img');
    });
    // 订单
    Route::prefix('order')->group(function () {
        Route::get('list',  'OrderController@index')->name('admin.order.index');
        Route::post('list',  'OrderController@goodsList')->name('admin.order.index');
        Route::post('deliver',  'OrderController@deliver')->name('admin.order.deliver');
        Route::get('excel',  'OrderController@excel');
    });

    // 订单
    Route::prefix('user-flow')->group(function () {
        Route::get('/',  'UserFlowController@index')->name('admin.user-flow.index');
    });
    // 提现
    Route::prefix('withdrawal')->group(function () {
        Route::get('/',  'WithdrawalController@index')->name('admin.withdrawal.index');
        Route::post('agree',  'WithdrawalController@agree')->name('admin.withdrawal.agree');
        Route::post('reject',  'WithdrawalController@reject')->name('admin.withdrawal.reject');
    });

    //权限管理路由
    Route::prefix('permission')->group(function (){
        Route::get('{cid}/create', ['as' => 'admin.permission.create', 'uses' => 'PermissionController@create']);
        Route::get('{cid}/create', ['as' => 'admin.permission.create', 'uses' => 'PermissionController@create']);
        Route::get('manage', ['as' => 'admin.permission.manage', 'uses' => 'PermissionController@index']);
        Route::get('{cid?}', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']);
        Route::post('index', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']); //查询
        Route::resource('permission', 'PermissionController', ['names' => ['update' => 'admin.permission.edit', 'store' => 'admin.permission.create']]);
    });

    Route::get('permission/{cid}/create', ['as' => 'admin.permission.create', 'uses' => 'PermissionController@create']);
    Route::get('permission/manage', ['as' => 'admin.permission.manage', 'uses' => 'PermissionController@index']);
    Route::get('permission/{cid?}', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']);
    Route::post('permission/index', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']); //查询
    Route::resource('permission', 'PermissionController', ['names' => ['update' => 'admin.permission.edit', 'store' => 'admin.permission.create']]);

    //角色管理路由
    Route::get('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index']);
    Route::post('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index']);
    Route::resource('role', 'RoleController', ['names' => ['update' => 'admin.role.edit', 'store' => 'admin.role.create']]);


    // 系统
    Route::namespace('System')->prefix('system')->group(function () {
        // 后台用户管理
        Route::prefix('admin-user')->group(function () {
            Route::get('/',  'AdminUserController@index')->name('system.admin-user.index');
            Route::get('create',  'AdminUserController@create')->name('system.admin-user.create');
            Route::post('create',  'AdminUserController@store')->name('system.admin-user.create');
            Route::get('edit',  'AdminUserController@edit')->name('system.admin-user.edit');
            Route::post('edit',  'AdminUserController@update')->name('system.admin-user.edit');
        });

        Route::get('notice',  'NoticeController@index')->name('admin.system.notice');
        Route::post('notice-update',  'NoticeController@update')->name('admin.system.notice.update');
    });
});
