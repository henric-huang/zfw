<?php

namespace App\Http\Controllers\Admin;

use App\Models\Node;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NodeController extends Controller
{
    /**
     * 显示节点
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$data = (new Node)->getAll();
        /*$data = Node::get()->toArray();
        $data = treeLevel($data);
        dd($data);*/
        // 获取所有的节点 返回是数组
        // $data = Node::get();
        $data = (new Node)->getAllList();
        // dd($data);
        return view('admin.node.index', compact('data'));
    }

    /**
     * 添加节点
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 获取所有的顶级
        $data = Node::where('pid', 0)->get();
        return view('admin.node.create', compact('data'));
    }

    /**
     * 添加节点处理
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 表单验证
        // try{}catch(){};
        // 入库
//        dd($request->all());
        /*$data = $request->except('_token');
        $data['route_name'] = empty()*/
        Node::create($request->except('_token'));
        return ['status' => 0, 'msg' => '添加权限成功'];
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Node $node
     * @return \Illuminate\Http\Response
     */
    public function show(Node $node)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Node $node
     * @return \Illuminate\Http\Response
     */
    public function edit(Node $node)
    {
        $data = Node::where('pid', 0)->get();
//        dd($data);
        return view('admin.node.edit', compact('node', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Node $node
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Node $node)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Node $node
     * @return \Illuminate\Http\Response
     */
    public function destroy(Node $node)
    {
        //
    }
}
