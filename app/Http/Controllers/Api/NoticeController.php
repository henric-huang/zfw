<?php

namespace App\Http\Controllers\Api;

use App\Models\Notice;
use App\Models\Renting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoticeController extends Controller
{
    // 查看看房通知 GET请求
    public function index(Request $request)
    {

        $openid = $request->get('openid');

        // 根据openid转为租客ID
        $renting_id = Renting::where('openid', $openid)->value('id');

        // 查看此用户的看房通知
        $data = Notice::where('renting_id', $renting_id)->with('owner')->orderBy('id', 'desc')->get();

        return $data;
    }
}
