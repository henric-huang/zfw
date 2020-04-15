<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    // 构造方法
    public function __construct()
    {
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
        /*$p = $request->only(['username','password']);
        $bool = auth()->attempt($p);*/

        $bool = auth()->attempt($post);
        // 判断是否登录成功
        if ($bool) { // 登录成功
            //auth()->user(); 返回当前登录的用户模型对象 存储在session中
            // laravel默认session是存储在文件中 优化到memcached redis
            /*$model = auth()->user();
            dump($model->toArray());*/

            // 得到该用户的权限
            // 判断是否是超级管理员
            if (config('rbac.super') != $post['username']) {
                $userModel = auth()->user();
                // 这两个是等效的，如果我们要获取到模型的数据，肯定要用first()或get()进行查询，也就是第一种写法
                // 而我们要是想省略first()或get()进行查询，那就把关联关系方法的括号()省略掉，也就是第二种写法
                // 拓展：find()和all()用法是模型名::find()/all(),中间不能加条件，所以这里不能用。
                // $roleModel = $userModel->role()->first();
                // $roleModel = $userModel->role;

                // $nodeArr = $roleModel->nodes;
                // $nodeArr = $userModel->role->nodes()->get()->toArray();

                $nodeArr = $userModel->role->nodes()->pluck('route_name', 'id')->toArray();
                // dd($nodeArr);
                // 权限保存在session中
                session(['admin.auth' => $nodeArr]);
            } else {
                session(['admin.auth' => true]);
            }
            // 跳转以后台页面
            return redirect(route('admin.index'));
        }
        // withErrors 把信息写入到验证错误提示中  特殊的session laravel中叫 闪存
        // 闪存 从设置好之后，只能在第1个http请求中获取到数据，以后就没有了
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
