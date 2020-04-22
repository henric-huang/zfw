<?php

namespace App\Http\Controllers\Api;

use App\Models\Fang;
use App\Models\Fangattr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FangController extends Controller
{
    // 房源推荐
    public function recommend(Request $request)
    {
        // 返回给小程序的接口的字段 包含了追加字段 pic
        $field = [
            'id',
            'fang_name',
            'fang_pic',
            'fang_shi',
            'fang_ting'
        ];
        $data  = Fang::where('is_recommend', '1')->orderBy('id', 'desc')->limit(5)->get($field);
        return $data;

    }

    // 房源属性
    public function attr(Request $request)
    {
        // 指定要获取的属性
        $field_name = $request->get('field');
        // 字段的ID号 父级ID
        $id = Fangattr::where('field_name', $field_name)->value('id');
        // 获取的数据
        $data = Fangattr::where('pid', $id)->limit(4)->get(['id', 'name', 'icon']);
        return $data;
    }

    // 房源列表
    public function fang(Request $request)
    {
        // 返回的字段
        $field   = [
            'id',
            'fang_name',
            'fang_pic',
            'fang_shi',
            'fang_ting',
            'fang_rent',
            'fang_build_area'
        ];
        $builder = Fang::select($field);
        // 条件的过滤
        if ($request->get('fieldname')) {
            $builder->where($request->get('fieldname'), $request->get('fieldvalue'));
        }
        // 分页查询
        $data = $builder->paginate(10);
        return $data;
    }

    // 使用es来进行搜索
    public function esfang(Request $request)
    {
        // 根据关键词查询
        $kw = $request->get('kw');
        if (empty($kw)) {
            return ['status' => 10000, 'msg' => '没有数据', 'data' => []];
        }
        // 调用es类来查询
        $client = \Elasticsearch\ClientBuilder::create()->setHosts(config('es.host'))->build();
        // 查询参数
        $params = [
            'index' => 'fang',
            'type'  => '_doc',
            'body'  => [
                'query' => [
                    'match' => [
                        'fang_desn' => [
                            'query' => $kw
                        ]
                    ]
                ]
            ]
        ];
        // 执行查询
        $results = $client->search($params);
        $total   = $results['hits']['total'];
        // 判断是否查询到结果
        if ($total > 0) {
            // 房源ID
            $ids = array_column($results['hits']['hits'], '_id');

            $field = [
                'id',
                'fang_name',
                'fang_pic',
                'fang_shi',
                'fang_ting',
                'fang_rent',
                'fang_build_area'
            ];
            $data  = Fang::select($field)->whereIn('id', $ids)->paginate(10);
            return $data;
        }

        return ['status' => 10009, 'msg' => '没有存数据', 'data' => []];
    }

    // 房源详情
    public function detail(Fang $fang)
    {
        // 得到房东
        $fang->owner;
        // 房屋配置
        $configArr    = explode(',', $fang->fang_config);
        $fang->config = Fangattr::whereIn('id', $configArr)->get(['id', 'name', 'icon']);
        // 房屋朝向
        $fang->direction = Fangattr::where('id', $fang->fang_direction)->value('name');
        // 房子图片
        $fang->piclist = array_map(function ($item) {
            return config('url.domain') . $item;
        }, explode('#', $fang->fang_pic));
        return $fang;
    }
}
