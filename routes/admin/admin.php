<?php
// 后台路由

//路由分组
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    //登录提示 name给路由起别名   www.zfw.com/admin/login
    Route::get('login', 'LoginController@index')->name('admin.login');
    // 登录处理     www.zfw.com/admin/login
    Route::post('login', 'LoginController@login')->name('admin.login');

    //后台需要验证才能通过
    Route::group(['middleware' => ['ckadmin']], function () {
        // 后台首页     www.zfw.com/admin/index
        Route::get('index', 'IndexController@index')->name('admin.index');
        // 后台欢迎页 绑定路由中间件     www.zfw.com/admin/welcome
        //Route::get('welcome', 'IndexController@welcome')->name('admin.welcome')->middleware(['ckadmin']);
        Route::get('welcome', 'IndexController@welcome')->name('admin.welcome');
        // 退出       www.zfw.com/admin/logout
        Route::get('logout', 'LoginController@logout')->name('admin.logout');

        // 用户管理--------------------------------------
        // 用户列表
        Route::get('user/index', 'UserController@index')->name('admin.user.index');
        // 添加用户显示
        Route::get('user/add', 'UserController@create')->name('admin.user.create');
        // 添加用户处理
        Route::post('user/add', 'UserController@store')->name('admin.user.store');

        // 发送邮件
        Route::get('user/email', function () {
            /*//  发送文本邮件
            \Mail::raw('邮件正文内容', function (\Illuminate\Mail\Message $message) {
                // 获取回调方法中的形参数
                //dump(func_get_args());
                // 发给谁
                $message->to('981488996@qq.com');
                // 主题
                $message->subject('邮件标题');
                echo "ok";
            });*/

            // 发送富文本
            // 参数1  模板视图  以views文件夹为根目录
            // 参数2  传给视图的数据
            Mail::send('mail.adduser', ['user' => '张三'], function (\Illuminate\Mail\Message $message) {
                // 发给谁
                $message->to('981488996@qq.com');
                // 主题
                $message->subject('邮件标题');
            });
        });

        // 删除用户
        Route::delete('user/del/{id}', 'UserController@del')->name('admin.user.del');
        // 还原删除用户
        Route::get('user/restore/{id}', 'UserController@restore')->name('admin.user.restore');
        // 全选删除
        Route::delete('user/delall', 'UserController@delall')->name('admin.user.delall');
        // 修改用户显示
        Route::get('user/edit/{id}', 'UserController@edit')->name('admin.user.edit');
        // 修改用户处理
        Route::put('user/edit/{id}', 'UserController@update')->name('admin.user.update');


    });


});
