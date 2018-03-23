@extends('backend.layouts.main')

@section('title', '提现管理')

@section('css')
    <link rel="stylesheet" type="text/css" href="/backend/vendor/layui/css/layui.css">
@endsection

@section('page-header')
    <div class="page-header card">
        <div class="card-block">
            <h5 class="m-b-10">提现管理</h5>
            <p class="text-muted m-b-10"></p>
            <ul class="breadcrumb-title b-t-default p-t-10">
                <li class="breadcrumb-item">
                    <a href=""> <i class="fa fa-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">财务</a>
                </li>
                <li class="breadcrumb-item"><a href="#!">提现管理</a>
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
                </div>
                <div class="card-block">
                    <form>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <select name="type" class="form-control">
                                    <option value="0">类型</option>
                                    <option value="4">学分</option>
                                    <option value="3">积分</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select name="status" class="form-control">
                                    <option value="0">状态</option>
                                    <option value="1">未处理</option>
                                    <option value="2">已提现</option>
                                    <option value="3">已拒绝</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" placeholder="手机号" name="user_phone" value="{{ $userPhone }}">
                            </div>
                            <div class="col">
                                <input type="submit" class="btn btn-primary btn-sm waves-effect waves-light" value="搜索">
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">

                        <div class="table-content">
                            <div class="project-table">
                                <table class="layui-table" lay-size="mini">
                                    <colgroup>
                                        <col width="150">
                                        <col width="200">
                                        <col>
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th>流水单号</th>
                                        <th>用户</th>
                                        <th>类型</th>
                                        <th>金额</th>
                                        <th>手续费</th>
                                        <th>应到金额</th>
                                        <th>提现银行</th>
                                        <th>提现卡号</th>
                                        <th>提现户名</th>
                                        <th>申请时间</th>
                                        <th>处理时间</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @forelse($withdrawal as $item)
                                    <tr>
                                        <td>{{ $item->voucher_id }}</td>
                                        <td>{{ $item->user_phone }}</td>
                                        <td>{{ $item->type == 3 ? '积分' : '学分' }}</td>
                                        <td>{{ $item->amount  }}</td>
                                        <td>{{  bcmul($item->poundage, $item->amount)  }}</td>
                                        <td>{{ bcsub($item->amount, bcmul($item->poundage, $item->amount))  }}</td>
                                        <td>{{ $item->bank->bank_name  }}</td>
                                        <td>{{ $item->bank->bank_card  }}</td>
                                        <td>{{ $item->bank->name  }}</td>
                                        <td>{{ $item->created_at  }}</td>
                                        <td>{{ $item->created_at  }}</td>
                                        <td>{{ $item->status == 1 ? '未处理' :( $item->status == 2 ? '已提现' : '已拒绝') }}</td>
                                        <td>
                                            @if($item->status == 1)
                                            <button class="layui-btn layui-bg-blue layui-btn-sm" lay-submit="" lay-filter="agree" data-id="{{ $item->id }}">确认</button>
                                            <button class="layui-btn layui-bg-blue layui-btn-sm" lay-submit="" lay-filter="reject"  data-id="{{ $item->id }}">拒绝</button>
                                            @else
                                                ---
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="16"> 暂时没有数据</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $withdrawal->appends([
                                     'type' => $type,
                                     'status' => $status,
                                     'user_phone' => $userPhone,
                                 ])->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/vendor/layui/layui.js "></script>
    <script>
        layui.use('table', function(){
            var table = layui.table ,form = layui.form;

            form.on('submit(agree)', function(data){
                $.post('{{ route('admin.withdrawal.agree') }}', {id:data.elem.getAttribute('data-id')}, function (result) {
                    layer.alert(result.message, function () {
                        layer.closeAll();
                        document.location.reload();
                    });
                }, 'json');
                return false;
            });

            form.on('submit(reject)', function(data){
                $.post('{{ route('admin.withdrawal.reject') }}', {id:data.elem.getAttribute('data-id')}, function (result) {
                    layer.alert(result.message, function () {
                        layer.closeAll();
                        document.location.reload();
                    });
                }, 'json');
                return false;
            });
        });
    </script>
@endsection