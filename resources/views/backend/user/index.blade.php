@extends('backend.layouts.main')

@section('title', '用户列表')

@section('css')
    <link rel="stylesheet" type="text/css" href="/backend/vendor/layui/css/layui.css">
@endsection

@section('page-header')
    <div class="page-header card">
        <div class="card-block">
            <h5 class="m-b-10">用户列表</h5>
            <p class="text-muted m-b-10"></p>
            <ul class="breadcrumb-title b-t-default p-t-10">
                <li class="breadcrumb-item">
                    <a href=""> <i class="fa fa-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">用户</a>
                </li>
                <li class="breadcrumb-item"><a href="#!">用户列表</a>
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
                                    <option value="0">用户类型</option>
                                    @foreach(config('user.type') as $value => $name)
                                        <option value="{{ $value }}" @if($value == $type) selected  @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select name="role" class="form-control">
                                    <option value="0">用户角色</option>
                                    @foreach(config('user.role') as $value => $name)
                                        <option value="{{ $value }}" @if($value == $role) selected  @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" placeholder="用户手机" name="phone" value="{{ $phone }}">
                            </div>
                            <div class="col">
                                <input type="submit" class="btn btn-primary btn-sm waves-effect waves-light" value="搜索">
                            </div>
                        </div>
                    </form>
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
                                            <th>手机</th>
                                            <th>积分</th>
                                            <th>学分</th>
                                            <th>用户类型</th>
                                            <th>用户角色</th>
                                            <th>注册时间</th>
                                            <th>上级</th>
                                            <th>上级角色</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($users as $item)
                                            <tr>
                                                <td>{{ $item->phone }}</td>
                                                <td>{{ $item->integral }}</td>
                                                <td>{{ $item->credit }}</td>
                                                <td>{{ isset(config('user.type')[$item->type]) ? config('user.type')[$item->type] : '' }}</td>
                                                <td>{{ isset(config('user.role')[$item->special_type]) ? config('user.role')[$item->special_type] : '' }}</td>
                                                <td>{{ $item->created_at }}</td>
                                                <td>{{ $item->parent->phone ?? ''}}</td>
                                                <td>{{ isset($item->parent->special_type) ? config('user.role')[$item->parent->special_type] : '' }}</td>
                                                <td>
                                                    <button class="layui-btn layui-bg-blue layui-btn-sm" lay-submit="" lay-filter="change-asset" data-id="{{ $item->id }}">加减（学/积) 分</button>
                                                    <button class="layui-btn layui-bg-blue layui-btn-sm" lay-submit="" lay-filter="change-role" data-parent_phone="{{ $item->parent->phone ?? '' }}" data-type="{{ $item->type }}" data-special_type="{{ $item->special_type }}" data-id="{{ $item->id }}">修改</button>
                                                </td>
                                            </tr>
                                        @empty
                                            暂时没有数据
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{ $users->appends([
                                      'role' => $role,
                                      'type' => $type,
                                      'phone' => $phone,
                                  ])->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="change-role" style="display: none;padding: 20px">
        <form class="layui-form layui-form-pane" action="">

            <div class="layui-form-item" pane="">
                <label class="layui-form-label">用户类型</label>
                <div class="layui-input-block">
                    <input type="radio" name="type" value="1" title="注册用户">
                    <input type="radio" name="type" value="2" title="创客">
                </div>
            </div>
            <div class="layui-form-item" pane="">
                <label class="layui-form-label">用户角色</label>
                <div class="layui-input-block">
                    <input type="radio" name="special_type" value="0" title="没有角色">
                    <input type="radio" name="special_type" value="1" title="一级代理">
                    <input type="radio" name="special_type" value="2" title="二级代理">
                    <input type="radio" name="special_type" value="3" title="运营商">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">上级</label>
                <div class="layui-input-block">
                    <input type="text" name="parent_phone" required lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <button class="layui-btn layui-bg-blue col-lg-12" lay-submit="" lay-filter="save-role">确定</button>
            </div>
        </form>
    </div>
    <div id="change-asset" style="display: none;padding: 20px">
        <form class="layui-form layui-form-pane pane" action="">
            <div class="layui-form-item" pane="">
                <label class="layui-form-label">资金类型</label>
                <div class="layui-input-block">
                    <input type="radio" name="asset_type" value="3" title="积分">
                    <input type="radio" name="asset_type" value="4" title="学分">
                </div>
            </div>
            <div class="layui-form-item " pane>
                <label class="layui-form-label">操作类型</label>
                <div class="layui-input-block ">
                    <input type="radio" name="type" value="1" title="增加">
                    <input type="radio" name="type" value="2" title="减少">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">(学/积)分</label>
                <div class="layui-input-block">
                    <input type="text" name="amount" required lay-verify="required" placeholder="请输入增加或减少的(学/积)分" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-bg-blue col-lg-12" lay-submit="" lay-filter="save-asset">确定</button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="/backend/vendor/layui/layui.js "></script>
    <script>
        layui.use('table', function(){
            var table = layui.table ,form = layui.form;

            form.on('submit(save-role)', function(data){
                $.post('{{ route('admin.user.change-role') }}', {
                    id:data.field.id, type:data.field.type, special_type:data.field.special_type, parent_phone:data.field.parent_phone
                }, function (result) {
                    layer.alert(result.message, function () {
                        layer.closeAll();
                        document.location.reload();
                    });
                }, 'json');
                return false;
            });

            form.on('submit(change-role)', function (data) {
                $('#change-role form').append('<input type="hidden" name="id" value="' + data.elem.getAttribute('data-id')  +  '">');
                $('#change-role input[name=type][value=' +  data.elem.getAttribute('data-type')  + ']').attr("checked",true);
                $('#change-role input[name=special_type][value=' +  data.elem.getAttribute('data-special_type')  + ']').attr("checked",true);
                $('#change-role input[name=parent_phone]').val(data.elem.getAttribute('data-parent_phone'));
                form.render();
                layer.open({
                    type: 1,
                    shade: 0.2,
                    title: '编辑',
                    area: ['400px', '340px'],
                    content: $('#change-role'),
                    cancel: function(index, layero){
                        location.reload()
                    }
                });
            });

            form.on('submit(save-asset)', function(data){
                $.post('{{ route('admin.user.change-asset') }}', {
                    id:data.field.id, type:data.field.type, asset_type:data.field.asset_type, amount:data.field.amount
                }, function (result) {
                    layer.alert(result.message, function () {
                        document.location.reload();
                    });
                }, 'json');

                return false;
            });

            form.on('submit(change-asset)', function (data) {
                $('#change-asset form').append('<input type="hidden" name="id" value="' + data.elem.getAttribute('data-id')  +  '">');
                layer.open({
                    type: 1,
                    shade: 0.2,
                    title: '增减(学/积)分',
                    area: ['400px', '320px'],
                    content: $('#change-asset')
                });
            });
        });
    </script>
@endsection