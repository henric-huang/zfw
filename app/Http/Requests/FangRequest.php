<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FangRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    // 验证规则
    public function rules()
    {
        return [
            'fang_name'     => 'required',
            'fang_province' => 'numeric|min:1',
            'fang_city'     => 'numeric|min:1',
            'fang_region'   => 'numeric|min:1',
        ];
    }

    // 提示信息
    public function messages()
    {
        return [
            'fang_name.required' => '房源名称不能为空',
            'fang_province.min'  => '必须选择省份',
            'fang_city.min'      => '必须选择城市',
            'fang_region.min'    => '必须选择区/县',
        ];
    }
}
