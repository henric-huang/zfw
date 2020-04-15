<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddArtRequest extends FormRequest
{
    /**
     * 是否使用验证
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 文章表单验证
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'desc'  => 'required',
            'body'  => 'required'
        ];
    }

    /**
     * 文章表单提示
     */
    public function messages()
    {
        return [
            'title.required' => '标题一定要写'
        ];
    }
}
