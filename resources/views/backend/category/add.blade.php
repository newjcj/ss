@extends('backend.layouts.main')

@section('title', '商品列表')

@section('css')
    <link rel="stylesheet" type="text/css" href="/backend/vendor/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="/backend/vendor/LXXUploadNeeded/LXXUploadPic.css">
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
                <li class="breadcrumb-item"><a href="/admin/category/list">分类</a>
                </li>
                <li class="breadcrumb-item"><a href="#!">添加分类</a>
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
                                <form class="layui-form" action="" id="uploadForm">
                                    <div class="layui-form-item" >
                                        <label class="layui-form-label">选择父分类</label>
                                        <div class="layui-input-block" >
                                            <select name="pid"  >
                                                <?php echo $category->getOptions();?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">分类名称</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-check"></i>
                                                    </span>
                                                <input value="" type="text" name="name" class="form-control" datatype="/^[\s\S]{1,55}$/"> </div>
                                            <p class="help-block"> </p>
                                        </div>
                                    </div>

                                    <h4 class="sub-title">分类图片</h4>
                                    <div class="row">
                                        <div class="col-sm-12 col-xl-12 m-b-30">
                                            <table class="LXXUploadPic">
                                                <tbody>
                                                <tr>
                                                    <td style="width: 300px; height: 300px;">
                                                        <div class="delete-img layui-hide">删除</div>
                                                        <input type="file" name="file[]">
                                                    </td>
                                                    <td style="width: 300px; height: 300px;">
                                                        <div class="delete-img  layui-hide">删除</div>
                                                        <input type="file" name="file[]">
                                                    </td>
                                                    <td style="width: 300px; height: 300px;">
                                                        <div class="delete-img layui-hide">删除</div>
                                                        <input type="file" name="file[]">
                                                    </td>
                                                    <td style="width: 300px; height: 300px;">
                                                        <div class="delete-img layui-hide">删除</div>
                                                        <input type="file" name="file[]">
                                                    </td>
                                                    <td style="width: 300px; height: 300px;">
                                                        <div class="delete-img layui-hide">删除</div>
                                                        <input type="file" name="file[]">
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="layui-form-item">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" type="button" id="sub" lay-submit lay-filter="formDemo">立即提交</button>
                                        </div>
                                    </div>
                                </form>
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
    {{--<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js "></script>--}}

    <script>
        //上传图片
        $('input[type="file"]').change(function () {
            var currentObj = $(this);
            var file = this.files[0];
            var formData = new FormData();
            formData.append("file", file);

            $.ajax({
                type: "POST",
                url: "{{ route('admin.goods.upload') }}",
                processData: false,
                contentType: false,
                data: formData,
                success: function (result) {
                    currentObj.prev().removeClass('layui-hide');
                    currentObj.parent().css('background', 'url(' + result.data + ') no-repeat center center').css('background-size', '100%');
                    currentObj.parent().append('<input type="hidden" class="cimages" name="images[]" value="' + result.data   + '"/>')
                }
            })
        });

        $('#sub').click(function(){
           $.ajax({
                   url: '/admin/category/postadd',
                   data: $('form').serialize(),
                   type: 'post',
                   dataType: 'json',
                   async:false,
                   success: function (data) {
                       if(data.status !== 1){
                           layer.open({
                               title: '提示'
                               ,content: data.message,
                               yes:function(){
                                   window.location.href='/admin/category/list';
                               }
                           });
                       }else{
                           layer.open({
                               title: '提示'
                               ,content: data.message,
                               yes:function(){
                                   window.location.href='/admin/category/list';
                               }
                           });
                       }
                       return false;
                   }
               });
        });
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