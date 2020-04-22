<?php

namespace App\Http\Controllers\Api;

use App\Models\Renting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    // 用户获取信息入库  openid 头像和昵称
    public function userinfo(Request $request)
    {
        // 得到openid
        $openid = $request->get('openid');
        // 得到表单数据  头像  和  昵称
        $data = $request->except('openid');

        // 修改入库
        try {
            Renting::where('openid', $openid)->update($data);
            return ['status' => 0, 'msg' => '修改成功'];
        } catch (\Exception $exception) {
            return ['status' => 1006, 'msg' => '修改失败'];
        }
    }

    // 获取信息
    public function getuserinfo(Request $request)
    {
        // 得到openid
        $openid = $request->get('openid');
        return Renting::where('openid', $openid)->first();
    }

    // 文件上传
    public function upfile(Request $request)
    {
        if ($request->hasFile('file')) {
            // 上传
            // 参数1 生成一个以参数1为名称的文件夹，里面存放图片
            // 参数2 配置的节点名称
            $ret = $request->file('file')->store($request->get('openid'), 'card');
            $pic = '/uploads/card/' . $ret;
            return ['status' => 0, 'pic' => $pic, 'url' => config('url.domain') . $pic];
        }
        return ['status' => 1005, 'msg' => '无图片上传'];
    }
}
