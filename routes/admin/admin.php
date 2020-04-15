<?php
// 后台路由

//路由分组
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    //登录提示 name给路由起别名   www.zfw.com/admin/login
    Route::get('login', 'LoginController@index')->name('admin.login');
    // 登录处理     www.zfw.com/admin/login
    Route::post('login', 'LoginController@login')->name('admin.login');

    //后台需要验证才能通过
    Route::group(['middleware' => ['ckadmin'], 'as' => 'admin.'], function () {
        // 后台首页     www.zfw.com/admin/index
        Route::get('index', 'IndexController@index')->name('index');
        // 后台欢迎页 绑定路由中间件     www.zfw.com/admin/welcome
        //Route::get('welcome', 'IndexController@welcome')->name('welcome')->middleware(['ckadmin']);
        Route::get('welcome', 'IndexController@welcome')->name('welcome');
        // 退出       www.zfw.com/admin/logout
        Route::get('logout', 'LoginController@logout')->name('logout');

        // 用户管理--------------------------------------
        // 用户列表
        Route::get('user/index', 'UserController@index')->name('user.index');
        // 添加用户显示
        Route::get('user/add', 'UserController@create')->name('user.create');
        // 添加用户处理
        Route::post('user/add', 'UserController@store')->name('user.store');

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
        Route::delete('user/del/{id}', 'UserController@del')->name('user.del');
        // 还原删除用户
        Route::get('user/restore/{id}', 'UserController@restore')->name('user.restore');
        // 全选删除
        Route::delete('user/delall', 'UserController@delall')->name('user.delall');
        // 修改用户显示
        //Route::get('user/edit/{id}', 'UserController@edit')->name('user.edit');
        Route::get('user/edit/{user}', 'UserController@edit')->name('user.edit');
        // 修改用户处理
        Route::put('user/edit/{id}', 'UserController@update')->name('user.update');
        // 给用户分配角色
        Route::match(['get', 'post'], 'user/role/{user}', 'UserController@role')->name('user.role');

        // 角色管理
        // 分配权限
        Route::get('role/node/{role}', 'RoleController@node')->name('role.node');
        Route::post('role/node/{role}', 'RoleController@nodeSave')->name('role.node');

        // 权限管理
        // 资源路由 admin/role/xxx
        Route::resource('role', 'RoleController');
        // 节点管理
        Route::resource('node', 'NodeController');
        // 文章管理
        Route::post('article/upfile', 'articleController@upfile')->name('article.upfile');
        Route::resource('article', 'articleController');

        // 房源属性
        // 文件上传
        Route::post('fangattr/upfile', 'FangAttrController@upfile')->name('fangattr.upfile');
        Route::resource('fangattr', 'FangAttrController');

        // 房东管理
        // 导出Excel
        Route::get('fangowner/exports', 'FangOwnerController@exports')->name('fangowner.exports');
        // 文件上传
        Route::post('fangowner/upfile', 'FangOwnerController@upfile')->name('fangowner.upfile');
        // 删除图片
        Route::get('fangowner/delfile', 'FangOwnerController@delfile')->name('fangowner.delfile');
        Route::resource('fangowner', 'FangOwnerController');

        // 房源管理
        // 改变房源状态
        Route::get('fang/changestatus', 'FangController@changestatus')->name('fang.changestatus');
        // 创建生成es索引的路由
        Route::get('fang/es/init', 'FangController@esinit')->name('fang.esinit');
        // 省市县三级联动
        Route::get('fang/city', 'FangController@city')->name('fang.city');
        // 文件上传
        Route::post('fang/upfile', 'FangController@upfile')->name('fang.upfile');
        Route::resource('fang', 'FangController');

        // 预约资源管理
        Route::resource('notice', 'NoticeController');

        // 接口账号管理
        Route::resource('apiuser', 'ApiuserController');

    });
});
