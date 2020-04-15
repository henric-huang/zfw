<?php

namespace App\Http\Controllers\Admin;

use App\Models\Apiuser;
use Illuminate\Http\Request;

class ApiuserController extends BaseController
{
    // 列表展示
    public function index()
    {
        $data = Apiuser::paginate($this->pagesize);
        return view('admin.apiuser.index', compact('data'));
    }

    // 添加显示
    public function create()
    {
        return view('admin.apiuser.create');
    }

    // 添加处理
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'username' => 'required|unique:apiusers,username',
            'password' => 'required'
        ]);

        // 入库
        Apiuser::create($data);
        // 跳转
        return redirect(route('admin.apiuser.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Apiuser $apiuser
     * @return \Illuminate\Http\Response
     */
    public function show(Apiuser $apiuser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Apiuser $apiuser
     * @return \Illuminate\Http\Response
     */
    public function edit(Apiuser $apiuser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Apiuser $apiuser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apiuser $apiuser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Apiuser $apiuser
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apiuser $apiuser)
    {
        //
    }
}
