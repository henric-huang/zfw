<?php

namespace App\Models;

use App\Models\Traits\Btn;
use Illuminate\Database\Eloquent\Model;

class Role extends Base
{
    use Btn;

    // 角色与权限  多对多
    public function nodes()
    {
        // 参1 关联模型
        // 参2 中间表的表名，没有前缀
        // 参3 本模型对应的外键ID
        // 参4 关联模型对应的外键ID
        return $this->belongsToMany(Node::class, 'role_nodes', 'role_id', 'node_id');
    }
}
