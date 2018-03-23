<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>注册</title>
    <link rel="stylesheet" type="text/css" href="/frontend/css/aui.css"/>
    <link rel="stylesheet" type="text/css" href="/frontend/layui/css/layui.css"/>
    <meta name="_token" content="{{ csrf_token() }}"/>
    <style type="text/css">
        .top-area {
        }

        .logo {
            height: 200px;
            display: -webkit-box;
            -webkit-box-orient: horizontal;
            -webkit-box-pack: center;
            -webkit-box-align: center;
            display: box;
            box-orient: horizontal;
            box-pack: center;
            box-align: center;
            background-color: #03a9f4;
        }

        .login-area {
            text-align: center;
        }

        .login-area img {
            width: 270px;
            border-radius: 8px;
            -webkit-border-radius: 8px;
        }
    </style>
</head>

<body>
<div class="top-area">
    <div class="logo">
        <div class="login-area">
            <img src="/frontend/image/login_logo.png" alt="" title=""/>
        </div>

    </div>
    <div class="" style="display: block; text-align: center; background-color: #03a9f4;color:#ffffff;">
        你的好友 {{ $inviteUser->phone }} 邀请您加入飕飕网络
    </div>

</div>
<div class="aui-content aui-margin-b-15">
    <ul class="aui-list aui-form-list">
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label-icon">
                    <i class="aui-iconfont aui-icon-mobile"></i>
                </div>
                <div class="aui-list-item-input">
                    <input type="text" placeholder="手机号" id="phone">
                </div>
            </div>
        </li>

        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label-icon">
                    <i class="aui-iconfont aui-icon-lock"></i>
                </div>
                <div class="aui-list-item-input">
                    <input type="password" placeholder="密码" id="password">
                </div>
            </div>
        </li>

        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label-icon">
                    <i class="aui-iconfont aui-icon-lock"></i>
                </div>
                <div class="aui-list-item-input">
                    <input type="password" placeholder="确认密码" id="rePassword">
                </div>

            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label-icon">
                    <i class="aui-iconfont aui-icon-lock"></i>
                </div>
                <div class="aui-list-item-input">
                    <input type="text" placeholder="请输入收到的验证码" id="code">

                </div>
                <span class="aui-input-addon">
                    <input teyp="button" class="aui-btn" id="sendVerify" value="获取验证码"  onclick="sendVerify()"/>
                </span>

            </div>
        </li>
    </ul>
</div>

<div class=" aui-margin-10">
    <div class="aui-btn aui-btn-block aui-btn-sm aui-btn-info aui-list-item-btn"  onclick="doRegister()">确定</div>
</div>

<div class=" aui-margin-10">
    <a href="http://app.szsousou.com/sou0111.apk" class="aui-btn aui-btn-block aui-btn-sm aui-btn-info aui-list-item-btn" >下载APP</a>
</div>

</body>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.js"></script>
<script src="/frontend/layui/layui.js"></script>
<script type="text/javascript">
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}});
    var  layer;

    layui.use('layer', function(){
         layer = layui.layer;
    });

    var countdown=60;
    function sendVerify(){
        if ($('#phone').val() == '' || $('#phone').val().length != 11) {
            layer.msg('请输入正确的手机号');
        } else {
            setTime($("#sendVerify"));
            $.post('{{ route('verify-code') }}', {phone:$('#phone').val()} ,function (ret) {
                layer.msg(ret.message);
            }, 'json');
        }
    }
    function setTime(obj) { //发送验证码倒计时
        if (countdown == 0) {
            obj.attr('disabled',false);
            obj.val("获取验证码");
            countdown = 60;
        } else {
            obj.attr('disabled',true);
            obj.val("重新发送(" + countdown + ")");
            countdown--;

        }


        setTimeout(function() {setTime(obj) },1000);
    }

    function doRegister() {
        var phone = $('#phone').val();
        var password = $('#password').val();
        var rePassword = $('#rePassword').val();
        var code = $('#code').val();
        if (!phone) {
            layer.msg('请输入手机号');
            return false;
        }
        if (!password) {
            layer.msg('请输入密码');
            return false;
        }
        if (!rePassword) {
            layer.msg('请再次确认密码');
            return false;
        }
        if (rePassword != password) {
            layer.msg('两次输入密码不一至');
            return false;
        }
        if (!code) {
            layer.msg('请输入验证码');
            return false;
        }
        $.post('{{ route('register', ['qr' => $inviteUser->qr_code]) }}', {phone:phone, password:password, code:code} ,function (ret) {
            if (ret.code == 1) {
                layer.msg('注册成功');
            } else {
                layer.msg(ret.message);
            }
        }, 'json');
    }
</script>

</html>
