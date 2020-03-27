<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    // 构造方法
    public function __construct() {
//        $this->middleware(['ckadmin:login']);
    }

    //登录显示
    public function index()
    {
        // 判断用户是否已经登录
        if (auth()->check()) {
            // 跳转到后台首页
            return redirect(route('admin.index'));
        }
        return view('admin.login.login');
    }

    // 登录 别名 admin.login 根据别名生成url  route(别名);
    public function login(Request $request)
    {
//        dump($request->all());die;
//        dump($request);die;
        //表单验证
        $post = $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => '用户名不能为空'
        ]);
//        dump($post);die;  //array("username" => "admin","password" => "admin888")

        //登录
        $bool = auth()->attempt($post);
        // 判断是否登录成功
        if ($bool) { // 登录成功
            //auth()->user(); 返回当前登录的用户模型对象 存储在session中
            // laravel默认session是存储在文件中 优化到memcached redis
            /*$model = auth()->user();
            dump($model->toArray());*/

            // 跳转以后台页面
            //return view('admin.index.index');
            return redirect(route('admin.index'));
        }
        // ->withErrors(['xx'])自带的session名就是errors
        return redirect(route('admin.login'))->withErrors(['用户名或密码错误']);

    }

    // 退出登录
    public function logout()
    {
        // 用户退出 清空session
        auth()->logout();
        //return view('admin.login.login');
        // 跳转 带提示  闪存 session
        // ->with('xxx',’提示信息’)的session名就是xxx
        return redirect(route('admin.login'))->with('success', '请重新登陆');
    }
}
