@extends('admin.common.main')


@section('cnt')
    <nav class="breadcrumb">
        <i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span> 用户中心
        <span class="c-gray en">&gt;</span> 文章列表
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px"
           href="javascript:location.replace(location.href);" title="刷新"><i class="Hui-iconfont">&#xe68f;</i></a>
    </nav>
    {{-- 消息提示 --}}
    @include('admin.common.msg')

    <div class="page-container">
        <form method="get" class="text-c" onsubmit="return dopost()"> 日期范围：
            <input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'datemax\')||\'%y-%M-%d\'}' })" id="datemin"
                   class="input-text Wdate" autocomplete="off" style="width:120px;">
            -
            <input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'datemin\')}',maxDate:'%y-%M-%d' })"
                   id="datemax" class="input-text Wdate" autocomplete="off" style="width:120px;">
            <input type="text" class="input-text" style="width:250px" placeholder="文章标题"
                   value="{{ request()->get('title') }}" id="title" autocomplete="off">
            <button type="submit" class="btn btn-success radius"><i class="Hui-iconfont">&#xe665;</i> 搜文章</button>
        </form>
        <div class="cl pd-5 bg-1 bk-gray mt-20">
        <span class="l">
            <a href="{{ route('admin.article.create') }}" class="btn btn-primary radius">
                <i class="Hui-iconfont">&#xe600;</i> 添加文章
            </a>
        </span>
        </div>
        <div class="mt-20">
            <table class="table table-border table-bordered table-hover table-bg table-sort">
                <thead>
                <tr class="text-c">
                    <th width="80">ID</th>
                    <th width="100">文章标题</th>
                    <th width="130">加入时间</th>
                    <th width="100">操作</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('js')
    <script>
        // 列表显示
        var dataTable = $('.table-sort').dataTable({
            // 下拉的分页数量
            lengthMenu: [5, 10, 15, 20, 100],
            // 隐藏dataTables自带的搜索
            searching: false,
            columnDefs: [
                // 索引第3列，不进行排序
                {targets: [3], orderable: false}
            ],
            // 开启服务器端分页 开启ajax
            serverSide: true,
            // 进行ajax请求
            ajax: {
                // 请求地址
                url: '{{ route('admin.article.index') }}',
                // 请求方式
                type: 'get',
                // ret.后面的变量名，就是前段传给后端的数据变量名
                data: function (ret) {
                    ret.datemin = $('#datemin').val();
                    ret.datemax = $('#datemax').val();
                    ret.title = $('#title').val();
                }
            },
            // 指定每一列显示的数据
            columns: [
                // 总的数量与表格的列的数量一致，不多也不少
                // 字段名称与sql查询出来的字段时要保持一致，就是服务器返回数据对应的字段名称
                // defaultContent 和 className 可选参数
                // {'data': '字段名称1', "defaultContent": "默认值", 'className': '类名'},
                {'data': 'id', 'className': 'text-c'},
                {'data': 'title'},
                {'data': 'created_at'},
                {'data': 'aaa', "defaultContent": "默认值"},  // 展示数据库没有的字段，'data'名随便写
            ],
            // 回调方法
            // row 当前行的dom对象
            // data 当前行的数据
            // dataIndex 当前行的数据索引
            createdRow: function (row, data, dataIndex) {
                // 当前id
                var id = data.id;
                // 行的最后一列
                var td = $(row).find('td:last-child');
                // 显示的html内容
                var html = `
                <a href="/admin/article/${id}/edit" class="label label-secondary radius">修改</a>
                <a href="/admin/article/${id}" onclick="return delArticle(event,this)" class="label label-warning radius">删除</a>
                `;
                // html添加到td中
                td.html(html);
            }
        });

        // 表单提交
        function dopost() {

            // 手动调用一次dataTable插件请求 让表单通过dataTable的ajax方式提交
            dataTable.api().ajax.reload();
            // dataTable.ajax.reload();  // 如果页面console提示api()不能用，就把api()删掉

            // 取消表单默认行为 不让表单自己提交
            return false;
        }

        // async await  promise  异步变同步
        async function delArticle(evt, obj) {
            evt.preventDefault();   // 取消默认行为
            console.log(obj);
            // console.log(evt);
            // <a href="/admin/article/12" onclick="return delArticle(event,this)" class="label label-warning radius">删除</a>

            // 请求的URL地址
            let url = $(obj).attr('href');
            // 发起ajax
            // fetch原生的，浏览自带
            let ret = await fetch(url, {
                method: 'delete',
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            let json = await ret.json();
            console.log(json);  // id: 1  后端返回来的数据
            // 取消默认行为
            return false;
        }
    </script>
@endsection
