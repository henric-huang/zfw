<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fang;
use App\Models\Node;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //
    public function index()
    {
        // 获取用户对应的权限
        $auth = session('admin.auth');
        // 读取菜单
        $menuData = (new Node())->treeData($auth);
//        dump($menuData);die;
        return view('admin.index.index', compact('menuData'));
    }

    //
    public function welcome()
    {
        $data = Fang::fangStatusCount();
        return view('admin.index.welcome', $data);
    }
}
