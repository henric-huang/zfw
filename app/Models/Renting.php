<?php

namespace App\Models;

class Renting extends Base
{
    // 读取器
    public function getPhoneAttribute()
    {
        return !empty($this->attributes['phone']) ? $this->attributes['phone'] : '无号码';
    }

    // 访问器
    public function getCardImgAttribute()
    {
        // 将图片地址分隔成数组
        $imglist = explode('#', $this->attributes['card_img']);
        // 循环放在数组中
        return array_map(function ($item) {
            return ['pic' => $item, 'url' => config('url.domain') . $item];
        }, $imglist);
    }
}
