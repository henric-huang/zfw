<?php

namespace App\Models\Traits;

trait Btn
{
    /**
     * @param string $route 路由别名
     * @return string
     */
    public function editBtn(string $route)
    {
        // 如果该用户不是超级管理员，且他没拥有传进来的路由的权限
        if (auth()->user()->username != config('rbac.super') && !in_array($route, request()->auths)) {
            return '';  // 按钮不显示
        }

        // 原页面的按钮代码
        // <a href="{{ route('admin.user.edit',$item) }}" class="label label-secondary radius">修改</a>
        // $this 表示当前模型对象，User、Role、Node...等，因为原页面代码第二个参数是要传具体用户的当前模型对象
        // 而我们这里User模型已经继承使用了Btn这个Trait，所以就用$this 调用User当前模型对象
        return '<a href="' . route($route, $this) . '" class="label label-secondary radius">修改</a>';
    }

    /**
     * @param string $route 路由别名
     * @return string
     */
    public function deleteBtn(string $route)
    {
        // {{ route('admin.user.edit',$item) }}
        if (auth()->user()->username != config('rbac.super') && !in_array($route, request()->auths)) {
            return '';
        }
        return '<a href="' . route($route, $this) . '"class="label label-danger radius delbtn">删除</a>';
    }

}