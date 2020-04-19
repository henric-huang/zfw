<?php

namespace App\Models;

class Renting extends Base
{
    // 读取器
    public function getPhoneAttribute()
    {
        return !empty($this->attributes['phone']) ? $this->attributes['phone'] : '无号码';
    }
}
