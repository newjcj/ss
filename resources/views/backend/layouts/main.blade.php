<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>@yield('title') | {{ config('app.name') }} </title>
    <!--[if lt IE 10]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://cdn.bootcss.com/underscore.js/1.8.3/underscore-min.js"></script>
    <scripte src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></scripte>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="_token" content="{{ csrf_token() }}"/>

    <link rel="icon" href="/backend/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="/backend/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/backend/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="/backend/icon/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/backend/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="/backend/pages/chart/radial/css/radial.css" type="text/css" media="all">
    <link rel="stylesheet" type="text/css" href="/backend/css/style.css">
    @yield('css')
</head>
<body>

<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">

        @include('backend.layouts.top-nav')

        <div class="pcoded-main-container">
            <div class="pcoded-wrapper">

                @include('backend.layouts.side-nav')

                <div class="pcoded-content">
                    <div class="pcoded-inner-content">

                        <div class="main-body">
                            <div class="page-wrapper">
                                   @yield('page-header')
                                <div class="page-body">
                                    @yield('content')
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('backend.layouts.outdated-warning')

<script src="/backend/vendor/jquery/js/jquery.min.js "></script>
<script src="/backend/vendor/jquery-ui/js/jquery-ui.min.js "></script>
<script src="/backend/vendor/popper.js/js/popper.min.js"></script>
<script src="/backend/vendor/bootstrap/js/bootstrap.min.js "></script>
<script src="/backend/pages/widget/excanvas.js "></script>

<script src="/backend/vendor/jquery-slimscroll/js/jquery.slimscroll.js "></script>

<script src="/backend/vendor/modernizr/js/modernizr.js "></script>

<script src="/backend/js/SmoothScroll.js"></script>
<script src="/backend/js/jquery.mCustomScrollbar.concat.min.js "></script>

<script src="/backend/vendor/chart.js/js/Chart.js"></script>
<script src="/backend/pages/widget/amchart/amcharts.js"></script>
<script src="/backend/pages/widget/amchart/serial.js"></script>
<script src="/backend/pages/widget/amchart/light.js"></script>

<script src="/backend/js/pcoded.min.js"></script>
<script src="/backend/js/vertical/vertical-layout.min.js "></script>

<script src="/backend/pages/dashboard/custom-dashboard.js"></script>
<script src="/backend/js/script.js "></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
</script>
@yield('js')
</body>
</html>
