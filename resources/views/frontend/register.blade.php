<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>注册</title>
    <meta name="_token" content="{{ csrf_token() }}"/>
</head>
<style>
    img{max-width: 100%; height: auto;}
    @media (min-width: 640px) {
        html {
            font-size: 64px;
        }

        input, textarea {
            font-size: 25.6px;
        }
    }

    @media (min-width: 750px) {
        html {
            font-size: 75px;
        }

        input, textarea {
            font-size: 30px;
        }
    }

    @media (min-width: 800px) {
        html {
            font-size: 80px;
        }

        input, textarea {
            font-size: 32px;
        }
    }

    @media (min-width: 1024px) {
        html {
            font-size: 102.4px;
        }

        input, textarea {
            font-size: 50px;
        }
    }

    @media (min-width: 1280px) {
        html {
            font-size: 128px;
        }
    }

    @media (min-width: 1366px) {
        html {
            font-size: 136.6px;
        }
    }

    @media (min-width: 1440px) {
        html {
            font-size: 144px;
        }
    }

    body, html, ul, li {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    body {
        width: 100%;
        height: 100%;
        background: url(/frontend/image/all.png) no-repeat;
        top: 0;
        background-size: cover;
    }

    .warp {
        width: 100%;
    }

    .warp img {
        display: block;
        width: 100%;
    }

    .yourFriend {
        text-align: center;
    }

    .fyl {
        display: inline-block;
        /* float: left; */
    }

    .fylr {
        padding-left: 0.8rem;
        color: #f98456;
    }

    .main {
        width: 80%;
        margin: 0 auto;
        height: 500px;
    }

    .input {
        width: 100%;
        height: 50px;
        border-radius: 30px;
        box-sizing: border-box;
        font-size: 20px;
        padding-left: 20px;
        border: 1px solid #656565;
    }

    .main ul li input:first-child {
        margin-top: 20px;
    }

    .tip {
        margin-left: 20px;
        margin-top: 5px;
        color: red;
    }

    .input-btn {
        position: absolute;
        right: 7px;
        top: 40%;
        color: #009ee7;
        width: 90px;
        height: 30px;
        line-height: 30px;
        border-left: 2px solid #999;
        border-top: none;
        border-right: none;
        border-bottom: none;
        padding-left: 10px;
        font-size: 14px;
    }

    .login-go {
        display: block;
        width: 100%;
        height: 50px;
        border-radius: 30px;
        box-sizing: border-box;
        font-size: 20px;
        border: 2px solid #d65132;
        background-color: #fa8559;
        text-align: center;
        line-height: 50px;
        color: #fff;
        margin-top: 20px;
    }

    .main ul li .lastinput {
        width: 30px;
        height: 15px;
        background-color: #009ee7;
        margin-top: 0;

    }

    .main ul li .lastinput:checked {
        background-color: #009ee7;
    }

    .lastspan {
        /* width:30px; */
        height: 30px;
        display: inline-block;
        text-align: center;
        line-height: 30px;
        color: #009ee7;
    }

    .downAPP {
        text-align: center;
        color: #009ee7;
        font-size: 1.32rem;
    }
</style>
<body>
<div class="warp">
    <img src="/frontend/image/bg1_02.png" alt="">
</div>
<div class="yourFriend">
    <div style="height: 25px;line-height: 25px;margin-bottom: 10px;">
        <span style="font-size: 16px;">您的好友</span>
        <span style="font-size: 27px;font-weight:700;color:#fa8559">{{ $inviteUser->phone }} </span>
    </div>
    <div style="height: 25px;line-height: 25px;font-size: 18px">邀请您使用飕飕随身WIFI</div>
</div>

<div class="main">
    <ul>
        <li>
            <input type="text" class="input" placeholder="请输入手机号" id="phone">
        </li>
        <li>
            <input type="password" class="input" placeholder="设置登录密码" id="password">
        </li>
        <li style="position:relative">
            <input type="text" class="input" placeholder="请输入验证码" id="code">
            <input class="input-btn" id="btnSendCode" onclick="sendMessage()" value="获取验证码">
        </li>
        <li><span class="login-go" onclick="doRegister()">立即注册</span>
        </li>
        <li>
            <p><input style="margin-top:0" type="checkbox" class="lastinput">
                <span class="lastspan">我已阅读并同意</span>
                <span class="lastspan">飕飕用户服务协议</span>
            </p>
            <a id="downloadLinkIOS" style="margin-left:40px;text-decoration:none" class="downAPP" >iOS下载</a>
            <a id="downloadLinkAND" style="margin-left:10px;text-decoration:none" class="downAPP">android下载</a>
            <!--<a id="downloadLink" style="text-decoration:none">
                <p class="downAPP">iOS下载</p>
            </a>
            <a id="downloadLink" style="text-decoration:none">
                <p class="downAPP">安卓下载</p>
            </a>-->
        </li>
    </ul>
</div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.js"></script>
<script src="/frontend/layui/layui.js"></script>
<script type="text/javascript">
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}});
    var layer;

    layui.use('layer', function () {
        layer = layui.layer;
    });

    var interValObj; //timer变量，控制时间
    var count = 60; //间隔函数，1秒执行
    var curCount;//当前剩余秒数

    function sendMessage() {
        if ($('#phone').val() == '' || $('#phone').val().length != 11) {
            layer.msg('请输入正确的手机号');
        } else {
            curCount = count;
            //设置button效果，开始计时
            $("#btnSendCode").attr("disabled", "true");
            $("#btnSendCode").val(curCount + "秒后可重发");
            InterValObj = window.setInterval(setRemainTime, 1000); //启动计时器，1秒执行一次

            //请求后台发送验证码
            $.post('{{ route('verify-code') }}', {phone: $('#phone').val()}, function (ret) {
                layer.msg(ret.message);
            }, 'json');
        }
    }
    //timer处理函数
    function setRemainTime() {
        if (curCount == 0) {
            window.clearInterval(interValObj);//停止计时器
            $("#btnSendCode").removeAttr("disabled");//启用按钮
            $("#btnSendCode").val("重新发送");
        }
        else {
            curCount--;
            $("#btnSendCode").val(curCount + "秒后可重发");
        }
    }

    function doRegister() {
        var phone = $('#phone').val();
        var password = $('#password').val();
        var code = $('#code').val();
        if (!phone) {
            layer.msg('请输入手机号');
            return false;
        }
        if (!password) {
            layer.msg('请输入密码');
            return false;
        }
        if (!code) {
            layer.msg('请输入验证码');
            return false;
        }
        $.post('{{ route('register', ['qr' => $inviteUser->qr_code]) }}', {
            phone: phone,
            password: password,
            code: code
        }, function (ret) {
            if (ret.code == 1) {
                layer.msg('注册成功');
            } else {
                layer.msg(ret.message);
            }
        }, 'json');
    }
    function is_weixin() {
        var ua = navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == "micromessenger") {
            return true;
        } else {
            return false;
        }
    }
    var isWeixin = is_weixin();
    var winHeight = typeof window.innerHeight != 'undefined' ? window.innerHeight : document.documentElement.clientHeight;
    function loadHtml() {
        var div = document.createElement('div');
        div.id = 'weixin-tip';
        div.innerHTML = '<p><img src="/frontend/image/live_weixin.png" alt="微信打开"/></p>';
        document.body.appendChild(div);
    }

    function loadStyleText(cssText) {
        var style = document.createElement('style');
        style.rel = 'stylesheet';
        style.type = 'text/css';
        try {
            style.appendChild(document.createTextNode(cssText));
        } catch (e) {
            style.styleSheet.cssText = cssText; //ie9以下
        }
        var head = document.getElementsByTagName("head")[0];
        head.appendChild(style);
    }
    var cssText = "#weixin-tip{position: fixed; left:0; top:0; background: rgba(0,0,0,0.8); filter:alpha(opacity=80); width: 100%; height:100%; z-index: 100;} #weixin-tip p{text-align: center; margin-top: 10%; padding:0 5%;}";
    if (isWeixin) {
        loadHtml();
        loadStyleText(cssText);
    }

    $('#downloadLinkAND').attr('href', '{{ env('A_LINK') }}');
    $('#downloadLinkIOS').attr('href', '{{ env('I_LINK') }}');
    /*
    var u = navigator.userAgent, app = navigator.appVersion;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

    if (isAndroid) {
        $('#downloadLink').attr('href', '{{ env('A_LINK') }}');
    } else {
        $('#downloadLink').attr('href', '{{ env('I_LINK') }}');
    }
    */

</script>

</html>