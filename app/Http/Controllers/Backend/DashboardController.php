<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User;
use App\Models\Order;
use DB;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Backend
 */
class DashboardController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        //会员统计
        $user = User::count();
        $user_month = User::where("created_at","like", date("Y-m")."%")->count();
        //订单统计
        $order = Order::count();
        $order_month = Order::where("created_at","like", date("Y-m")."%")->count();
        //成交额统计
        $amount = Order::whereIn("status",array(2,3))->sum("amount");
        $amount_month = Order::whereIn("status",array(2,3))->where("created_at","like", date("Y-m")."%")->sum("amount");
        $msgs = DB::table('feedback as fb')->select('fb.*','u.avatar','u.nickname','u.phone')
            ->leftJoin('users as u', function($join){
                $join->on('fb.user_id', '=', 'u.id');
            })->orderBy('fb.id', 'desc')->paginate(10);
        return view('backend.dashboard.index',compact('msgs','user','user_month','order','order_month','amount','amount_month'));
    }
}
