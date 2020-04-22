<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Models\Article_count;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    // 分页数
    protected $pagesize = 5;

    public function __construct()
    {
        $this->pagesize = config('page.pagesize');
    }

    // 列表
    public function index(Request $request)
    {
        $allow_field = [
            'id',
            'title',
            'desn',
            'pic',
            'created_at'
        ];
        $data        = Article::orderBy('id', 'desc')->select($allow_field)->paginate($this->pagesize);

        return $data;
    }

    //
    public function show(Article $article)
    {
        return $article;
    }

    public function count(Request $request, int $article)
    {
        // 用户访问统计
        $open_id = $request->get('openid');
        // 获取openid
        $data = [
            // 唯一索引
            'openid' => $open_id,
            'art_id'  => $article,
            'vdt'     => date('Y-m-d'),
            'vtime'   => time()
        ];
//        dd($data);
        try {
            $model = Article_count::create($data);
        } catch (\Exception $e) {
//            print $e->getMessage();
//            exit();
            return ['status' => 10006, 'msg' => '数据已存在'];
        }
        return $model;
    }
}
