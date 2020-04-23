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

    // 用户授权信息入库
    // 文件上传
    Route::post('user/upfile', 'UserController@upfile');
    Route::post('user/userinfo', 'UserController@userinfo');
    Route::get('user/userinfo', 'UserController@getuserinfo');

    // 看房通知
    Route::get('notices', 'NoticeController@index');

    // 资讯列表
    Route::get('news', 'NewsController@index');

    // 使用路由参数
    Route::get('news/{article}', 'NewsController@show');

    // 资讯用户访问记录
    Route::post('news/{article}', 'NewsController@count');

    // 房源
    // 房源列表
    Route::get('fangs', 'FangController@fang');
    // es搜索
    Route::get('esfangs', 'FangController@esfang');
    // 推荐房源
    Route::get('fangs/recommend', 'FangController@recommend');
    // 房源属性
    Route::get('fangs/attr', 'FangController@attr');
    // 房源详情
    Route::get('fangs/{fang}', 'FangController@detail');

    // 房源属性接口
    Route::get('fangattrs', 'FangAttrController@attr');

    // 添加收藏
    Route::post('favs', 'FavController@add');
    // 查询收藏
    Route::get('favs/show', 'FavController@show');
    // 取消收藏
    Route::delete('favs/delete', 'FavController@delete');

});

