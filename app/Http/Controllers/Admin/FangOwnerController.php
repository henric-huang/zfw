<?php

namespace App\Http\Controllers\Admin;

use App\Models\FangOwner;
use Illuminate\Http\Request;
// Excel类
use App\Exports\FangOwnerExport;
use Maatwebsite\Excel\Facades\Excel;

class FangOwnerController extends BaseController
{
    /**
     * 房东列表
     */
    public function index()
    {
        // 获取房东数据
        $data = FangOwner::paginate($this->pagesize);
//        dd($data->toArray());
        return view('admin.fangowner.index', compact('data'));
    }

    /**
     * 添加显示
     */
    public function create()
    {
        return view('admin.fangowner.create');
    }

    // 文件上传
    public function upfile(Request $request)
    {
        // 默认图标
        $pic = config('up.pic');
        if ($request->hasFile('file')) {
            // 上传
            // 参数2 配置的节点名称
            $ret = $request->file('file')->store('', 'fangowner');
            $pic = '/uploads/fangowner/' . $ret;
        }
        return ['status' => 0, 'url' => $pic];
    }

    // 文件删除
    public function delfile(Request $request)
    {
        $filepath = $request->get('file');
        // 得到真实的地址
        $path = public_path() . $filepath;
        // 删除指定的文件
        unlink($path);
        return ['status' => 0, 'msg' => '成功'];
    }

    /**
     * 添加房东处理
     */
    public function store(Request $request)
    {
        // 表单验证
        $this->validate($request, [
            'name'  => 'required',
            'phone' => 'required|phone'
        ]);

//        dd($request->all());
        // 获取数据
        $postData = $request->except(['_token', 'file']);
        // 去除两边的'#'号
        $postData['pic'] = trim($postData['pic'], '#');
        FangOwner::create($postData);
        return redirect(route('admin.fangowner.index'));
    }

    /**
     * 查看照片
     */
    public function show(FangOwner $fangowner)
    {
        $picList = explode('#', $fangowner->pic);
        // 遍历
        array_map(function ($item) {
            echo "<div><img src='$item' style='width: 150px;'></div>";
        }, $picList);
    }

    // 导出excel
    public function exports()
    {
        return Excel::download(new FangOwnerExport(), 'fangdong.xlsx');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\FangOwner $fangOwner
     * @return \Illuminate\Http\Response
     */
    public function edit(FangOwner $fangOwner)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\FangOwner $fangOwner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FangOwner $fangOwner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\FangOwner $fangOwner
     * @return \Illuminate\Http\Response
     */
    public function destroy(FangOwner $fangOwner)
    {
        //
    }
}
