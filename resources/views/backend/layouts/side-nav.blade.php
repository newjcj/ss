<nav class="pcoded-navbar">
    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
    <div class="pcoded-inner-navbar main-menu">

        <div class="pcoded-navigation-label">导航</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="@if(Route::currentRouteName() == 'admin.dashboard') active  @endif">
                <a href="{{ route('admin.dashboard') }}">
                    <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                    <span class="pcoded-mtext">数据面板</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>

        <div class="pcoded-navigation-label">用户</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="@if(Route::currentRouteName() == 'admin.user.index') active  @endif">
                <a href="{{ route('admin.user.index') }}">
                    <span class="pcoded-micon"><i class="ti-crown"></i><b>D</b></span>
                    <span class="pcoded-mtext">推广员管理</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="@if(Route::currentRouteName() == 'admin.user.idcard') active  @endif">
                <a href="{{ route('admin.user.idcard') }}">
                    <span class="pcoded-micon"><i class="ti-crown"></i><b>D</b></span>
                    <span class="pcoded-mtext">认证管理</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>

        <div class="pcoded-navigation-label">商品</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="@if(Route::currentRouteName() == 'admin.goods.index') active  @endif">
                <a href="{{ route('admin.goods.index') }}">
                    <span class="pcoded-micon"><i class="ti-crown"></i><b>D</b></span>
                    <span class="pcoded-mtext">商品列表</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="@if(Route::currentRouteName() == 'admin.goods.create') active  @endif">
                <a href="{{ route('admin.goods.create') }}">
                    <span class="pcoded-micon"><i class="ti-layers-alt"></i><b>S</b></span>
                    <span class="pcoded-mtext">发布商品</span>
                    <span class="pcoded-badge label label-danger">HOT</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>

        <div class="pcoded-navigation-label">分类</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="<?php echo Request::is('admin/category/list') ? 'active' : ''?>">
                <a href="/admin/category/list">
                    <span class="pcoded-micon"><i class="ti-crown"></i><b>D</b></span>
                    <span class="pcoded-mtext">分类列表</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="<?php echo Request::is('admin/category/add') ? 'active' : ''?>">
                <a href="/admin/category/add">
                    <span class="pcoded-micon"><i class="ti-crown"></i><b>D</b></span>
                    <span class="pcoded-mtext">添加分类</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>

        <div class="pcoded-navigation-label">订单</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="@if(Route::currentRouteName() == 'admin.order.index') active  @endif">
                <a href="{{ route('admin.order.index') }}">
                    <span class="pcoded-micon"><i class="ti-crown"></i><b>D</b></span>
                    <span class="pcoded-mtext">订单列表</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>

        <div class="pcoded-navigation-label">财务</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="">
                <a href="{{ route('admin.user-flow.index') }}">
                    <span class="pcoded-micon"><i class="ti-crown"></i><b>D</b></span>
                    <span class="pcoded-mtext">资金流水</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="">
                <a href="{{ route('admin.withdrawal.index') }}">
                    <span class="pcoded-micon"><i class="ti-crown"></i><b>D</b></span>
                    <span class="pcoded-mtext">兑换管理</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>

        <div class="pcoded-navigation-label">系统</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="">
                <a href="{{ route('admin.system.notice') }}">
                    <span class="pcoded-micon"><i class="ti-crown"></i><b>D</b></span>
                    <span class="pcoded-mtext">公告</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </div>
</nav>