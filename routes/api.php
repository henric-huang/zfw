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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


// 说明：定义在api.php文件中的路由，默认就有了一个前缀，api

// 定义登录路由
// 访问路由  api/login
Route::post('login', 'Api\LoginController@login');

// 请求验证
// 符合restful标准
// uri 独立域名或能区别的前缀 api
// uri最好有版本
// 结果用名词不用动词
// 内容可以用 rsa非对称加密 https://blog.csdn.net/haibo0668/article/details/81489217
Route::group(['middleware' => 'auth:api', 'prefix' => 'v1', 'namespace' => 'Api'], function () {

    Route::get('user', function () {
        return '123456';
        //return base64_decode(base64_decode('WVdGaE1USXpORFUyTnpoaVltST0='));
        //return base64_encode(base64_encode('aaa12345678bbb'));
    });

    // 小程序登录
    Route::post('wxlogin', 'LoginController@wxlogin');

});

