<?php

namespace App\Http\Controllers\Admin;

use App\Models\Node;
use App\Models\Role;
use App\Models\RoleNode;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends BaseController
{
    /**
     * 角色列表展示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 获取关键词
        $name = request()->get('name', '');
        // 分页 搜索
        // when 如果参数1存在时，则执行参数2的匿名函数
        $data = Role::when($name, function ($query) use ($name) {
            $query->where('name', 'like', "%{$name}%");
        })->paginate($this->pagesize);

        return view('admin.role.index', compact('data', 'name'));
    }

    /**
     * 添加角色页面
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * 添加角色处理
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 异常处理
        try {
            $this->validate($request, [
                'name' => 'required|unique:roles,name'
            ]);
        } catch (\Exception $e) {
            return ['status' => 1000, 'msg' => '验证不通过'];
        }
        // 添加到角色表
        Role::create($request->only('name'));
        return ['status' => 0, 'msg' => '添加角色成功'];
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * 修改角色
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $model = Role::find($id);
        return view('admin.role.edit', compact('model'));
    }

    /**
     * 修改角色处理
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $name = $request->only(['name']);
        try {
            $this->validate($request, [
                'name' => 'required|unique:roles,name,' . $id . ',id',
            ]);
        } catch (\Exception $e) {
            return ['staus' => 1, 'msg' => '验证不通过'];
        };
        Role::where('id', $id)->update($request->only(['name']));
        return ['status' => 0, 'msg' => '修改角色名称成功'];

    }

    /**
     * 删除角色
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        // 如果还有用户使用该角色，不能删除
        /*$data = User::where('role_id', $id)->get();
        if ($data) {
            return json_encode(['staus' => 1, 'msg' => '该角色还有用户使用，无法删除']);
        }*/

        Role::destroy($id);
        return json_encode(['status' => 0, 'msg' => '删除成功']);
    }

    // 给角色分配权限
    public function node(Role $role)
    {
        // dd($role);
        //dump($role->nodes->toArray());
        //dump($role->nodes()->pluck('name', 'id')->toArray());
        // 读取出所有的权限
        $nodeAll = (new Node())->getAllList();
        // 读取当前角色所拥有的权限id   pluck()用于获取括号内该列字段的值
        $nodes = $role->nodes()->pluck('id')->toArray();    // [0 => 1, 1 => 2, 2 => 3, 3 => 4];

        return view('admin.role.node', compact('role', 'nodeAll', 'nodes'));
    }

    // 分配处理
    public function nodeSave(Request $request, Role $role)
    {
        /*$node = $request->get('node');
//        dd($role->id);
        RoleNode::destroy('role_id', $role->id);
        foreach ($node as $item) {
            $data = [
                'role_id' => $role->id,
                'node_id' => $item
            ];
            RoleNode::create('node_id', $data);
        }*/

        // 关联模型的数据同步
        $role->nodes()->sync($request->get('node'));

        //return redirect(route('admin.role.node', $role));   //跳转到权限页
        return redirect(route('admin.role.index'));       //跳转到角色首页
    }
}





