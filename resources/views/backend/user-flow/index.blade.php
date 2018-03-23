@extends('backend.layouts.main')

@section('title', '资金流水')

@section('css')
    <link rel="stylesheet" type="text/css" href="/backend/vendor/layui/css/layui.css">
@endsection

@section('page-header')
    <div class="page-header card">
        <div class="card-block">
            <h5 class="m-b-10">资金流水</h5>
            <p class="text-muted m-b-10"></p>
            <ul class="breadcrumb-title b-t-default p-t-10">
                <li class="breadcrumb-item">
                    <a href=""> <i class="fa fa-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">用户</a>
                </li>
                <li class="breadcrumb-item"><a href="#!">资金流水</a>
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

                    <div class="table-responsive">
                        <form>
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <select name="asset_type" class="form-control">
                                        <option value="0">资金类型</option>
                                        @foreach(config('userflow.asset_type') as $value => $name)
                                            <option value="{{ $value }}" @if($value == $assetType) selected  @endif>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select name="trade_type" class="form-control">
                                        <option value="0">交易类型</option>
                                        @foreach(config('userflow.trade_type') as $value => $name)
                                            <option value="{{ $value }}" @if($value == $tradeType) selected  @endif>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" placeholder="单号" name="voucher_id" value="{{ $voucherId }}">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" placeholder="用户" name="user_phone" value="{{ $userPhone }}">
                                </div>
                                <div class="col">
                                    <input type="submit" class="btn btn-primary btn-sm waves-effect waves-light" value="搜索">
                                </div>
                            </div>
                        </form>
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
                                        <th>单号</th>
                                        <th>用户</th>
                                        <th>流水类型</th>
                                        <th>资金类型</th>
                                        <th>交易类型</th>
                                        <th>发生金额</th>
                                        <th>发生时间</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $sum = 0; ?>
                                    @forelse($userFlow as $item)
                                        <?php $sum += $item->amount; ?>
                                    <tr>
                                        <td>{{ $item->voucher_id }}</td>
                                        <td>{{ $item->user->phone ?? '' }}</td>
                                        <td>{{ config('userflow.type')[$item->type] }}</td>
                                        <td>{{ config('userflow.asset_type')[$item->asset_type] }}</td>
                                        <td>{{ config('userflow.trade_type')[in_array($item->trade_type, [1,2]) ? $item->trade_type . $item->user_level : $item->trade_type] }}</td>
                                        <td>{{ $item->amount }}</td>
                                        <td>{{ $item->created_at }}</td>
                                    </tr>
                                    @empty
                                        暂时没有数据
                                    @endforelse
                                    <tr>
                                        <td colspan="5"  align="right">本页合计返利</td>
                                        <td colspan="2" >{{ $sum  }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $userFlow->appends([
                                      'asset_type' => $assetType,
                                      'trade_type' => $tradeType,
                                      'voucher_id' => $voucherId,
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

            //监听性别操作
            form.on('switch(sexDemo)', function(obj){
                layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
            });

            //监听锁定操作
            form.on('checkbox(lockDemo)', function(obj){
                layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
            });
        });
    </script>
@endsection