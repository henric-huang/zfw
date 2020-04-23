<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Mail\Message;

class UserController extends BaseController
{
    // 用户列表
    public function index()
    {
        /*$info = User::with('role.nodes')->where('id', 1)->first()->toArray();
        dd($info);*/

        /*$info = User::where('id', 1)->first();
        // $info = $info->role->toArray();
        $info = $info->role->nodes->toArray();
        dd($info);*/

        // 分页
        $data = User::orderBy('id', 'asc')->withTrashed()->paginate($this->pagesize);

        return view('admin.user.index', compact('data'));
    }

    // 用户添加显示
    public function create()
    {
        return view('admin.user.create');
    }

    // 用户添加处理
    public function store(Request $request)
    {
        /*// 获取某一字段   参数1：获取字段  参数2：默认值
        dump(request()->get('username', 'zhangsan'));
        // 只获取指定字段
        dump(request()->only(['username', 'password']));
        // 只获取列举字段外的字段
        dump(request()->except(['username', 'password']));
        // 判断是否有该字段
        dump(request()->has('username'));
        // 获取全部
        dump(request()->all());die；*/

        /*$this->validate($request, [
            'username' => 'required',
            'truename' => 'required',
            'password' => 'required|confirmed',
            'phone' => 'nullable|phone'
        ], [
            'phone.phone' => '手机号码不合法'
        ]);*/

        $this->validate($request, [
            // 用户名唯一性验证
            'username' => 'required|unique:users,username',
            'truename' => 'required',
            // 两次密码是否一致的验证
            'password' => 'required|confirmed',
            // 自定义验证规则
            'phone'    => 'nullable|phone',
        ]);

        // 获取表单数据
        $post = $request->except(['_token', 'password_confirmation']);
        // 明文密码，后续发邮件要用
        $pwd              = $post['password'];
        $post['password'] = bcrypt($post['password']);
        // 添加用户入库
        $userModel = User::create($post);


        // 发邮件给用户   匿名函数内部获取外部数据，使用"use (外部数据)"
        /*\Mail::send('mail.useradd', compact('userModel', 'pwd'), function (Message $message) use ($userModel) {
            // 发给谁
            $message->to($userModel->email);
            // 主题
            $message->subject('邮件标题');
        });*/

        // 跳转到列表页
        return redirect(route('admin.user.index'))->with('success', '添加用户成功');

    }

    // 删除用户操作
    public function del(int $id)
    {
        if ($id != auth()->id()) {
            // 删除
            User::destroy($id);

            // 强制删除 在配置了软删除的时候，真实的删除操作
            // User::find($id)->forceDelete();

            //return ['status' => 0, 'msg' => '删除成功'];
            return json_encode(['status' => 0, 'msg' => '删除成功']);
        }
    }

    //还原删除用户
    public function restore(int $id)
    {
        // 还原
        User::onlyTrashed()->where('id', $id)->restore();
        return redirect(route('admin.user.index'))->with('success', '还原成功');
    }

    // 删除全部
    public function delall(Request $request)
    {
        $ids = $request->get('id');
        //dump($ids);die;
        User::destroy($ids);

        return json_encode(['status' => 0, 'msg' => '全选删除成功']);
    }

    // 修改用户显示
    /*public function edit(int $id)
    {
        $model = User::find($id);

        return view('admin.user.edit', compact('model'));
    }*/
    public function edit(User $user)
    {
        // dd($user->toArray());
        return view('admin.user.edit', compact('user'));
    }

    // 修改用户处理
    public function update(Request $request, int $id)
    {
        // 验证
        $this->validate($request, [
            'truename'  => 'required',
            'spassword' => 'required',
            'password'  => 'confirmed',
            'phone'     => 'nullable|phone'
        ]);

        $model = User::find($id);

        // 原密码要输对才能更改
        $spass  = $request->get('spassword');    // 新密码  明文
        $oldpwd = $model->password;              // 原密码 密文
        // 检查明文和密码是否一致
        $bool = \Hash::check($spass, $oldpwd);

        if ($bool) {
            // 修改
            $data = $request->only(['truename', 'password', 'phone', 'email', 'sex']);
            if (!empty($data['password'])) {
                $data['password'] = bcrypt($data->password);
            } else {
                unset($data['password']);
            }
            User::where('id', $id)->update($data);
            // $model->update($data);
            return redirect(route('admin.user.index'))->with('success', '修改成功');
        }
        // 如果只是获取id，可用模型对象代替
        //return redirect(route('admin.user.edit', $model))->withErrors(['error' => '原密码不正确']);
        return redirect(route('admin.user.edit', ['id' => $id]))->withErrors(['原密码不正确']);
    }

    // 分配角色和处理
    public function role(Request $request, User $user)
    {
        // 判断是否是post登录
        if ($request->isMethod('post')) {
            $post = $this->validate($request, [
                'role_id' => 'required'
            ], [
                'role_id.required' => '角色必须选择'
            ]);

            $user->update($post);
            return redirect(route('admin.user.index'));
            // redirect是跳转到控制器，不用携带数据，顶多需要携带id或者对应的模型对象用以满足路由条件，跳转到指定控制器
        }

        // 获取全部角色信息
        $roleAll = Role::all();
        return view('admin.user.role', compact('roleAll', 'user'));  //view是跳转到视图，需要携带数据传给视图
    }

}


