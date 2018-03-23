@extends('backend.layouts.main')

@section('title', '订单列表')

@section('css')
    <link rel="stylesheet" type="text/css" href="/backend/vendor/layui/css/layui.css">
    <style>
        .layui-form-pane .layui-form-label {
            white-space: inherit !important;
            height:auto !important;
        }
    </style>
@endsection

@section('page-header')
    <div class="page-header card">
        <div class="card-block">
            <h5 class="m-b-10">订单列表</h5>
            <p class="text-muted m-b-10"></p>
            <ul class="breadcrumb-title b-t-default p-t-10">
                <li class="breadcrumb-item">
                    <a href=""> <i class="fa fa-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">订单</a>
                </li>
                <li class="breadcrumb-item"><a href="#!">订单列表</a>
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
                    <form>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <select name="status" class="form-control">
                                    <option value="0">订单状态</option>
                                    @foreach(config('order.status') as $value => $name)
                                        <option value="{{ $value }}" @if($value == $status) selected  @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select name="mode_of_payment" class="form-control">
                                    <option value="0">支付方式</option>
                                    @foreach(config('order.payment') as $value => $name)
                                        <option value="{{ $value }}" @if($value == $modeOfPayment) selected  @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" placeholder="手机号" name="phone" value="{{ $phone }}">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" placeholder="示例：输入201803查找2018年3月订单" name="no" value="{{ $no }}">
                            </div>
                            <div class="col">
                                <input type="submit" class="btn btn-primary btn-sm waves-effect waves-light" value="搜索">
                            </div>
                        </div>
                    </form>

                    <div class="col">
                        <a href="#" onclick="excel();"><img src="/backend/images/download-icon.png" height="20px"></a>
                    </div>
                    <div class="table-responsive">

                        <div class="table-content">

                            <div class="project-table">
                                <div class="project-table">
                                    <table class="layui-table layui-form" lay-size="mini">
                                        <colgroup>
                                            <col width="150">
                                            <col width="200">
                                            <col>
                                        </colgroup>
                                        <thead>
                                        <tr>
                                            <th>订单号</th>
                                            <th>用户</th>
                                            <th>商品</th>
                                            <th>属性</th>
                                            <th>订单金额</th>
                                            <th>订单时间</th>
                                            <th>订单状态</th>
                                            <th>支付方式</th>
                                            <th>支付时间</th>
                                            <th>支付金额</th>
                                            <th>用户留言</th>
                                            <th>提货方式</th>
                                            <th>物流单号</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($orders as $item)
                                            <tr>
                                                <td>{{ $item->no }}</td>
                                                <td>{{ $item->user->phone ?? '' }}</td>
                                                <td>{{ $item->goods_title }}</td>
                                                <td>
                                                    @if($item->attributes != null)
                                                        <?php $attributes = explode(',',$item->attributes);?>
                                                    @foreach($attributes as $k=>$attribute)
                                                        <span style="border:1px solid green;">{{$attribute}}</span>
                                                    @endforeach
                                                    @else
                                                    @endif
                                                </td>
                                                <td>{{ $item->amount }}</td>
                                                <td>{{ $item->created_at }}</td>
                                                <td>{{ config('order.status')[$item->status] ?? ''}}</td>
                                                <td>{{ config('order.payment')[$item->mode_of_payment] ?? '' }}</td>
                                                <td>{{ $item->time_of_payment }}</td>
                                                <td>{{ $item->payment }}</td>
                                                <td>{{ $item->remark }}</td>
                                                <td>{{ $item->logistics_mode == 1 ? '自提' : '快递' }}</td>
                                                <td>{{ $item->express_no }}</td>
                                                <td>
                                                    @if($item->status == 2)
                                                        <button class="layui-btn layui-bg-blue layui-btn-sm" lay-submit="" lay-filter="deliver"
                                                                data-id="{{ $item->id }}"
                                                                data-name="{{ $item->receiving_name }}"
                                                                data-phone="{{ $item->receiving_phone }}"
                                                                data-address="{{ $item->receiving_address }}"
                                                                data-remark="{{ $item->remark }}">发货</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            暂时没有数据
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{ $orders->appends([
                                      'no' => $no,
                                      'status' => $status,
                                      'phone' => $phone,
                                      'mode_of_payment' => $modeOfPayment,
                                  ])->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div id="deliver" style="display: none;padding: 20px">
        <form class="layui-form layui-form-pane" action="">
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">收货人姓名：<span id="name"></span></label>
                <label class="layui-form-label">收货人手机：<span id="phone"></span></label>
                <label class="layui-form-label">收货人地址：<span id="address"></span></label>
                <label class="layui-form-label">留言：<span id="remark"></span></label>

                <div class="layui-input-block">
                    <input type="text" name="express_no" class="layui-input" placeholder="如有单号则填没有则不写">
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-bg-blue col-lg-12" lay-submit="" lay-filter="deliver-submit">确定</button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="/backend/vendor/layui/layui.js "></script>
    <script>
        layui.use('table', function(){
            var table = layui.table ,form = layui.form;

            //监听性别操作
            form.on('submit(deliver-submit)', function(data){
                $.post('{{ route('admin.order.deliver') }}', {id:data.field.id, express_no:data.field.express_no}, function (result) {
                    layer.alert(result.message, function () {
                        layer.closeAll();
                        document.location.reload();
                    });
                }, 'json');

                return false;
            });

            form.on('submit(deliver)', function (data) {
                $('#name').html(data.elem.getAttribute('data-name'));
                $('#phone').html(data.elem.getAttribute('data-phone'));
                $('#address').html(data.elem.getAttribute('data-address'));
                $('#remark').html(data.elem.getAttribute('data-remark'));
                $('#deliver form').append('<input type="hidden" name="id" value="' + data.elem.getAttribute('data-id')  +  '">');
                layer.open({
                    type: 1,
                    shade: 0.2,
                    title: '发货',
                    area: ['400px', '400px'],
                    content: $('#deliver')
                });
            });
        });
        function excel(){
            var url = window.location.href;
            var url = url.split("?");
            var url = url[1];
            if(url == undefined){
                return alert("不能导出所有数据！");
            }
            //var base = "http://localhost/admin/order/excel?";
            var base = "http://app.szsousou.com/admin/order/excel?";
            var url = base + url;
            window.location.href = url;
        }
    </script>
@endsection