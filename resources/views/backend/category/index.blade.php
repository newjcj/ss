@extends('backend.layouts.main')

@section('title', '商品列表')

@section('css')
    <link rel="stylesheet" type="text/css" href="/backend/vendor/layui/css/layui.css">
@endsection

@section('page-header')
    <div class="page-header card">
        <div class="card-block">
            <h5 class="m-b-10">分类列表</h5>
            <p class="text-muted m-b-10"></p>
            <ul class="breadcrumb-title b-t-default p-t-10">
                <li class="breadcrumb-item">
                    <a href="{{config('view.dashboard')}}"> <i class="fa fa-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">分类</a>
                </li>
                <li class="breadcrumb-item"><a href="#!">分类列表</a>
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
                    {{--<a href="{{ route('admin.goods.create') }}" class="btn btn-primary  f-right d-inline-block" >--}}
                        {{--<i class="icofont icofont-plus m-r-5"></i> 添加商品--}}
                    {{--</a>--}}
                </div>
                <div class="card-block">
                    <div class="table-responsive">
                        <div class="table-content">
                            <div class="project-table">
                                <table class="table table-striped table-bordered table-hover" id="sample_1">
                                    <thead>
                                    <tr class="odd">
                                        <th style="text-align: center">列表</th>
                                        <th style="text-align: center">图片</th>
                                        <th style="text-align: center">分类名</th>
                                        <th style="text-align: center"> 操作</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1;?>
                                    @foreach($categorys as $item)
                                        <tr style="text-align: center">
                                            <td>{{ $i }}</td>
                                            <?php $images = $item->image;?>
                                            <td><img src="{{ $images[0] }}" alt="" style="width:60px;"/></td>
                                            <?php $color=["#FF69B4",'#800080','#aaa','#0000FF','#1E90FF','#2F4F4F'];?>
                                            <?php $num = count(explode('-',$item->orderpath))-2;?>
                                            <td style="text-align:left;color:<?php echo $color[$num];?>;"><?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$num);?>{{$item->name}}</td>
                                            <td style="text-align: center">
                                                <button type="button" class="btn btn-primary" onclick="location.href='/admin/category/detail?id={{$item->id}}'">编辑</button>
                                                <button type="button" class="btn btn-danger" onclick="_delete({{$item->id}})">删除</button>

                                            </td>
                                        </tr>
                                        <?php $i++;?>
                                    @endforeach


                                    </tbody>
                                </table>
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
    <script>
        function _delete(id) {
            $.ajax({
                    url: '/admin/category/delete',
                    data: {
                        _token:"{{csrf_token()}}",
                        id: id
                    },
                    type: 'post',
                    dataType: 'json',
                    async:false,
                    success: function (data) {
                        if (data.status !== 1) {
                            layer.open({
                                title: '提示'
                                , content: data.message,
                                yes: function () {
                                    window.location.href = '/admin/category/list';
                                },
                                cancel: function () {
                                    //右上角关闭回调
                                    window.location.href = '/admin/category/list';
                                }
                            });
                        } else {
                            layer.open({
                                title: '提示'
                                , content: data.message,
                                yes: function () {
                                    window.location.href = '/admin/category/list';
                                },
                                cancel: function () {
                                    //右上角关闭回调
                                    window.location.href = '/admin/category/list';
                                }
                            });
                        }
                    }
                });
        }
    </script>











    <script type="text/html" id="operate">
        <a href="{{ route('admin.goods.edit') }}/@{{ d.id }}"  class="btn btn-primary btn-mini" lay-event="edit" >编辑</a>
        <button data-id="@{{ d.id }}" class="btn btn-danger btn-mini" lay-event="del">删除</button>
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
                    {field:'updated_at', title:'更新时间'},
                    { title:'操作', toolbar:'#operate'}
                ]],
                page: true
            });

            //监听工具条
            table.on('tool(goods)', function(obj){
                var data = obj.data;
               if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){
                        $.post('{{ route('admin.goods.delete') }}', {id:data.id}, function () {
                            layer.msg('删除成功');
                            layer.close(index);
                            table.reload('goods');
                        }, 'json');
                    });
                }
            });

            //监听锁定操作
            form.on('checkbox(lockDemo)', function(obj){
                layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
            });
        });
    </script>
@endsection