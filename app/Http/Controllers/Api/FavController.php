<?php

namespace App\Http\Controllers\Api;

use App\Models\Fav;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavController extends Controller
{
    // 添加收藏
    public function add(Request $request)
    {
        try {
            // 表单验证
            $postData = $this->validate($request, [
                'openid'  => 'required',
                'fang_id' => 'required|numeric'
            ]);
            // 入库
            // 1、查询此用户是否对此房源有过收藏
            $data = Fav::where('openid', $postData['openid'])->where('fang_id', $postData['fang_id'])->first();
            // 2、判断是否收藏与否，做出对应的操作
            if (is_null($data)) {
                $data = Fav::create($postData);
                return response()->json(['stauts' => 0, 'msg' => '添加成功', 'data' => $data], 201);
            } else { // 数据存在就更新一个时间就可以，不需要添加
                Fav::where('openid', $postData['openid'])->where('fang_id', $postData['fang_id'])->update($postData);
                $data = array_merge($data->toArray(), $postData);
                return response()->json(['stauts' => 0, 'msg' => '更新成功', 'data' => $data], 201);
            }
        } catch (\Exception $e) {
            return ['status' => 100010, 'msg' => '数据验证不通过'];
        }

    }

    // 查询用户是否有收藏房源
    public function show(Request $request)
    {
        $openid  = $request->get('openid');
        $fang_id = $request->get('fang_id');
        $ret     = Fav::where('openid', $openid)->where('fang_id', $fang_id)->first();
        if (is_null($ret)) {
            return ['status' => 0];
        }
        return ['status' => 1];
    }

    // 取消收藏
    public function delete(Request $request)
    {
        $openid  = $request->get('openid');
        $fang_id = $request->get('fang_id');
        $ret     = Fav::where('openid', $openid)->where('fang_id', $fang_id)->first();
        if (is_null($ret)) {
            return ['status' => 0];
        } else {
            Fav::where('openid', $openid)->where('fang_id', $fang_id)->delete();
            return ['status' => 0];
        }
    }
}
