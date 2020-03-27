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
    return bcrypt('admin888');
});

// 引入定义好的后台路由文件
//include base_path('routes/admin/admin.php');
include __DIR__ . '/admin/admin.php';
