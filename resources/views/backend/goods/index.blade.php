@extends('backend.layouts.main')

@section('title', '商品列表')

@section('css')
    <link rel="stylesheet" type="text/css" href="/backend/vendor/layui/css/layui.css">
@endsection

@section('page-header')
    <div class="page-header card">
        <div class="card-block">
            <h5 class="m-b-10">商品列表</h5>
            <p class="text-muted m-b-10"></p>
            <ul class="breadcrumb-title b-t-default p-t-10">
                <li class="breadcrumb-item">
                    <a href="index.html"> <i class="fa fa-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">商品</a>
                </li>
                <li class="breadcrumb-item"><a href="#!">商品列表</a>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">

            <div class="card">
                <div class="card-header">
                    <h5></h5>
                    <a href="{{ route('admin.goods.create') }}" class="btn btn-primary  f-right d-inline-block" >
                        <i class="icofont icofont-plus m-r-5"></i> 添加商品
                    </a>
                </div>
                <div class="card-block">
                    <div class="table-responsive">
                        <div class="table-content">
                            <div class="project-table">
                                <table class="layui-hide layui-bg-blue" id="goods"  lay-filter="goods"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/vendor/layui/layui.js "></script>
    <script type="text/html" id="operate">
        <a href="{{ route('admin.goods.edit') }}/@{{ d.id }}"  class="btn btn-primary btn-mini" lay-event="edit" >编辑</a>
        <button data-id="@{{ d.id }}" class="btn btn-danger btn-mini" lay-event="del">下架</button>
        <button data-id="@{{ d.id }}" class="btn btn-danger btn-mini" lay-event="grounding">上线</button>
    </script>


    <script type="text/html" id="sort">
        <button data-id="@{{ d.sort }}" class="btn btn-danger btn-mini" lay-event="sort">@{{ d.sort }}</button>
    </script>


    <script>
        layui.use('table', function(){
            var table = layui.table ,form = layui.form;
            table.render({
                elem: '#goods',
                method:'post',
                url:'{{ route('admin.goods.index') }}',
                where: {_token: '{{ csrf_token() }}'},
                cellMinWidth: 80,
                cols: [[
                    {type: 'checkbox'},
                    {field:'id', title:'ID', width:60, unresize: true, sort: true},
                    {field:'title', title:'商品名', templet: '#usernameTpl'},
                    {field:'price', title:'售价'},
                    {field:'stock_quantity', title: '库存'},
                    {field:'sale_quantity', title:'已售', width:85},
                    {field:'status', title:'状态'},
                    {title:'排序' , toolbar:'#sort'},
                    {field:'updated_at', title:'更新时间'},
                    { title:'操作', toolbar:'#operate'}
                ]],
                page: true
            });

            //监听工具条
            table.on('tool(goods)', function(obj){
                var data = obj.data;
               if(obj.event === 'del'){
                    layer.confirm('确认下架商品', function(index){
                        $.post('{{ route('admin.goods.delete') }}', {id:data.id}, function () {
                            layer.msg('下架成功');
                            layer.close(index);
                            table.reload('goods');
                        }, 'json');
                    });
                }

                if(obj.event === 'grounding'){
                    layer.confirm('确认上线此商品', function(index){
                        $.post('{{ route('admin.goods.grounding') }}', {id:data.id}, function () {
                            layer.msg('上线成功');
                            layer.close(index);
                            table.reload('goods');
                        }, 'json');
                    });
                }


                if(obj.event === 'sort'){
                    var id = data.id;
                    var sort = data.sort;
                    layer.open({
                      type: 1,
                      skin: 'layui-layer-rim', //加上边框
                      area: ['200px', '150px'], //宽高
                      title:" ",
                      content: '<table width="100%" border="0" style="line-height:30px;text-align:center;color:black;"><tr><td><input name="sort" type="number" value="'+sort+'" style="border:1px solid;margin-bottom:15px;" placeholder="数字越小排序越前" /></td></tr><tr><td><input type="button" onClick="sort('+id+');" value="确认修改"></td></tr></table>'
                    });
                }


            });

            //监听锁定操作
            form.on('checkbox(lockDemo)', function(obj){
                layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
            });
        });

        function sort(id){
            var sort = $("input[name=sort]").val();
            $.post('{{ route('admin.goods.sort') }}', {id:id,sort:sort}, function () {
                layer.msg("修改成功！");
                setTimeout(function(){
                    window.location.reload();
                },3000);
                return;
            }, 'json');
        }
    </script>
@endsection