<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title></title>
    <!--[if lt IE 10]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="icon" href="/backend/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/backend/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/backend/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="/backend/icon/icofont/css/icofont.css">
    <link rel="stylesheet" type="text/css" href="/backend/icon/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/backend/css/style.css">
</head>
<body class="fix-menu">

<section class="login p-fixed d-flex text-center bg-primary common-img-bg">

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="login-card card-block auth-body mr-auto ml-auto">
                    <form class="md-float-material" method="POST" action="{{ route('admin.login') }}">
                        {{ csrf_field() }}
                        <div class="text-center">
                            <img src="/backend/images/logo.png" alt="logo.png">
                        </div>
                        <div class="auth-box">
                            <div class="row m-b-20">
                                <div class="col-md-12">
                                    <h3 class="text-left txt-primary">登录</h3>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                                <input id="email" type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="您的手机号"  required autofocus >
                                @if ($errors->has('phone'))
                                    <div class="text-left text-danger">
                                        {{ $errors->first('phone') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group  {{ $errors->has('password') ? ' has-error' : '' }}">
                                <input id="password" type="password" class="form-control" name="password" placeholder="您的密码" required>
                                @if ($errors->has('password'))
                                    <div class="text-left text-danger">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>
                            <div class="row m-t-25 text-left">
                                <div class="col-12">
                                    <div class="checkbox-fade fade-in-primary d-">
                                        <label>
                                            <input type="checkbox" value="" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                            <span class="text-inverse">记住登录状态</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-t-30">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">登录</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>

@include('backend.layouts.outdated-warning')

<script src="/backend/vendor/jquery/js/jquery.min.js"></script>
<script src="/backend/vendor/jquery-ui/js/jquery-ui.min.js"></script>
<script src="/backend/vendor/popper.js/js/popper.min.js"></script>
<script src="/backend/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="/backend/vendor/jquery-slimscroll/js/jquery.slimscroll.js"></script>
<script src="/backend/vendor/modernizr/js/modernizr.js"></script>
<script src="/backend/vendor/modernizr/js/css-scrollbars.js"></script>
<script src="/backend/vendor/i18next/js/i18next.min.js"></script>
<script src="/backend/vendor/i18next-xhr-backend/js/i18nextXHRBackend.min.js"></script>
<script src="/backend/vendor/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js"></script>
<script src="/backend/vendor/jquery-i18next/js/jquery-i18next.min.js"></script>
<script src="/backend/js/common-pages.js"></script>
</body>
</html>
