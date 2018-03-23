@extends('backend.layouts.main')

@section('title', '实名认证')

@section('css')
    <link rel="stylesheet" type="text/css" href="/backend/vendor/layui/css/layui.css">
    <link href="/backend/mouse/index.css" rel="stylesheet" type="text/css" />
@endsection

@section('page-header')
    <div class="page-header card">
        <div class="card-block">
            <h5 class="m-b-10">实名认证</h5>
            <p class="text-muted m-b-10"></p>
            <ul class="breadcrumb-title b-t-default p-t-10">
                <li class="breadcrumb-item">
                    <a href=""> <i class="fa fa-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">实名认证</a>
                </li>
                <li class="breadcrumb-item"><a href="#!">认证列表</a>
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
                                    <option value="4">认证类型</option>
                                        <option value="1" @if(1 == $type) selected  @endif >已认证</option>
                                        <option value="2" @if(2 == $type) selected  @endif >未通过</option>
                                        <option value="0" @if(0 == $type) selected  @endif >待审核</option>
                                </select>
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
                                            <th>用户ID</th>
                                            <th>手机号</th>
                                            <th>真实姓名</th>
                                            <th>身份证号码</th>
                                            <th>IMME</th>
                                            <th>ICCID</th>
                                            <th>正面照片</th>
                                            <th>背面照片</th>
                                            <th>手持身份证照</th>
                                            <th>状态(审核)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($cards as $item)
                                            <tr>
                                                <td>{{ $item->user_id }}</td>
                                                <td>{{ $item->phone }}</td>
                                                <td>{{ $item->realname }}</td>
                                                <td>{{ $item->no }}</td>
                                                <td>{{ $item->device }}</td>
                                                <td>{{ $item->iccid }}</td>
                                                <td><a target="_blank" href="/storage/idcard/{{ $item->img_path1 }}.jpg">
                                                    <img width="30px" height="30px" src="/storage/idcard/{{ $item->img_path1 }}.jpg" onMouseOver="toolTip('<img src=/storage/idcard/{{ $item->img_path1 }}.jpg>')" onMouseOut="toolTip()"></a></td>

                                                <td><a target="_blank" href="/storage/idcard/{{ $item->img_path2 }}.jpg">
                                                    <img width="30px" height="30px" src="/storage/idcard/{{ $item->img_path2 }}.jpg" onMouseOver="toolTip('<img src=/storage/idcard/{{ $item->img_path2 }}.jpg>')" onMouseOut="toolTip()"></a></td>


                                                <td><a target="_blank" href="/storage/idcard/{{ $item->img_path3 }}.jpg">
                                                    <img width="30px" height="30px" src="/storage/idcard/{{ $item->img_path3 }}.jpg" onMouseOver="toolTip('<img src=/storage/idcard/{{ $item->img_path3 }}.jpg>')" onMouseOut="toolTip()"></a></td>

                                                <td>
                                                    @if ($item->status == 1)
                                                    已认证
                                                    @elseif ($item->status == 2)
                                                    未通过
                                                    @else
                                                        <a style="color:blue;" href="javascript::void(0)" onclick="do_check({{ $item->id }},1);">通过</a>
                                                    <a style="color:blue;" href="javascript::void(0)" onclick="do_check({{ $item->id }},2);">未通过</a>
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
                            {{ $cards->appends([
                                'type' => $type,
                                  ])->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/vendor/layui/layui.js "></script>
    <script src="/backend/mouse/ToolTip.js "></script>
    <script>
        function do_check(id,status){
            var msg = "确认操作？";
            if (confirm(msg)==true){
            }else{
                return false;
            }
            $.ajax({
                url: "/admin/user/iddocheck",
                data: {"id":id,"status":status},
                timeout: 5000,
                cache: false,
                type: "post",
                dataType: "json",
                success: function (d,s,r) {
                    if(d){
                        if(d.status == 0){
                            alert("操作成功");
                            setTimeout(function(){
                                window.location.reload();
                            },1000);
                            return;
                        }else{
                            return alert(d.msg);
                        }
                    }else{
                        return alert("系统发生不可预期的错误！");
                    }
                }
            });
        }
    </script>
@endsection