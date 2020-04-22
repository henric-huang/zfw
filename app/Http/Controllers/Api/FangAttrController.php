<?php

namespace App\Http\Controllers\Api;

use App\Models\Fangattr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FangAttrController extends Controller
{
    protected $_attr = [
        // 租房小组
        'fang_group'      => 1,
        // 租期方式
        'fang_rent_type'  => 3,
        // 房源朝向
        'fang_direction'  => 6,
        // 租赁方式
        'fang_rent_class' => 9
    ];

    // 房源属性列表
    public function attr(Request $request)
    {
        $arr = [];
        foreach ($this->_attr as $key => $value) {
            $arr[$key] = Fangattr::where('pid', $value)->get(['id', 'name']);
        }

        return $arr;
    }
}
