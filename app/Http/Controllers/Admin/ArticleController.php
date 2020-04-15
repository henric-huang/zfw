<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AddArtRequest;
use App\Models\article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 判断是否是ajax请求
        if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
            // 排序  PHP7中新增的解构赋值写法
            // ['column' => $column, 'dir' => $dir] = $request->get('order')[0];

            $orderArr = $request->get('order')[0];
            // 获取表单哪一列排序
            $column = $orderArr['column'];
            // 获取升序还是降序
            $dir = $orderArr['dir'];
            // 获取排序字段
            $orderField = $request->get('columns')[$column]['data'];

            // 开启位置
            $start = $request->get('start', 0);
            // 开启时间
            $datemin = $request->get('datemin');
            // 结束时间
            $datemax = $request->get('datemax');
            // 搜索关键字
            $title = $request->get('title');

            // 查询对象
            $query = Article::where('id', '>', 0);
            // 日期
            if (!empty($datemin) && !empty($datemax)) {
                //
                $datemin = date('Y-m-d H:i:s', strtotime($datemin . ' 00:00:00'));
                $datemax = date('Y-m-d H:i:s', strtotime($datemax . ' 23:59:59'));

                $query->whereBetween('created_at', [$datemin, $datemax]);
            }
            // 搜索关键词
            if (!empty($title)) {
                $query->where('title', 'like', "%{$title}%");
            }
            // 获取记录数
            $length = min(100, $request->get('length', 10));
            // 记录总数
            $count = $query->count();
            // 获取数据
            $data = $query->orderBy($orderField, $dir)->offset($start)->limit($length)->get();
            /*
            draw: 客户端调用服务器端次数标识
            recordsTotal: 获取数据记录总条数
            recordsFiltered: 数据过滤后的总数量
            data: 获得的具体数据
            注意：recordsTotal和recordsFiltered都设置为记录的总条数
            */
            $result = [
                'draw'            => $request->get('draw'),
                'recordsTotal'    => $count,
                'recordsFiltered' => $count,
                'data'            => $data
            ];
            return json_encode($result);
        }

        // 取出所有的文章数据
        $data = Article::all();
        return view('admin.article.index', compact('data'));
    }

    /**
     * 添加文章
     */
    public function create()
    {
        return view('admin.article.create');
    }

    /**
     *  WebUploader文件上传
     */
    public function upfile(Request $request)
    {
        $pic = config('up.pic');
        if ($request->hasFile('file')) {
            // 上传,将文件'pic'保存到配置的'article'文件路径中
            // 参数2 配置的节点名称
            $ret = $request->file('file')->store('', 'article');
            $pic = '/uploads/article/' . $ret;
        }
        return ['status' => 0, 'url' => $pic];
    }

    /**
     * 添加文章处理
     * AddArtRequest 一定要引入命名空间
     */
    public function store(AddArtRequest $request)
    {
        // 文件上传
        $post = $request->except(['_token', 'file']);
        // 添加到数据库
        Article::create($post);
        return redirect(route('admin.article.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\article $article
     * @return \Illuminate\Http\Response
     */
    public function show(article $article)
    {
        //
    }

    /**
     * 文章显示修改
     */
    public function edit(Article $article)
    {
        return view('admin.article.edit', compact('article'));
    }

    /**
     * 修改处理
     */
    public function update(Request $request, article $article)
    {
        // dd($request->all());
        $putData = $request->except(['created_at', 'updated_at', 'deleted_at', 'action', 'id']);
        $article->update($putData);
        return ['status' => 0, 'url' => route('admin.article.index')];
    }

    /**
     * 文章删除
     */
    public function destroy(Article $article)
    {
        // 软删除
        $article->delete();
//        return ['status' => 1, 'msg' => '删除成功'];
        return ['id' => 1];
    }
}
