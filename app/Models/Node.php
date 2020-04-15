<?php

namespace App\Models;

class Node extends Base
{
    // 获取全部的数据
    public function getAllList()
    {
        // $data = Node::get()->toArray();
        $data = self::get()->toArray();
        return $this->treeLevel($data);
    }

    // 获取父子级树状结构 层级的数据
    public function treeData($allow_node)
    {
        $query = Node::where('is_menu', '1');
        if (is_array($allow_node)) {
            $query->whereIn('id', array_keys($allow_node));
        }
//        $menuData = Node::where('is_menu', '1')->get()->toArray();
        $menuData = $query->get()->toArray();
        return $this->subTree($menuData);
    }

    public function getAll()
    {
        $data = self::get()->toArray();
        return treeLevel($data);
    }


}
