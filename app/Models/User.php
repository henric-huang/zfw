<?php

namespace App\Models;

// 软删除类
use Illuminate\Database\Eloquent\SoftDeletes;
// 继承可以使用 auth登录的模型类
use Illuminate\Foundation\Auth\User as AuthUser;
// trait按钮组
use App\Models\Traits\Btn;

class User extends AuthUser
{
    // 调用定义的trait类 和继承效果一样
    use SoftDeletes;
    use Btn;

    // 软删除标识字段
    protected $dates = ['delete_at'];

    //protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    //定义为空数组，则所有属性都可以被批量赋值
    protected $guarded = [];

    // 隐藏字段
    protected $hidden = ['password'];

    // 角色 属于
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
