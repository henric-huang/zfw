<?php

namespace App\Models;

use App\Models\Traits\Btn;
use App\Observers\ApiuserObserver;
// 继承可以使用 auth登录的模型类
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as AuthUser;
// api验证方法
use Laravel\Passport\HasApiTokens;

class Apiuser extends AuthUser
{
    // 设置添加时的黑名单
    protected $guarded = [];
    // 软删除
    use SoftDeletes, HasApiTokens, Btn;
    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        self::observe(ApiuserObserver::class);
    }
}
