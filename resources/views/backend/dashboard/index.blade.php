@extends('backend.layouts.main')

@section('title', '数据面板')

@section('css')

@endsection

@section('content')
    <div class="row">
        <div class="col-md-6 col-xl-4">
            <div class="card bg-c-yellow order-card">
                <div class="card-block">
                    <h6 class="m-b-20">用户数</h6>
                    <h2 class="text-right"><i class="ti-reload f-left"></i><span>{{$user}}人</span></h2>
                    <p class="m-b-0">本月<span class="f-right">{{$user_month}}人</span></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card bg-c-blue order-card">
                <div class="card-block">
                    <h6 class="m-b-20">订单数据</h6>
                    <h2 class="text-right"><i class="ti-shopping-cart f-left"></i><span>{{$order}} 单</span></h2>
                    <p class="m-b-0">本月<span class="f-right">{{$order_month}} 单</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card bg-c-green order-card">
                <div class="card-block">
                    <h6 class="m-b-20">销售额</h6>
                    <h2 class="text-right"><i class="ti-tag f-left"></i><span>{{$amount}} 元</span></h2>
                    <p class="m-b-0">本月<span class="f-right">{{$amount_month}} 元</span></p>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>用户反馈</h5>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            <li><i class="fa fa-chevron-left"></i></li>
                            <li><i class="fa fa-window-maximize full-card"></i></li>
                            <li><i class="fa fa-minus minimize-card"></i></li>
                            <li><i class="fa fa-refresh reload-card"></i></li>
                            <li><i class="fa fa-times close-card"></i></li>
                        </ul>
                    </div>
                </div>

                <!--<div class="card-block">
                    <ul class="feed-blog">
                        <li class="active-feed">
                            <div class="feed-user-img">
                                <img src="/backend/images/avatar-3.jpg" class="img-radius " alt="User-Profile-Image">
                            </div>
                            <h6><span class="label label-danger">File</span> Eddie uploaded new files: <small class="text-muted">2 hours ago</small></h6>
                            <p class="m-b-15 m-t-15">hii <b> @everone</b> Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                            <div class="row">
                                <div class="col-auto text-center">
                                    <img src="/backend/images/blog/blog-r-1.jpg" alt="img" class="img-fluid img-100">
                                    <h6 class="m-t-15 m-b-0">Old Scooter</h6>
                                    <p class="text-muted m-b-0"><small>PNG-100KB</small></p>
                                </div>
                                <div class="col-auto text-center">
                                    <img src="/backend/images/blog/blog-r-2.jpg" alt="img" class="img-fluid img-100">
                                    <h6 class="m-t-15 m-b-0">Wall Art</h6>
                                    <p class="text-muted m-b-0"><small>PNG-150KB</small></p>
                                </div>
                                <div class="col-auto text-center">
                                    <img src="/backend/images/blog/blog-r-3.jpg" alt="img" class="img-fluid img-100">
                                    <h6 class="m-t-15 m-b-0">Microphone</h6>
                                    <p class="text-muted m-b-0"><small>PNG-150KB</small></p>
                                </div>
                            </div>
                        </li>
                        <li class="diactive-feed">
                            <div class="feed-user-img">
                                <img src="/backend/images/avatar-4.jpg" class="img-radius " alt="User-Profile-Image">
                            </div>
                            <h6><span class="label label-success">Task</span>Sarah marked the Pending Review: <span class="text-c-green"> Trash Can Icon Design</span><small class="text-muted">2 hours ago</small></h6>
                        </li>
                        <li class="diactive-feed">
                            <div class="feed-user-img">
                                <img src="/backend/images/avatar-2.jpg" class="img-radius " alt="User-Profile-Image">
                            </div>
                            <h6><span class="label label-primary">comment</span> abc posted a task: <span class="text-c-green">Design a new Homepage</span> <small class="text-muted">6 hours ago</small></h6>
                            <p class="m-b-15 m-t-15">hii <b> @everone</b> Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        </li>
                        <li class="active-feed">
                            <div class="feed-user-img">
                                <img src="/backend/images/avatar-3.jpg" class="img-radius " alt="User-Profile-Image">
                            </div>
                            <h6><span class="label label-warning">Task</span>Sarah marked : <span class="text-c-green"> do Icon Design</span><small class="text-muted">10 hours ago</small></h6>
                        </li>
                    </ul>
                </div>-->


                <div class="card-block">
                    <ul class="feed-blog">

                        @forelse($msgs as $item)
                        <li class="diactive-feed">
                            <div class="feed-user-img">
                                <img src="/avatar/{{$item->avatar}}" class="img-radius " alt="">
                            </div>
                            <h6>
                                <span class="label label-primary">comment</span> {{$item->nickname}}({{$item->phone}}): 
                                <small class="text-muted">{{$item->created_at}}</small>
                            </h6>

                            <p class="m-b-15 m-t-15">{{$item->content}}</p>
                        </li>

                        @empty
                            暂时没有数据
                        @endforelse

                    </ul>
                </div>

{{ $msgs->appends([])->links() }}

            </div>
        </div>

    </div>
@endsection


@section('js')

@endsection