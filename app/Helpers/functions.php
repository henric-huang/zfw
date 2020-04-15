<?php

/**
 * 数组的合并，并加上html标识前缀
 * @param array $data
 * @param int $pid
 * @param string $html
 * @param int $level
 * @return array
 */
function treeLevel(array $data, int $pid = 0, string $html = '--', int $level = 0)
{
    static $arr = [];
    foreach ($data as $val) {
        if ($pid == $val['pid']) {
            // 重复一个字符多少次
            $val['html']  = str_repeat($html, $level * 2);
            $val['level'] = $level + 1;
            $arr[]        = $val;
            treeLevel($data, $val['id'], $html, $val['level']);
        }
    }
    return $arr;
}