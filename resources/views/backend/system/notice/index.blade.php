@extends('backend.layouts.main')

@section('title', '公告')

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
            <h5 class="m-b-10">公告</h5>
            <p class="text-muted m-b-10"></p>
            <ul class="breadcrumb-title b-t-default p-t-10">
                <li class="breadcrumb-item">
                    <a href=""> <i class="fa fa-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#">系统</a>
                </li>
                <li class="breadcrumb-item"><a href="#">公告</a>
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
                    <form method="post" action="{{ route('admin.system.notice.update') }}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-12 col-xl-12 m-b-30">
                                <h4 class="sub-title">公告</h4>
                                <textarea id="container" rows="20" cols="20" class="form-control"
                                          placeholder="Default textarea" name="contents">{{ $notice->content ?? '' }}</textarea>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block" lay-submit="" lay-filter="save">确认添加</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script src="/backend/vendor/layui/layui.js "></script>
    <script>

        layui.use('table', function () {
            var table = layui.table, form = layui.form;

           {{--form.on('submit(save)', function (data) {--}}
               {{--console.log(data.field);--}}
              {{--$.post('{{ route('admin.system.notice.update') }}', {contents:data.field.content}, function () {--}}
                  {{--layer.msg('更新成功');--}}
              {{--}, 'json');--}}
               {{--return false;--}}
           {{--});--}}
        });

        var ue = UE.getEditor('container');
        ue.ready(function () {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });

    </script>
@endsection
@include('vendor.ueditor.assets')