<?php

namespace App\Models;

use App\Models\Traits\Btn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Base extends Model
{
    // 设置添加时的黑名单
    protected $guarded = [];
    // 软删除
    use SoftDeletes, Btn;
    protected $dates = ['deleted_at'];

    /**
     * 数组的合并，并加上html标识前缀 无限极分类
     * @param array $data
     * @param int $pid
     * @param string $html
     * @param int $level
     * @return array
     */
    public function treeLevel(array $data, int $pid = 0, string $html = '--', int $level = 0)
    {
        static $arr = [];
        foreach ($data as $val) {
            if ($pid == $val['pid']) {
                // 重复一个字符多少次
                $val['html']  = str_repeat($html, $level * 2);
                $val['level'] = $level + 1;
                $arr[]        = $val;
                $this->treeLevel($data, $val['id'], $html, $val['level']);
            }
        }
        return $arr;
    }

    /**
     * 父子级树状结构
     * @param array $data
     * @param int $pid
     * @return array
     */
    public function subTree(array $data, int $pid = 0)
    {
        // 返回的结果
        $arr = [];
        foreach ($data as $val) {
            // 给定的PID是当前记录的上级ID
            if ($pid == $val['pid']) {
                // 递归
                $val['sub'] = $this->subTree($data, $val['id']);
                $arr[]      = $val;
            }
        }
        return $arr;
    }
}
