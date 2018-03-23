@extends('backend.layouts.main')

@section('title', '添加商品')

@section('css')
    <link rel="stylesheet" type="text/css" href="/backend/vendor/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="/backend/vendor/LXXUploadNeeded/LXXUploadPic.css">
    <style>
        .box {
            isplay: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            /*padding: 25px 3px 10px 20px;*/
        }

        .box .item {
            border-radius: 4px;
            width: calc(20% - 17px);
            margin-right: 21px;
            margin-bottom: 27px;
        }

        .box .item .preview {
            position: relative;
            max-height: 211px;
            overflow: hidden;
        }

        .embed-responsive {
            position: relative;
            display: block;
            width: 100%;
            padding: 0;
            overflow: hidden;
        }

        img {
            vertical-align: middle;
            border-style: none;
        }

        .item-control {
            margin-right: 17px;
            line-height: 40px;
            height: 40px;
            opacity: .85;
        }


    </style>
@endsection

@section('page-header')
    <div class="page-header card">
        <div class="card-block">
            <h5 class="m-b-10">添加商品</h5>
            <p class="text-muted m-b-10"></p>
            <ul class="breadcrumb-title b-t-default p-t-10">
                <li class="breadcrumb-item">
                    <a href=""> <i class="fa fa-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#">商品</a>
                </li>
                <li class="breadcrumb-item"><a href="#">添加商品</a>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">

            <div class="card">
                <div class="card-block">
                    <form method="post" action="{{ route('admin.goods.create') }}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-12 col-xl-12 m-b-30">
                                <h4 class="sub-title">商品名称</h4>
                                <input type="text" class="form-control" name="title">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-xl-12 m-b-30">
                                <h4 class="sub-title">商品所属分类</h4>
                                <div class="col-sm-4">
                                    <select class="bs-select form-control" name="category_id" tabindex="-98">
                                        <?php echo $category->getOptions();?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-form-item" pane="">
                                <span class="sub-title">是否热销</span>
                                <div class="layui-input-block">
                                    <span>是</span>
                                    <input type="radio" name="is_hot" value="1" title="是" checked>
                                    <span>不是</span>
                                    <input type="radio" name="is_hot" value="0" title="不是" >
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-form-item" pane="">
                                <span class="sub-title">是否用老的积分机制</span>
                                <div class="layui-input-block">
                                    <span>是</span>
                                    <input type="radio" name="vocational" value="" title="是" onclick="showIntegral('')" checked>
                                    <span>不是</span>
                                    <input type="radio" name="vocational" value="1" title="不是" onclick="showIntegral(1)" >
                                </div>
                            </div>
                        </div>
                        <div class="pattribute">
                            <h4 class="sub-title">商品属性设置</h4>
                            <div class="layui-input-block attribute" style="margin-top:20px">
                                <div>属性名</div>
                                <input type="text" name="attribute[]" >
                                <button class="btn-danger attributeDel">删除属性</button>
                                <button class="btn-primary attributeAdd">添加属性</button>
                                <br>
                                <div>属性值列表</div>
                                <div class="attributeList">
                                    <input type="text" name="attributelist[]"  >
                                    <button class="btn-danger attributeListDel">删除属性值</button>
                                    <button class="btn-primary attributeAddList" type="button">添加属性值</button>
                                </div>
                            </div>
                        </div>
                        {{--<div class="pattribute" id="app">--}}
                            {{--<h4 class="sub-title">商品属性设置</h4>--}}
                            {{--<div class="layui-input-block attribute" style="margin-top:20px">--}}
                                {{--<p>${ message }</p>--}}
                                {{--<input type="text" v-model="one">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="row">
                            <div class="col-sm-12 col-xl-12 m-b-30">
                                <h4 class="sub-title">赠送话费</h4>
                                <input type="text" class="form-control" name="telephone_fare">
                            </div>
                        </div>
                        <h4 class="sub-title">商品图片</h4>
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

                        <div class="row  m-b-3">
                            <div class="col-sm-6 col-xl-6 m-b-30">
                                <h4 class="sub-title">原价</h4>
                                <input type="text" class="form-control" name="original_price">
                            </div>
                            <div class="col-sm-6">
                                <h4 class="sub-title">售价</h4>
                                <input type="text" class="form-control" name="price">
                            </div>
                        </div>
                        <div class="row  m-b-3">
                            <div class="col-sm-6 col-xl-6 m-b-30">
                                <h4 class="sub-title">库存</h4>
                                <input type="text" class="form-control" name="stock_quantity">
                            </div>
                            <div class="col-sm-6">
                                <h4 class="sub-title">已售</h4>
                                <input type="text" class="form-control" name="sale_quantity">
                            </div>
                        </div>

                        <div class="row integal" >
                            <div class="col-sm-6">
                                <h4 class="sub-title">推销员</h4>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">I激励</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="seller_one_integral" value="0">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">II激励</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="seller_two_integral" value="0">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">III激励</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="seller_three_integral" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mobile-inputs">
                                <h4 class="sub-title">代理商</h4>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">一级</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="agent_one_integral" value="0">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">二级</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="agent_two_integral" value="0">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">运营商</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="agent_three_integral" value="0">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-xl-12 m-b-30">
                                <h4 class="sub-title">副标题</h4>
                                <textarea rows="5" cols="5" class="form-control" placeholder="副标题"
                                          name="desc"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-xl-12 m-b-30">
                                <h4 class="sub-title">商品详情</h4>
                                <textarea id="container" rows="5" cols="5" class="form-control"
                                          placeholder="Default textarea" name="detail"></textarea>
                            </div>
                        </div>
                        <input type="hidden" id="attributedata" name="attributedata">
                        <button class="btn btn-primary btn-block add">确认添加</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script src="/backend/vendor/layui/layui.js "></script>
    <script>

        //商品属性
            var attributeAdd = $('.attributeAdd');
            var attributeAddList = $('.attributeAddList');
            var init = function(){
                var str = '';
                var attribute = $('.attribute');
                attribute.each(function(i,n){
                    str += $(n).find('input[name="attribute[]"]').val()+',';
                    $(n).find('.attributeList').each(function(i,n){
                        str += $(n).find('input[name="attributelist[]"]').val()+',';
                    });
                    str += "|";
                })
                $('#attributedata').val(str);
                console.log(str);
            };
            var attributeAddStr = '  <div class="layui-input-block attribute" style="margin-top:20px"><div>属性名</div><input type="text" name="attribute[]" ><button class="btn-danger attributeDel">删除属性</button><button class="btn-primary attributeAdd">添加属性</button><br><div>属性值列表</div><div class="attributeList"><input type="text" name="attributelist[]"  ><button class="btn-danger attributeListDel">删除属性值</button><button class="btn-primary attributeAddList" type="button">添加属性值</button></div></div>';
            var attributeAddListStr = '<div class="attributeList"><input type="text" name="attributelist[]"  ><button class="btn-danger attributeListDel">删除属性值</button><button class="btn-primary attributeAddList" type="button">添加属性值</button></div>';
            $(document).on('click','.attributeAddList',function(){
                $(this).parents('.attribute').append(attributeAddListStr);
                $(this).remove();
                init();
            });
            $(document).on('click','.attributeAdd',function(){
                $(this).parents('.pattribute').append(attributeAddStr);
                $(this).remove();
                init();
            });
            $(document).on('click','.attributeListDel',function(){
                $(this).parents('.attributeList').remove();
            });
            $(document).on('click','.attributeDel',function(){
                $(this).parents('.attribute').remove();
            });
            $('.add').click(function(){
                init();
            });


        //是否显示integral
        function showIntegral(i){

            if(i === 1){
                console.log(333);
                $('.integal').css({display:'none'});
            }else{
                $('.integal').css({display:'flex'});
            }
        }

        layui.use('table', function () {
            var table = layui.table, form = layui.form;

            //监听性别操作
            form.on('switch(sexDemo)', function (obj) {
                layer.tips(this.value + ' ' + this.name + '：' + obj.elem.checked, obj.othis);
            });

            //监听锁定操作
            form.on('checkbox(lockDemo)', function (obj) {
                layer.tips(this.value + ' ' + this.name + '：' + obj.elem.checked, obj.othis);
            });

            function getObjectURL(file) {
                var url = null;
                if (window.createObjectURL != undefined) {
                    url = window.createObjectURL(file)
                } else if (window.URL != undefined) {
                    url = window.URL.createObjectURL(file)
                } else if (window.webkitURL != undefined) {
                    url = window.webkitURL.createObjectURL(file)
                }
                return url
            }

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
                        currentObj.parent().append('<input type="hidden" name="images[]" value="' + result.data   + '"/>')
                    }
                })
            });

            $('.delete-img').click(function () {
                    var currentObj = $(this);
                currentObj.addClass('layui-hide');
                currentObj.parent().css('background', '');
                currentObj.next().next().val('');

            });
        });

        var ue = UE.getEditor('container');
        ue.ready(function () {
        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });

    </script>
@endsection
@include('vendor.ueditor.assets')