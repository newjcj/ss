<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use App\Models\User;
use App\Service\Phone;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use Auth, DB, QrCode, Image;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class TestController extends Controller
{

    public function test(Request $request)
    {
        print_r(32);exit;
        $goods = Goods::find(33);
        print_r($goods->invocational);exit;
    }

    public function jcj(Request $request)
    {
        print_r(3);exit;
    }
}
